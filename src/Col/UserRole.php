<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Base;
use Quid\Core;
use Quid\Main;
use Quid\Orm;

// userRole
// class for the column which manages the role field for the user row
class UserRole extends SetAlias
{
    // config
    protected static array $config = [
        'cell'=>Core\Cell\UserRole::class,
        'required'=>true,
        'relation'=>[self::class,'getRoles'],
        'check'=>['kind'=>'text']
    ];


    // onSet
    // sur changement de role
    // un utilisateur ne peut pas affecter une permission plus grande que la sienne
    // un utilisateur ne peut pas changer sa propre permission
    // si c'est un insert et que la valeur est default, accepte dans tous les cas
    // des exceptions attrapables peuvent être envoyés
    final protected function onSet($values,?Orm\Cell $cell=null,array $row,array $option)
    {
        $return = null;
        $values = parent::onSet($values,$cell,$row,$option);

        if($values instanceof Main\Roles)
        $values = array_values($values->pair('permission'));

        if($values instanceof Main\Role)
        $values = $values->permission();

        if(is_scalar($values))
        $values = Base\Set::arr($values);

        if(is_array($values))
        {
            $values = Base\Arr::cast($values);
            $values = Base\Arr::clean($values);
            asort($values);

            if(!empty($values))
            {
                $table = $this->table();
                $primary = $table->primary();
                $isInsert = (empty($cell));
                $id = $row[$primary] ?? null;

                $boot = static::boot();
                $session = $boot->session();
                $sessionUser = $session->user();
                $sessionRoles = $session->roles();
                $sessionRole = $session->role();
                $permission = $sessionRole->permission();
                $isNobody = (!empty($sessionRoles->nobody()));
                $isAdmin = $sessionRole->isAdmin();
                $isInsertNobody = ($isInsert === true && $isNobody === true);

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