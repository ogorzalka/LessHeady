<?php
/***************************************************************************************
 * Script for automatic generation of one pixel
 * alpha-transparent images for non-RGBA browsers.
 * @author Lea Verou
 * @version 1.2
 * Licensed under the MIT license (http://www.opensource.org/licenses/mit-license.php)
 ***************************************************************************************/

// Only report errors that would stop the script anyway, since the output is an image so even an
// extra byte will prevent it from showing up
error_reporting(E_ERROR | E_PARSE);

class Rgba {
    
    ######## SETTINGS ##############################################################
    
    /**
     * Enter the directory in which to store cached color images.
     * This should be relative.
     * The directory you specify should exist and be writeable (usually chmoded to 777).
     * If you want to store the pngs at the same directory, leave blank ('').
     */
    public static $conf = array(
        'cachedir' => 'cache',
        
        /**
         * If you don't want the generated pngs to be cached on the server, set the following to
         * false. This is STRONGLY NOT RECOMMENDED. It's here only for testing/debugging purposes.
         */
        'cachepngs' => true,

        /**
         * If you want the generated image to have a greater or smaller size than 10x10, you may adjust the following.
         * Apart from debugging purposes (it's easier to see if the image has a problem when it's something
         * large), some people argue that the browser needs to spend substantially more resources
         * to render the background when the image is too small (like 1x1).
         */
        'size' => 10,
    );
        
    /**
     * If you frequently use a color with varying alphas, you can name it
     * below, to save you some typing and make your CSS easier to read.
     */
    public static $color_names = array(
        // valid W3C
        'aqua' => array(0, 255, 255), 
        'black' => array(0, 0, 0), 
        'blue' => array(0, 0, 255), 
        'fuchsia' => array(255, 0, 255), 
        'gray' => array(128, 128, 128), 
        'green' => array(0, 128, 0), 
        'lime' => array(0, 255, 0), 
        'maroon' => array(128, 0, 0), 
        'navy' => array(0, 0, 128), 
        'olive' => array(128, 128, 0), 
        'purple' => array(128, 0, 128), 
        'red' => array(255, 0, 0), 
        'silver' => array(192, 192, 192), 
        'teal' => array(0, 128, 128), 
        'white' => array(255, 255, 255),
        'yellow' => array(255, 255, 0),
        
        // invalid w3c
        'aliceblue'=> array(240,248,255),
        'antiquewhite'=> array(250,235,215),
        'aquamarine'=> array(127,255,212),
        'azure'=> array(240,255,255),
        'beige'=> array(245,245,220),
        'bisque'=> array(255,228,196),
        'blanchedalmond'=> array(255,235,205),
        'blueviolet'=> array(138,43,226),
        'brown'=> array(165,42,42),
        'burlywood'=> array(222,184,135),
        'cadetblue'=> array(95,158,160),
        'chartreuse'=> array(127,255,0),
        'chocolate'=> array(210,105,30),
        'coral'=> array(255,127,80),
        'cornflowerblue'=> array(100,149,237),
        'cornsilk'=> array(255,248,220),
        'crimson'=> array(220,20,60),
        'cyan'=> array(0,255,255),
        'darkblue'=> array(0,0,139),
        'darkcyan'=> array(0,139,139),
        'darkgoldenrod'=> array(184,134,11),
        'darkgray'=> array(169,169,169),
        'darkgreen'=> array(0,100,0),
        'darkkhaki'=> array(189,183,107),
        'darkmagenta'=> array(139,0,139),
        'darkolivegreen'=> array(85,107,47),
        'darkorange'=> array(255,140,0),
        'darkorchid'=> array(153,50,204),
        'darkred'=> array(139,0,0),
        'darksalmon'=> array(233,150,122),
        'darkseagreen'=> array(143,188,143),
        'darkslateblue'=> array(72,61,139),
        'darkslategray'=> array(47,79,79),
        'darkturquoise'=> array(0,206,209),
        'darkviolet'=> array(148,0,211),
        'deeppink'=> array(255,20,147),
        'deepskyblue'=> array(0,191,255),
        'dimgray'=> array(105,105,105),
        'dodgerblue'=> array(30,144,255),
        'firebrick'=> array(178,34,34),
        'floralwhite'=> array(255,250,240),
        'forestgreen'=> array(34,139,34),
        'gainsboro'=> array(220,220,220),
        'ghostwhite'=> array(248,248,255),
        'gold'=> array(255,215,0),
        'goldenrod'=> array(218,165,32),
        'greenyellow'=> array(173,255,47),
        'honeydew'=> array(240,255,240),
        'hotpink'=> array(255,105,180),
        'indianred '=> array(205,92,92),
        'indigo '=> array(75,0,130),
        'ivory'=> array(255,255,240),
        'khaki'=> array(240,230,140),
        'lavender'=> array(230,230,250),
        'lavenderblush'=> array(255,240,245),
        'lawngreen'=> array(124,252,0),
        'lemonchiffon'=> array(255,250,205),
        'lightblue'=> array(173,216,230),
        'lightcoral'=> array(240,128,128),
        'lightcyan'=> array(224,255,255),
        'lightgoldenrodyellow'=> array(250,250,210),
        'lightgrey'=> array(211,211,211),
        'lightgreen'=> array(144,238,144),
        'lightpink'=> array(255,182,193),
        'lightsalmon'=> array(255,160,122),
        'lightseagreen'=> array(32,178,170),
        'lightskyblue'=> array(135,206,250),
        'lightslategray'=> array(119,136,153),
        'lightsteelblue'=> array(176,196,222),
        'lightyellow'=> array(255,255,224),
        'limegreen'=> array(50,205,50),
        'linen'=> array(250,240,230),
        'magenta'=> array(255,0,255),
        'mediumaquamarine'=> array(102,205,170),
        'mediumblue'=> array(0,0,205),
        'mediumorchid'=> array(186,85,211),
        'mediumpurple'=> array(147,112,216),
        'mediumseagreen'=> array(60,179,113),
        'mediumslateblue'=> array(123,104,238),
        'mediumspringgreen'=> array(0,250,154),
        'mediumturquoise'=> array(72,209,204),
        'mediumvioletred'=> array(199,21,133),
        'midnightblue'=> array(25,25,112),
        'mintcream'=> array(245,255,250),
        'mistyrose'=> array(255,228,225),
        'moccasin'=> array(255,228,181),
        'navajowhite'=> array(255,222,173),
        'oldlace'=> array(253,245,230),
        'olivedrab'=> array(107,142,35),
        'orange'=> array(255,165,0),
        'orangered'=> array(255,69,0),
        'orchid'=> array(218,112,214),
        'palegoldenrod'=> array(238,232,170),
        'palegreen'=> array(152,251,152),
        'paleturquoise'=> array(175,238,238),
        'palevioletred'=> array(216,112,147),
        'papayawhip'=> array(255,239,213),
        'peachpuff'=> array(255,218,185),
        'peru'=> array(205,133,63),
        'pink'=> array(255,192,203),
        'plum'=> array(221,160,221),
        'powderblue'=> array(176,224,230),
        'rosybrown'=> array(188,143,143),
        'royalblue'=> array(65,105,225),
        'saddlebrown'=> array(139,69,19),
        'salmon'=> array(250,128,114),
        'sandybrown'=> array(244,164,96),
        'seagreen'=> array(46,139,87),
        'seashell'=> array(255,245,238),
        'sienna'=> array(160,82,45),
        'skyblue'=> array(135,206,235),
        'slateblue'=> array(106,90,205),
        'slategray'=> array(112,128,144),
        'snow'=> array(255,250,250),
        'springgreen'=> array(0,255,127),
        'steelblue'=> array(70,130,180),
        'tan'=> array(210,180,140),
        'thistle'=> array(216,191,216),
        'tomato'=> array(255,99,71),
        'turquoise'=> array(64,224,208),
        'violet'=> array(238,130,238),
        'wheat'=> array(245,222,179),
        'whitesmoke'=> array(245,245,245),
        'yellowgreen'=> array(154,205,50),
        'shit' => array(90,55,16),
    );
    
    function __construct($conf = array()) {
        self::$conf = array_merge(self::$conf, (array)$conf); // merge options

        ######## NO FURTHER EDITING, UNLESS YOU REALLY KNOW WHAT YOU'RE DOING ##########
        
        self::$conf['cachedir'] = rtrim(self::$conf['cachedir'], '/');
        
        if (!is_writable(self::$conf['cachedir'])) {
        	die("The directory '".self::$conf['cachedir']."' either doesn't exist or isn't writable.");
        }
        
        // Are the RGB values provided directly or implied through a named color?
        if (isset(self::$conf['color_names'][$_REQUEST['name']])) {
        	list($red, $green, $blue) = self::$conf['color_names'][$_REQUEST['name']];
        	$alpha = $_REQUEST['a'] / 100;
        }
        else {
        	if(isset($_REQUEST['r']) and isset($_REQUEST['g']) and isset($_REQUEST['b'])) {
        		// Old way: rgba.php?r=R&g=G&b=B&a=100*A
        		$color_info = $_REQUEST;
        		$alpha = $_REQUEST['a'] / 100;
        	}
        	else {
        		// New way: rgba.php/rgba(R,G,B,A)
        		$color_info = explode(',', str_replace(' ', '', substr($_SERVER['PATH_INFO'], 6, -1)));
        		$color_info = array_combine(array('r','g','b','a'), $color_info);
        		$alpha	= floatval($color_info['a']);
        	}
        	
        	$red	= intval($color_info['r']);
        	$green	= intval($color_info['g']);
        	$blue	= intval($color_info['b']);
        }
        
        // "A value between 0 and 127. 0 indicates completely opaque while 127 indicates completely transparent."
        // http://www.php.net/manual/en/function.imagecolorallocatealpha.php
        $alpha = intval(127 - 127 * $alpha);
        
        // Send headers
        header('Content-type: image/png');
        header('Expires: 01 Jan 2026 00:00:00 GMT');
        header('Cache-control: max-age=2903040000');
        
        // Does it already exist?
        $filepath = self::$conf['cachedir'] . "/color_r{$red}_g{$green}_b{$blue}_a$alpha.png";
        
        if(self::$conf['cachepngs'] and file_exists($filepath)) {
        
        	// The file exists, is it cached by the browser?
        	if (function_exists('apache_request_headers')) {
        		$headers = apache_request_headers();
        
        		// We don't need to check if it was actually modified since then as it never changes.
        		$responsecode = isset($headers['If-Modified-Since'])? 304 : 200;
        	}
        	else {
        		$responsecode = 200;
        	}
        
        	header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($filepath)) . ' GMT', true, $responsecode);
        
        	if ($responsecode == 200) {
        		header('Content-Length: '.filesize($filepath));
        		die(file_get_contents($filepath));
        	}
        }
        else {
        	$img = @imagecreatetruecolor(self::$conf['size'], self::$conf['size'])
        		  or die('Cannot Initialize new GD image stream');
        
        	// This is to allow the final image to have actual transparency
        	// http://www.php.net/manual/en/function.imagesavealpha.php
        	imagealphablending($img, false);
        	imagesavealpha($img, true);
        
        	// Allocate our requested color
        	$color = imagecolorallocatealpha($img, $red, $green, $blue, $alpha);
        
        	// Fill the image with it
        	imagefill($img, 0, 0, $color);
        
        	// Save the file (if caching is allowed)
        	if (self::$conf['cachepngs']) {
        		// Check PHP version to solve a bug that caused the script to fail on PHP versions < 5.1.7
        		if (strnatcmp(phpversion(), '5.1.7') >= 0) {
        			imagepng($img, $filepath, 0, NULL);
        		}
        		else {
        			imagepng($img, $filepath);
        		}
        	}
        
        	// Serve the file
        	imagepng($img);
        
        	// Free up memory
        	imagedestroy($img);
        }
    }
}

// init class
$conf = (empty($conf)) ? array() : $conf;
new Rgba($conf);