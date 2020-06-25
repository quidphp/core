<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;

// userModify
// class for a column which stores the current user id on update
class UserModify extends EnumAlias
{
    // config
    protected static array $config = [
        'complex'=>'div',
        'visible'=>['validate'=>'notEmpty'],
        'relation'=>'user',
        'required'=>false,
        'duplicate'=>false,
        'check'=>['kind'=>'int']
    ];


    // onUpdate
    // donne le user courant lors d'un update
    final protected function onUpdate($cell,array $option)
    {
        $return = 1;
        $boot = static::bootReady();

        if(!empty($boot))
        $return = $boot->session()->user();

        return $return;
    }
}

// init
UserModify::__init();
?>