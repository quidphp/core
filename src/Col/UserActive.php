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
use Quid\Orm;

// userActive
// class for the column which manages the active field for the user row
class UserActive extends YesAlias
{
    // config
    public static array $config = [];


    // onSet
    // sur changement de active
    // une exception attrapable peut être envoyé
    final protected function onSet($value,array $row,?Orm\Cell $cell=null,array $option)
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
        {
            $return = (empty($value))? null:$value;
            $hasChanged = (!empty($cell))? ($cell->value() !== $return):true;

            if($hasChanged === true)
            {
                $closure = fn(Orm\Cell $cell) => $cell->row()->callThis(fn() => $this->onChangeActive($option));
                $this->setCommittedCallback('onCommitted',$closure,$cell);
            }
        }

        return $return;
    }
}

// init
UserActive::__init();
?>