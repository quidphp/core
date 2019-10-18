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

// role
// extended abstract class that provides more advanced logic for a role
abstract class Role extends Main\Role
{
    // trait
    use _fullAccess;


    // config
    public static $config = [
        'label'=>null, // label du rôle
        'description'=>null // description du rôle
    ];


    // isShared
    // retourne vrai si la permission est shared
    public static function isShared():bool
    {
        return false;
    }


    // isAdmin
    // retourne vrai si la permission est admin
    public static function isAdmin():bool
    {
        return false;
    }


    // isCli
    // retourne vrai si la permission est cli
    public static function isCli():bool
    {
        return false;
    }


    // validateReplace
    // retourne un tableau de remplacement en utilisant roles
    // méthode protégé, utilisé par validate
    protected static function validateReplace():?array
    {
        return static::cacheStatic(__METHOD__,function() {
            $return = null;
            $roles = static::boot()->roles();

            if(!empty($roles))
            {
                foreach ($roles as $permission => $role)
                {
                    $name = $role::name();
                    $return[$name] = $permission;
                }
            }

            return $return;
        });
    }


    // label
    // retourne le label du rôle
    // envoie une exception si lang/inst n'existe pas
    public static function label($pattern=null,?string $lang=null,?array $option=null):?string
    {
        $return = null;
        $obj = static::lang();
        $path = (!empty(static::$config['label']))? static::$config['label']:null;
        $option = Base\Arr::plus($option,['pattern'=>$pattern]);

        if(!empty($path))
        $return = $obj->same($path,null,$lang,$option);
        else
        $return = $obj->roleLabel(static::permission(),$lang,$option);

        return $return;
    }


    // labelPermission
    // retourne le label du rôle avec la permission entre paranthèse
    public static function labelPermission($pattern=null,?string $lang=null,?array $option=null):?string
    {
        $return = static::label($pattern,$lang,$option);

        if(is_string($return))
        {
            $permission = static::permission();
            $return .= " ($permission)";
        }

        return $return;
    }


    // description
    // retourne la description du rôle
    // envoie une exception si lang/inst n'existe pas
    public static function description($pattern=null,?array $replace=null,?string $lang=null,?array $option=null):?string
    {
        $return = null;
        $obj = static::lang();
        $path = (!empty(static::$config['description']))? static::$config['description']:null;
        $option = Base\Arr::plus($option,['pattern'=>$pattern]);

        if(!empty($path))
        $return = $obj->same($path,$replace,$lang,$option);
        else
        $return = $obj->roleDescription(static::permission(),$replace,$lang,$option);

        return $return;
    }


    // getOverloadKeyPrepend
    // retourne le prepend de la clé à utiliser pour le tableau overload
    public static function getOverloadKeyPrepend():?string
    {
        return (static::class !== self::class && !Base\Fqcn::sameName(static::class,self::class))? 'Role':null;
    }
}

// init
Role::__init();
?>