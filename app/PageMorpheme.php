<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PageMorpheme extends Model
{
    //
    protected $guarded = array('created_at');
    public static function insertBodyDecomposeWords($page)
    {
        // bodyを形態素分解して名詞のみを取り出す。
        $mecab = new \Mecab\Tagger();
        $ns = \App\Morpheme::parseNouns($page->body, $mecab);
        // morphemesに登録されてない名詞を挿入する。
        $words = array_map(function($x){
            return $x['word'];
        }, \App\Morpheme::all()->toArray());

        if(empty($words)){
            Log::info('empty morphemes table record.');
            return;
        }
        $insertRecs = array();
        foreach(array_diff($ns, $words) as $v){
            $insertRecs = array_merge($insertRecs, [
                [
                    'word' => $v,
                ]
            ]);
        }
        DB::table('morphemes')->insert($insertRecs);
        $insertRecs = array();
        // 登録後、登録した名詞と対応するレコードを抽出する。
        // スコアリング(tfのみ)
        foreach(\App\Morpheme::whereIn('word', $ns)->get() as $x){
            $cnt = 0;
            foreach($words as $w){
                if($x->word === $w){
                    $cnt++;
                }
            }
            $insertRecs = array_merge($insertRecs, [
                [
                    'page_id' => $page->id,
                    'morpheme_id' => $x->id,
                    'tf' => $cnt /  count($words),
                    'idf' => 0,
                    'score' => 0,
                ]
            ]);
        }
        // 登録時点ではidf及びscoreを計算しない。
        // すべて挿入下後に改めて計算して後進した方がパフォーマンス敵によいとはんだんしたため。
        DB::table('page_morphemes')->insert($insertRecs);
    }
    public static function scoring()
    {
        $pms = \App\PageMorpheme::join('morphemes', 'morphemes.id', 'page_morphemes.morpheme_id')
             ->join('pages', 'pages.id', 'page_morphemes.page_id')
             ->select('morphemes.word as word', 'page_morphemes.*');

        foreach($pms->get() as $pm){
            $cnt = \App\PageMorpheme::where('morpheme_id', $pm->morpheme_id)
                 ->groupBy('page_id')
                 ->count();
            $pm->idf = log(\App\Page::count() / $cnt);
            $pm->score = $pm->idf * $pm->tf;
            $pm->save();
        }
    }
    public static function search($query, $pages)
    {
        $queryParams = preg_split('/\s+/', mb_strtolower(preg_replace('/　/', ' ', $query)));
        $qpMore = array();
        $mecab = new \Mecab\Tagger();
        foreach($queryParams as $x){
            $qpMore = array_merge($qpMore, \App\Morpheme::parseNouns($x, $mecab));
        }
        $result = array();
        $pageMorphemesIncQueryParams = $pages
                                     ->join('page_morphemes',
                                            'pages.id',
                                            'page_morphemes.page_id')
                                     ->join('morphemes', 'morphemes.id', 'page_morphemes.morpheme_id')
                                     ->whereIn('word', $queryParams)
                                     ->orderBy('score', 'desc')
                                     ->get();
        foreach($pageMorphemesIncQueryParams as $x){
            if(!isset($result[$x->page_id])){
                $result[$x->page_id] = $x;
            }
        }
        $result = array_values($result);
        usort($result, function($a, $b){
            return $a->score <=> $b->score;
        });
        return $result;
    }
}
