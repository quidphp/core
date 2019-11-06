<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Base;
use Quid\Core;
use Quid\Orm;

// userActive
// class for the column which manages the active field for the user row
class UserActive extends YesAlias
{
    // config
    public static $config = [];


    // onSet
    // sur changement de active
    // une exception attrapable peut être envoyé
    public function onSet($value,array $row,?Orm\Cell $cell=null,array $option)
    {
        $return = null;
        $table = $this->table();
        $primary = $table->primary();
        $value = $this->value($value);
        $session = static::boot()->session();
        $user = $session->user();
        $id = $row[$primary] ?? null;
        $return = $user['active']->value();

        if(is_array($value) && !empty($value))
        $value = current($value);

        if($id === $user->primary() && $value !== $return)
        static::catchable(null,'userActiveSelf');

        else
        $return = $value;

        return $return;
    }
}

// init
UserActive::__init();
?>