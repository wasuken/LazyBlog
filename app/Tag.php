<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    protected $guarded = array('created_at');
    public static function tagsCreate($tags, $page_id)
    {
        if(!empty($tags)){
            foreach($tags as $tag){
                $target_tag = \App\Tag::where('name', $tag)->first();
                if($target_tag === null){
                    $target_tag = \App\Tag::create([
                        'name' => $tag,
                    ]);
                }
                \App\PageTag::create([
                    'page_id' => $page_id,
                    'tag_id' => $target_tag->id,
                ]);
            }
        }
    }
}
