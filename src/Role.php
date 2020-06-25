<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Base;
use Quid\Main;

// role
// extended abstract class that provides more advanced logic for a role
class Role extends Main\Role
{
    // trait
    use _fullAccess;


    // config
    protected static array $config = [
        'label'=>null, // label du rôle
        'description'=>null, // description du rôle
        'admin'=>false, // si le rôle est admin
        'cli'=>false // si le rôle est cli
    ];


    // isAdmin
    // retourne vrai si le role est admin
    final public function isAdmin():bool
    {
        return $this->is('admin');
    }


    // isCli
    // retourne vrai si le role est cli
    final public function isCli():bool
    {
        return $this->is('cli');
    }


    // validate
    // permet de faire une validation sur l'objet role
    final public function validate($value):bool
    {
        $return = false;
        $permission = $this->permission();
        $name = $this->name();

        if(is_scalar($value))
        $value = [$value];

        if(is_array($value))
        {
            if(Base\Arr::isIndexed($value))
            {
                if(in_array($permission,$value,true) || in_array($name,$value,true))
                $return = true;
            }

            else
            {
                $replace = $this->validateReplace();

                if(!empty($replace))
                {
                    $array = [];

                    foreach (Base\Arr::valuesReplace($replace,$value) as $k => $v)
                    {
                        if(is_numeric($v))
                        $array[$k] = (int) $v;
                    }

                    if(!empty($array) && Base\Validate::isAnd($array,$permission))
                    $return = true;
                }
            }
        }

        return $return;
    }


    // validateReplace
    // retourne un tableau de remplacement en utilisant roles dans boot
    // méthode protégé, utilisé par validate
    final protected function validateReplace():?array
    {
        return $this->cache(__METHOD__,function() {
            $return = null;

            foreach (static::boot()->roles() as $permission => $role)
            {
                $name = $role->name();
                $return[$name] = $permission;
            }

            return $return;
        });
    }


    // label
    // retourne le label du rôle
    // envoie une exception si lang/inst n'existe pas
    final public function label($pattern=null,?string $lang=null,?array $option=null):?string
    {
        $return = null;
        $obj = static::lang();
        $path = $this->getAttr('label');
        $option = Base\Arr::plus($option,['pattern'=>$pattern]);

        if(!empty($path))
        $return = $obj->same($path,null,$lang,$option);
        else
        $return = $obj->roleLabel(static::permission(),$lang,$option);

        return $return;
    }


    // labelPermission
    // retourne le label du rôle avec la permission entre paranthèse
    final public function labelPermission($pattern=null,?string $lang=null,?array $option=null):?string
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
    final public function description($pattern=null,?array $replace=null,?string $lang=null,?array $option=null):?string
    {
        $return = null;
        $obj = static::lang();
        $path = $this->getAttr('description');
        $option = Base\Arr::plus($option,['pattern'=>$pattern]);

        if(!empty($path))
        $return = $obj->same($path,$replace,$lang,$option);
        else
        $return = $obj->roleDescription(static::permission(),$replace,$lang,$option);

        return $return;
    }
}

// init
Role::__init();
?>