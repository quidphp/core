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
    final protected function onCommit($value,array $row,?Core\Cell $cell=null,array $option)
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