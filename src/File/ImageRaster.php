<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;
use Quid\Core;

// imageRaster
// class for a pixelated image file
class ImageRaster extends ImageAlias
{
    // config
    public static $config = [
        'group'=>'imageRaster',
        'service'=>Core\Service\ClassUpload::class
    ];


    // getServiceClass
    // retourne la classe du service
    public static function getServiceClass():string
    {
        return static::$config['service']::getOverloadClass();
    }


    // compress
    // comprime le fichier image avec le service spécifié dans config
    public function compress(string $dirname,?string $filename=null,?array $option=null)
    {
        $return = null;
        $class = static::getServiceClass();
        $upload = new $class(__METHOD__,$option);
        $return = $upload->trigger($this,$dirname,$filename);

        return $return;
    }
}

// config
ImageRaster::__config();
?>