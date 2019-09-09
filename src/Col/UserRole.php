<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;
use Quid\Orm;
use Quid\Base;

// userRole
// class for the column which manages the role field for the user row
class UserRole extends EnumAlias
{
    // config
    public static $config = [
        'required'=>true,
        'relation'=>[self::class,'getRoles'],
        'check'=>['kind'=>'int']
    ];


    // onCellSet
    // utilisé pour les changements de role
    // si la valeur de la cellule n'est pas un int, prend la permission du premier role dans roles soit nobody
    public function onCellSet(Orm\Cell $cell)
    {
        $role = null;
        $roles = static::boot()->roles();
        $permission = $cell->value();

        if(is_int($permission))
        $role = $roles->getObject($permission);

        if(empty($role))
        $role = $roles->nobody();

        if($role instanceof Core\Role)
        $cell->row()->setRole($role);

        else
        static::throw('invalidRoleObject');

        return $this;
    }


    // formComplex
    // génère le formComplex pour userRole
    // retourne un input plain si c'est l'utilisateur courant
    public function formComplex($value=true,?array $attr=null,?array $option=null):string
    {
        $return = null;
        $session = $current = static::boot()->session();
        $user = $session->user();

        if($value instanceof Core\Cell && $value->row()->primary() === $user->primary())
        $attr = Base\Arr::plus($attr,['tag'=>'div']);

        $return = parent::formComplex($value,$attr,$option);

        return $return;
    }


    // onSet
    // sur changement de role
    // un utilisateur ne peut pas affecter une permission plus grande que la sienne
    // un utilisateur ne peut pas changer sa propre permission
    // si c'est un insert et que la valeur est default, accepte dans tous les cas
    // des exceptions attrapables peuvent être envoyés
    public function onSet($value,array $row,?Orm\Cell $cell=null,array $option)
    {
        $return = null;
        $table = $this->table();
        $primary = $table->primary();
        $value = $this->value($value);
        $boot = static::boot();
        $session = $boot->session();
        $roles = $boot->roles();
        $nobody = $roles->nobody();
        $user = $session->user();
        $role = $session->role();
        $permission = $role::permission();
        $isAdmin = $role::isAdmin();
        $isInsert = (empty($cell))? true:false;
        $isDefault = ($value === $this->default())? true:false;
        $id = $row[$primary] ?? null;

        if(is_int($value))
        {
            if($isDefault === true && $isInsert === true)
            $return = $value;

            elseif($value === $nobody::permission())
            static::catchable(null,'userRoleNobody');

            elseif($value >= $permission && $isAdmin === false)
            static::catchable(null,'userRoleUpperEqual');

            elseif($value > $permission && $isAdmin === true)
            static::catchable(null,'userRoleUpper');

            elseif($id === $user->primary() && $permission !== $value)
            static::catchable(null,'userRoleSelf');

            else
            $return = $value;
        }

        return $return;
    }


    // getRoles
    // retourne les roles actifs
    public static function getRoles():array
    {
        return static::boot()->roles()->pair('label');
    }
}

// config
UserRole::__config();
?>