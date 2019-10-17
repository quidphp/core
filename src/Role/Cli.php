<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Role;
use Quid\Core;

// cli
// class which contains the default configuration for the cli role
class Cli extends Core\RoleAlias
{
    // config
    public static $config = [
        'permission'=>90
    ];


    // isCli
    // retourne vrai comme c'est cli
    public static function isCli():bool
    {
        return true;
    }
}

// init
Cli::__init();
?>