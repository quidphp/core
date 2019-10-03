<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;

// enum
// class for a column containing an enum relation (one)
class Enum extends RelationAlias
{
    // config
    public static $config = [
        'cell'=>Core\Cell\Enum::class,
        'enum'=>true,
        'required'=>true,
        'order'=>true,
        'complex'=>[0=>'select',11=>'search']
    ];


    // isEnum
    // retourne vrai comme la colonne est de type relation enum
    public function isEnum():bool
    {
        return true;
    }
}

// init
Enum::__init();
?>