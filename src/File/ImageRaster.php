<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\File;
use Quid\Core;
use Quid\Main;

// imageRaster
// class for a pixelated image file
class ImageRaster extends Main\File\ImageRaster
{
    // config
    protected static array $config = [
        'service'=>Core\Service\ClassUpload::class
    ];


    // getServiceClass
    // retourne la classe du service
    final public function getServiceClass():string
    {
        return $this->getAttr('service')::classOverload();
    }


    // compress
    // comprime le fichier image avec le service spécifié dans config
    final public function compress(string $dirname,?string $filename=null,?array $option=null)
    {
        $return = null;
        $class = $this->getServiceClass();
        $upload = new $class($option);
        $return = $upload->trigger($this,$dirname,$filename);

        return $return;
    }
}

// init
ImageRaster::__init();
?>