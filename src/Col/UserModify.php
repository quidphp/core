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

// userModify
// class for the userModify column, user_id is added automatically on update
class UserModify extends EnumAlias
{
    // config
    public static $config = [
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