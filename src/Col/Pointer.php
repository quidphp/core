<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;
use Quid\Orm;

// pointer
// class for a column which the value is a pointer to another row in the database
class Pointer extends Core\ColAlias
{
    // config
    protected static array $config = [
        'required'=>true,
        'validate'=>['pointer'=>[self::class,'custom']]
    ];


    // onGet
    // méthode appelé sur get, retourne la row ou null
    final protected function onGet($return,?Orm\Cell $cell,array $option)
    {
        return (is_string($return))? static::getRow($return):$return;
    }


    // getRow
    // retourne la row ou null
    final public static function getRow(?string $value):?Core\Row
    {
        return (is_string($value) && strlen($value))? static::boot()->db()->fromPointer($value):null;
    }


    // custom
    // méthode de validation custom pour le champ pointeur
    final public static function custom($value):?bool
    {
        $return = null;
        $row = static::getRow($value);

        if(!empty($row))
        $return = true;

        return $return;
    }
}

// init
Pointer::__init();
?>