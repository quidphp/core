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
use Quid\Core;

// set
// class for a column containing a set relation (many)
class Set extends RelationAlias
{
    // config
    protected static array $config = [
        'cell'=>Core\Cell\Set::class,
        'onGet'=>[Base\Set::class,'onGet'],
        'preValidate'=>'array',
        'filterMethod'=>'or|findInSet',
        'set'=>true,
        'complex'=>[0=>'checkbox',11=>'search'],
        'check'=>['kind'=>'text']
    ];


    // isSet
    // retourne vrai comme la colonne est de type relation set
    final public function isSet():bool
    {
        return true;
    }
}

// init
Set::__init();
?>