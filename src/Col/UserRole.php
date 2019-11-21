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
use Quid\Base;
use Quid\Main;
use Quid\Orm;

// userRole
// class for the column which manages the role field for the user row
class UserRole extends SetAlias
{
    // config
    public static $config = [
        'required'=>true,
        'relation'=>[self::class,'getRoles'],
        'check'=>['kind'=>'text']
    ];


    // onCellSet
    // utilisé pour les changements de role
    // si la valeur de la cellule n'est pas un int, prend la permission du premier role dans roles soit nobody
    final protected function onCellSet(Orm\Cell $cell)
    {
        $role = null;
        $roles = static::boot()->roles();
        $permissions = $cell->get();

        if(!empty($permissions))
        $userRoles = $roles->only(...$permissions);

        if(empty($userRoles) || $userRoles->isEmpty())
        $userRoles = $roles->nobody()->roles();

        if($userRoles instanceof Main\Roles)
        $cell->row()->setRoles($userRoles);

        else
        static::throw('invalidRolesObject');

        return $this;
    }


    // onSet
    // sur changement de role
    // un utilisateur ne peut pas affecter une permission plus grande que la sienne
    // un utilisateur ne peut pas changer sa propre permission
    // si c'est un insert et que la valeur est default, accepte dans tous les cas
    // des exceptions attrapables peuvent être envoyés
    final protected function onSet($values,array $row,?Orm\Cell $cell=null,array $option)
    {
        $return = null;
        $values = $this->value($values);

        if(is_scalar($values))
        $values = [$values];

        if(is_array($values))
        {
            $values = Base\Arr::cast($values);
            $values = Base\Arr::clean($values);
            asort($values);

            if(!empty($values))
            {
                $table = $this->table();
                $primary = $table->primary();
                $isInsert = (empty($cell))? true:false;
                $id = $row[$primary] ?? null;

                $boot = static::boot();
                $session = $boot->session();
                $sessionUser = $session->user();
                $sessionRoles = $session->roles();
                $sessionRole = $session->role();
                $permission = $sessionRole->permission();
                $isNobody = (!empty($sessionRoles->nobody()))? true:false;
                $isAdmin = $sessionRole->isAdmin();
                $isInsertNobody = ($isInsert === true && $isNobody === true)? true:false;

                $roles = $boot->roles();
                $rolesNobody = $roles->nobody();

                if(!$roles->exists(...$values))
                static::throw(null,'rolesNotFound');

                if($id === $sessionUser->primary() && $sessionRoles->keys() !== $values)
                static::catchable(null,'userRoleSelf');

                foreach ($values as $value)
                {
                    if($value === $rolesNobody->permission() && $isAdmin === false)
                    static::catchable(null,'userRoleNobody');

                    elseif($value >= $permission && $isAdmin === false && $isInsertNobody === false)
                    static::catchable(null,'userRoleUpperEqual');

                    elseif($value > $permission && $isAdmin === true)
                    static::catchable(null,'userRoleUpper');

                    $return[] = $value;
                }
            }
        }

        return $return;
    }


    // getRoles
    // retourne les roles actifs
    final public static function getRoles():array
    {
        return static::boot()->roles()->pair('labelPermission');
    }
}

// init
UserRole::__init();
?>