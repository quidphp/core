<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Main;

// _access
// trait that provides methods to useful objects related to the Boot
trait _access
{
    // trait
    use _bootAccess;


    // session
    // retourne l'objet session
    public static function session():Main\Session
    {
        return static::boot()->session();
    }


    // sessionCom
    // retourne l'objet com de session
    public static function sessionCom():Com
    {
        return static::session()->com();
    }


    // sessionUser
    // retourne l'objet user de session
    public static function sessionUser():Row
    {
        return static::session()->user();
    }


    // lang
    // retourne l'objet lang
    public static function lang():Main\Lang
    {
        return static::boot()->lang();
    }


    // langText
    // retourne un élément de texte à partir de l'objet lang
    public static function langText($key,?array $replace=null,?string $lang=null,?array $option=null):?string
    {
        return static::lang()->text($key,$replace,$lang,$option);
    }


    // langPlural
    // retourne un élément de texte plural à partir de l'objet lang
    public static function langPlural($value,$key,?array $replace=null,?array $plural=null,?string $lang=null,?array $option=null):?string
    {
        return static::lang()->plural($value,$key,$replace,$plural,$lang,$option);
    }


    // services
    // retourne l'objet services
    public static function services():Main\Services
    {
        return static::boot()->services();
    }


    // service
    // retourne un objet service, envoie une exception si n'existe pas
    public static function service(string $key):Main\Service
    {
        return static::boot()->checkService($key);
    }


    // serviceMailer
    // retourne l'objet mailer à utiliser pour envoyer un courriel, ne peut pas retourner null
    public static function serviceMailer($key=null):Main\ServiceMailer
    {
        return static::boot()->checkServiceMailer($key);
    }
}
?>