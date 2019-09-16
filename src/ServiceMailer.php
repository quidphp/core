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

// serviceMailer
// extended abstract class with basic methods that needs to be extended by a mailing service
abstract class ServiceMailer extends Main\ServiceMailer
{
    // trait
    use _fullAccess;


    // config
    public static $config = [
        'queue'=>Row\QueueEmail::class,
        'log'=>Row\LogEmail::class
    ];


    // getLangCode
    // retourne le code courant de la langue
    public static function getLangCode():string
    {
        return static::lang()->currentLang();
    }


    // getOverloadKeyPrepend
    // retourne le prepend de la clé à utiliser pour le tableau overload
    public static function getOverloadKeyPrepend():?string
    {
        return (static::class !== self::class && !Base\Fqcn::sameName(static::class,self::class))? 'Service':null;
    }
}

// config
ServiceMailer::__config();
?>