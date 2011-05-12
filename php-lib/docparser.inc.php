<?php
class Doc {

    public static $lessheady_location = 'lessheady/';
    public static $sep_expr = "//--------------------------------------------------------------------";
    public static $doc_items = array();
    public static $purifier;
    
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
	
	static function format_text($string) {
	    $string_lines = explode("\n", $string);
	    $line_parsed = array();
	    foreach($string_lines as $line) {
	        $line_parsed[] = trim(preg_replace("/^\/\//", '', $line), " ");
	    }
	    $output = implode("\n", $line_parsed);
	    $output = Markdown($output);
	    return self::$purifier->purify( $output);
	}
	
    function parse($path) {
        $content = file_get_contents($path);
        $regex = (self::$sep_expr.'(.*)'.self::$sep_expr);
        
        $doc_items = array();
        
        if (preg_match_all("|$regex|msU", $content, $out)) {
            foreach($out[1] as $doc) {
                $doc_items[] = self::format_text($doc);
            }
        } else {
            return false;
        }
        
        return $doc_items;
    }
    
    public function get_content() {
        $less_files = self::rglob('*.less', 0, self::$lessheady_location);
        unset($less_files[0]);
        sort($less_files);
        
        foreach($less_files as $f) {
            $real_location = str_replace(self::$lessheady_location, '', $f);
            $explode_dir = explode('/', dirname($real_location));
            $title_dir = end($explode_dir);
            $filename = str_replace('.less', '', $real_location);
           
            if (!array_key_exists($title_dir, self::$doc_items)) {
                self::$doc_items[$title_dir] = array(
                    'title' => ucfirst($title_dir),
                    'location' => dirname($f).'/',
                    'items' => array(),
                    'content' => false,
                );
            }
            
            if (self::$doc_items[$title_dir]['content'] === false) {
                if (file_exists(dirname($f).'/README')) {
                    self::$doc_items[$title_dir]['content'] = self::format_text(file_get_contents(dirname($f).'/README'));
                } else {
                    self::$doc_items[$title_dir]['content'] = '';
                }
            }
            
            $parsed_content = self::parse($f);
            
            if ($parsed_content) {
                self::$doc_items[$title_dir]['items'][] = array(
                    'location' => $f,
                    'content' => $parsed_content
                );
            }
        }
        return self::$doc_items;
    }
    
    function __construct() {
        require_once 'htmlpurifier/library/HTMLPurifier.auto.php';
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Doctype', 'XHTML 1.0 Strict');
        $config->set('AutoFormat.AutoParagraph', true);
        self::$purifier = new HTMLPurifier();
        include_once "markdown.php";
    }
}
