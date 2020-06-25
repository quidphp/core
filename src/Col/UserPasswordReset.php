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
use Quid\Orm;

// userPasswordReset
// class for the userPasswordReset column of a user row
class UserPasswordReset extends Core\ColAlias
{
    // config
    protected static array $config = [
        'complex'=>'div',
        'cell'=>Core\Cell\UserPasswordReset::class,
        'search'=>false,
        'visible'=>[
            'validate'=>'notEmpty',
            'role'=>['>='=>70]],
        'visibleGeneral'=>false,
        'onComplex'=>true,
        'export'=>false,
        'check'=>['kind'=>'char'],
        'security'=>null // custom, défini le niveau de sécurité du mot de passe utilisé, support pour loose
    ];


    // onGet
    // retourne une string sha1 du hash
    final protected function onGet($return,?Orm\Cell $cell=null,array $option)
    {
        if(is_string($return) && !empty($return))
        $return = Base\Crypt::passwordActivate($return,1);

        return $return;
    }


    // getSecurity
    // retourne le niveau de sécurité du mot de passe
    final public function getSecurity():?string
    {
        return $this->getAttr('security');
    }
}

// init
UserPasswordReset::__init();
?>