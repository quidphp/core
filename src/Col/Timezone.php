<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\Col;
use Quid\Base;

// timezone
// class for a column which is an enum relation to the PHP timezone list
class Timezone extends EnumAlias
{
    // config
    public static array $config = [
        'required'=>false,
        'relation'=>[self::class,'getTimezones'],
        'check'=>['kind'=>'int']
    ];


    // description
    // retourne la description de la colonne, remplace le segment timezone si existant par la timezone courante
    final public function description($pattern=null,?array $replace=null,?string $lang=null,?array $option=null):?string
    {
        return parent::description($pattern,Base\Arr::replace($replace,['timezone'=>Base\Timezone::get()]),$lang,$option);
    }


    // getTimezones
    // retourne un tableau avec les timezones
    final public static function getTimezones():array
    {
        return Base\Timezone::all();
    }
}

// init
Timezone::__init();
?>