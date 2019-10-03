<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Base;
use Quid\Main;

// file
// extended class for a basic file object
class File extends Main\File
{
    // trait
    use _fullAccess;


    // config
    public static $config = [];


    // param
    public static $param = [
        'storageClass'=>[ // défini les classes storages, un dirname dans celui défini de la classe doit utilisé un objet particulier
            'cache'=>File\Cache::class,
            'error'=>File\Error::class,
            'log'=>File\Log::class,
            'queue'=>File\Queue::class,
            'session'=>File\Session::class],
        'utilClass'=>[ // défini les classes utilités
            'dump'=>File\Dump::class,
            'serialize'=>File\Serialize::class,
            'email'=>File\Email::class],
        'groupClass'=>[ // défini la classe à utiliser selon le mimeGroup du fichier
            'audio'=>File\Audio::class,
            'calendar'=>File\Calendar::class,
            'css'=>File\Css::class,
            'csv'=>File\Csv::class,
            'doc'=>File\Doc::class,
            'font'=>File\Font::class,
            'html'=>File\Html::class,
            'imageRaster'=>File\ImageRaster::class,
            'imageVector'=>File\ImageVector::class,
            'js'=>File\Js::class,
            'json'=>File\Json::class,
            'pdf'=>File\Pdf::class,
            'php'=>File\Php::class,
            'txt'=>File\Txt::class,
            'video'=>File\Video::class,
            'xml'=>File\Xml::class,
            'zip'=>File\Zip::class]
    ];


    // getOverloadKeyPrepend
    // retourne le prepend de la clé à utiliser pour le tableau overload
    public static function getOverloadKeyPrepend():?string
    {
        return (static::class !== self::class && !Base\Fqcn::sameName(static::class,self::class))? 'File':null;
    }
}

// init
File::__init();
?>