<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cell;

// integer
// class to manage a cell containing an integer value
class Integer extends NumAlias
{
    // config
    protected static array $config = [];
}

// init
Integer::__init();
?>