<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;

// primary
// class for dealing with a column which has an auto increment primary key
class Primary extends Core\ColAlias
{
    // config
    public static $config = [
        'cell'=>Core\Cell\Primary::class,
        'general'=>true,
        'order'=>true,
        'complex'=>'inputHidden',
        'check'=>['kind'=>'int']
    ];
}

// init
Primary::__init();
?>