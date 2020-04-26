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
use Quid\Orm;

// active
// class for the active column - extends the Yes column class
class Active extends YesAlias
{
    // config
    public static array $config = [];


    // onDuplicate
    // callback sur duplication, retourne null
    final protected function onDuplicate($return,array $row,Orm\Cell $cell,array $option)
    {
        return;
    }
}

// init
Active::__init();
?>