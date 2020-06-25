<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Orm;

// active
// class for the active column - extends the Yes column class
class Active extends YesAlias
{
    // config
    protected static array $config = [];


    // onDuplicate
    // callback sur duplication, retourne null
    final protected function onDuplicate(Orm\Cell $cell,array $option)
    {
        return;
    }
}

// init
Active::__init();
?>