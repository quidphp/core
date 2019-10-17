<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Role;
use Quid\Core;

// admin
// class which contains the default configuration for the admin role
class Admin extends Core\RoleAlias
{
    // config
    public static $config = [
        'permission'=>80
    ];


    // isAdmin
    // retourne vrai comme c'est admin
    public static function isAdmin():bool
    {
        return true;
    }
}

// init
Admin::__init();
?>