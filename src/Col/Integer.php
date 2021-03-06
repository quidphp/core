<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;

// integer
// class for a column which deals with integer values
class Integer extends Core\ColAlias
{
    // config
    protected static array $config = [
        'cell'=>Core\Cell\Integer::class,
        'keyboard'=>'decimal',
        'check'=>['kind'=>'int']
    ];
}

// init
Integer::__init();
?>