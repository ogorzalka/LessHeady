<?php
class RgbaShortcut {
    public static function find_cache_folder()
    {
        $uri       = explode('/mixins/', str_replace('\\', '/', dirname(__FILE__)));
        $cache_dir = $uri[0] . 'cache/rgba/';
        if (!file_exists($cache_dir)) {
            mkdir($cache_dir, 0777);
        }
        return $cache_dir;
    }
    
    function __construct() {
        $conf['cachedir'] = self::find_cache_folder();
        require('rgba/rgba.php');
    }
}
new RgbaShortcut();