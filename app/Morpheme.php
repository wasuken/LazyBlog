<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Morpheme extends Model
{
    //
    protected $guarded = array('created_at');
    public static function parseNouns($target, $mecab)
    {
        $nodes = $mecab->parseToNode($target);
        $ms = array();
        foreach ($nodes as $n)
        {
            if(preg_match('/名詞/', $n->getFeature()) > 0){
                $ms = array_merge($ms, array($n->getSurface()));
            }
        }
        return $ms;
    }
}
