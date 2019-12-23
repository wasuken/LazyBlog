<?php
namespace App\Helpers;

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
}
