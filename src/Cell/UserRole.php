<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\Cell;
use Quid\Main;

// userRole
// class to work with the user role cell within a user table
class UserRole extends SetAlias
{
    // onInit
    // utilisé pour les changements de role
    // si la valeur de la cellule n'est pas un int, prend la permission du premier role dans roles soit nobody
    final protected function onInit(bool $initial):void
    {
        parent::onInit($initial);
        $role = null;
        $roles = static::boot()->roles();
        $permissions = $this->get();

        if(!empty($permissions))
        $userRoles = $roles->only(...$permissions);

        if(empty($userRoles) || $userRoles->isEmpty())
        $userRoles = $roles->nobody()->roles();

        if($userRoles instanceof Main\Roles)
        $this->row()->setRoles($userRoles);

        else
        static::throw('invalidRolesObject');

        return;
    }
}

// init
UserRole::__init();
?>