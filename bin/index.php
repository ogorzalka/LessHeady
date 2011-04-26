<?php
class LessHeady {
    public static $lessheady_location = '../lessheady/';
    static function rglob($pattern, $flags = 0, $path = '') {
	    if (!$path && ($dir = dirname($pattern)) != '.') {
	        if ($dir == '\\' || $dir == '/') $dir = '';
	        return self::rglob(basename($pattern), $flags, $dir . '/');
	    }
	    $paths = glob($path . '*', GLOB_ONLYDIR | GLOB_NOSORT);
	    $files = glob($path . $pattern, $flags);
	    foreach ($paths as $p) $files = array_merge($files, self::rglob($pattern, $flags, $p . '/'));
	    return $files;
	}
	
	static function load_css_tpl($tplname) {
	    if ($content = @file_get_contents($tplname)) {
	        return $content;
	    }
	    return '';
	}
    
    function __construct() {
        $less_files = self::rglob('*.less', 0, self::$lessheady_location);
        unset($less_files[0]);
        sort($less_files);
        
        $current_title_dir = false;
        $output = "";
        
        foreach($less_files as $less_file) {
            $real_location = str_replace(self::$lessheady_location, '', $less_file);
            $explode_dir = explode('/', dirname($real_location));
            $title_dir = ucfirst(end($explode_dir));
            $filename = str_replace('.less', '', $real_location);
            
            if ($title_dir != $current_title_dir) {
                $output .= "\n// $title_dir\n";
            }
            $output .= "@import '$filename';\n";
            
            $current_title_dir = $title_dir;
        }
        
        $tpl = self::load_css_tpl('tpl/loader.css');
        $formatted_output = str_replace('{imported_files}', $output, $tpl);
        file_put_contents(self::$lessheady_location.'loader.less', $formatted_output);
    }
}

new LessHeady;
