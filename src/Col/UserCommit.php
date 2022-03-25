<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;

// userCommit
// class for a column which stores the current user id on commit
class UserCommit extends EnumAlias
{
    // config
    protected static array $config = [
        'required'=>true,
        'complex'=>'div',
        'visible'=>['validate'=>'notEmpty'],
        'relation'=>'user',
        'duplicate'=>false,
        'check'=>['kind'=>'int']
    ];


    // onCommit
    // donne le user courant lors d'un insert ou un update
    // il faut vérifier que boot hasSession car la row session à un champ userCommit
    final protected function onCommit($value,?Core\Cell $cell,array $row,array $option)
    {
        $return = 1;
        $boot = static::bootReady();

        if(!empty($boot) && $boot->hasSession())
        $return = $boot->session()->user();

        return $return;
    }
}

// init
UserCommit::__init();
?>