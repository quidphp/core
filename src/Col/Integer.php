<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;

// integer
// class for a column which deals with integer values
class Integer extends Core\ColAlias
{
    // config
    public static $config = [
        'cell'=>Core\Cell\Integer::class
    ];
}

// init
Integer::__init();
?>