<?php
namespace App\Helpers;

use FeedWriter\ATOM;
use FeedWriter\RSS2;

class Helper
{
    public static function myOrderBy($collection, $col_name, $seq = 'asc')
    {
        $order_str = $col_name;
        if(config('DB_CONNECTION', '') === 'sqlite') {
            $order_str = "convert(datetime, $col_name, 103)";
        }
        return $collection->orderBy($order_str, $seq);
    }
    public static function pageToFeed($type, $pages)
    {
        switch($type){
            case 'atom':
                $feed = new ATOM;
                foreach($pages as $page){
                    $item = $feed->createNewItem();
                    $item->setTitle($page->title);
                    $item->setLink(url('/page?id=' . $page->id));
                    $item->setDate(strtotime($page->created_at));
                    $item->setAuthor(\App\User::find($page->user_id)->name);
                    $item->setDescription(mb_substr($page->body, 0, 100));
                    $feed->addItem($item);
                }
                return $feed->generateFeed();
            case 'rss2.0':
                $feed = new RSS2;
                $feed->setTitle(config('app.name', ''));
                $feed->setLink(url('/'));
                $feed->setDescription('');
                $feed->setDate(date(\DATE_RSS, time()));
                $feed->setChannelElement("language","ja-JP");
                $feed->setChannelElement("pubDate",date(\DATE_RSS, time()));
                $feed->setChannelElement("category", "Blog");
                foreach($pages as $page){
                    $item=$feed->createNewItem();
                    $item->setTitle($page->title);
                    $item->setLink(url('/page?id=' . $page->id));
                    $item->setDescription(mb_substr($page->body, 0, 100));
                    $item->setDate(strtotime($page->created_at));
                    $item->setId(url('/page?id=' . $page->id), true);
                    $feed->addItem($item);
                }
                return $feed->generateFeed();
            default:
                return "";
            }
    }
}
