<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;

// envType
// class for the envType column, updates itself automatically on commit
class EnvType extends JsonAlias
{
    // config
    protected static array $config = [
        'required'=>true,
        'general'=>true,
        'visible'=>['validate'=>'notEmpty'],
        'complex'=>'div',
        'onComplex'=>true,
        'check'=>['kind'=>'char']
    ];


    // onCommit
    // ajoute le envtype sur insertion ou mise à jour
    protected function onCommit($value,?Core\Cell $cell=null,array $row,array $option):?array
    {
        $boot = static::bootReady();
        return (!empty($boot))? $boot->envType():null;
    }
}

// init
EnvType::__init();
?>