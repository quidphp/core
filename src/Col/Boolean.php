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

// boolean
// class for the boolean column - a simple yes/no enum relation
class Boolean extends EnumAlias
{
    // config
    public static array $config = [
        'complex'=>'radio',
        'required'=>true,
        'relation'=>'bool',
        'onSet'=>[Base\Set::class,'onSet'],
        'check'=>['kind'=>'int']
    ];
}

// init
Boolean::__init();
?>