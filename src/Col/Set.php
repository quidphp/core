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
use Quid\Orm;

// set
// class for a column containing a set relation (many)
class Set extends RelationAlias
{
    // config
    protected static array $config = [
        'cell'=>Core\Cell\Set::class,
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


    // onGet
    // sur get de la valeur de relation
    protected function onGet($return,?Orm\Cell $cell=null,array $option)
    {
        $return = parent::onGet($return,$cell,$option);

        if(is_scalar($return))
        $return = Base\Set::arr($return,['cast'=>true]);

        return $return;
    }
}

// init
Set::__init();
?>