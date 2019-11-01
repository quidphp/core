<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;
use Quid\Core;
use Quid\Main;

// imageRaster
// class for a pixelated image file
class ImageRaster extends Main\File\ImageRaster
{
    // config
    public static $config = [
        'service'=>Core\Service\ClassUpload::class
    ];


    // getServiceClass
    // retourne la classe du service
    public function getServiceClass():string
    {
        return $this->getAttr('service')::getOverloadClass();
    }


    // compress
    // comprime le fichier image avec le service spécifié dans config
    public function compress(string $dirname,?string $filename=null,?array $option=null)
    {
        $return = null;
        $class = $this->getServiceClass();
        $upload = new $class(__METHOD__,$option);
        $return = $upload->trigger($this,$dirname,$filename);

        return $return;
    }
}

// init
ImageRaster::__init();
?>