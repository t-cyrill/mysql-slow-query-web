<?php
class Translates {
    public static $lang = 'ja';
    public static $map = [
        'ja' => [
            'latest_100_query' => '最新100件',
            'slow_top_100_query' => '遅い順100件',
            'sent_top_100_query' => '転送量が多い順100件',
            'rows_examined_top_100_query' => '探索が多い100件',
        ],
        'en' => [
            'latest_100_query' => 'Recent 100 queries',
            'slow_top_100_query' => 'Top slow 100 queries',
            'sent_top_100_query' => 'Top sent 100 queries',
            'rows_examined_top_100_query' => 'Top rows_examined 100 queries',
        ]
    ];
    public static function getMap() {
        return self::$map[self::$lang];
    }
}
function t($id, ...$params) {
    $map = Translates::getMap();
    if (!isset($map[$id])) {
        throw new RuntimeException('Translate identifier not defined');
    }
    return sprintf($map[$id], ...$params);
}

