<?php
class bgGrid
{
    public static $cache_target;
    
    public static function find_cache_folder()
    {
        $uri       = explode('/mixins/', str_replace('\\', '/', dirname(__FILE__)));
        $cache_dir = $uri[0] . '/cache/grid/';

        if (!file_exists($cache_dir)) {
            mkdir($cache_dir, 0777);
        }
        return $cache_dir;
    }
    
    /**
     * Generates the debug grid background image
     *
     * @author Olivier Gorzalka
     * @param $cc Column count
     * @param $cw Column width
     * @param $gw Gutter Width
     * @return null
     */
    private static function create_grid_image($cc = 1, $cw = 0, $gw = 0)
    {
        $cc = (int) $cc;
        $cw = (int) $cw;
        $bl = 1;
        $gw = (int) $gw;
        
        self::$cache_target = self::find_cache_folder() . "{$cc}col_{$cw}px_{$gw}px_grid.png";
        
        if (!file_exists(self::$cache_target)) {
            $image = ImageCreate(($cw + 2 * $gw) * $cc, $bl);
            
            $colorGutter = ImageColorAllocate($image, 245, 245, 245);
            $colorColumn = ImageColorAllocate($image, 235, 235, 235);

            $posx = 0;
            
            for ($i = 0; $i <= $cc; $i++) {
                $x1 = $posx;
                $x2 = $posx + $gw - 1;
                Imagefilledrectangle($image, $x1, 0, $x2, $bl, $colorGutter);
                $posx += $gw;
                
                # Draw column
                $x1 = $posx;
                $x2 = $posx + $cw - 1;
                Imagefilledrectangle($image, $x1, 0, $x2, $bl, $colorColumn);
                $posx += $cw;
                
                # Draw right gutter
                $x1 = $posx;
                $x2 = $posx + $gw - 1;
                Imagefilledrectangle($image, $x1, 0, $x2, $bl, $colorGutter);          
                $posx += $gw;
            }
            
            ImagePNG($image, self::$cache_target);
            # Kill it
            ImageDestroy($image);
        }
    }
    
    function __construct($params)
    {
        $default_params = array('cc' => 0, 'cw' => 1, 'gw' => 1);
        $params = array_merge($default_params, $params);
        
        self::create_grid_image($params['cc'], $params['cw'], $params['gw']);
        header('Content-Type: image/png');
        echo file_get_contents(self::$cache_target);
    }
}

$img = new bgGrid($_GET);