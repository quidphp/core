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
use Quid\Core;

// floating
// class for a column which deals with floating values
class Floating extends Core\ColAlias
{
    // config
    public static $config = [
        'cell'=>Core\Cell\Floating::class
    ];
}

// init
Floating::__init();
?>