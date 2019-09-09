<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Role;
use Quid\Core;

// nobody
// class that issues default configuration for the nobody role
class Nobody extends Core\RoleAlias
{
    // config
    public static $config = [
        'permission'=>1
    ];
}

// config
Nobody::__config();
?>