<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
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
        $userRoles = $roles->filterKeep(...$permissions);

        if(empty($userRoles) || $userRoles->isEmpty())
        $userRoles = $roles->nobody()->roles();

        if(!$userRoles instanceof Main\Roles)
        static::throw('invalidRolesObject');

        $this->row()->setRoles($userRoles);
    }
}

// init
UserRole::__init();
?>