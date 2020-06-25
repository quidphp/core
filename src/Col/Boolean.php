<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;

// boolean
// class for the boolean column - a simple yes/no enum relation
class Boolean extends EnumAlias
{
    // config
    protected static array $config = [
        'complex'=>'radio',
        'required'=>true,
        'relation'=>'bool',
        'check'=>['kind'=>'int']
    ];
}

// init
Boolean::__init();
?>