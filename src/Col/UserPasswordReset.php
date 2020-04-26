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
use Quid\Base;
use Quid\Core;

// userPasswordReset
// class for the userPasswordReset column of a user row
class UserPasswordReset extends Core\ColAlias
{
    // config
    public static array $config = [
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
    final protected function onGet($return,array $option)
    {
        $return = $this->value($return);

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