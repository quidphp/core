<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Role;
use Quid\Core;

// cron
// class which contains the default configuration for the cron role
class Cron extends Core\RoleAlias
{
    // config
    public static $config = [
        'permission'=>90,
        'db'=>[
            '*'=>[
                'insert'=>true,
                'update'=>true,
                'delete'=>true,
                'create'=>true,
                'alter'=>true,
                'truncate'=>true,
                'drop'=>true]]
    ];


    // isCron
    // retourne vrai comme c'est cron
    public static function isCron():bool
    {
        return true;
    }
}

// init
Cron::__init();
?>