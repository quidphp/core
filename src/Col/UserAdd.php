<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;

// userAdd
// class for a column which stores the current user id on insert
class UserAdd extends EnumAlias
{
    // config
    protected static array $config = [
        'required'=>false,
        'general'=>true,
        'visible'=>['validate'=>'notEmpty'],
        'relation'=>'user',
        'duplicate'=>false,
        'editable'=>false,
        'complex'=>'div',
        'check'=>['kind'=>'int']
    ];


    // onInsert
    // donne le user courant lors d'un insert
    // il faut vérifier que boot hasSession car la row session à un champ userAdd
    final protected function onInsert($value,array $row,array $option)
    {
        $return = 1;
        $boot = static::bootReady();

        if(!empty($boot) && $boot->hasSession())
        $return = $boot->session()->user();

        return $return;
    }
}

// init
UserAdd::__init();
?>