<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\Cell;
use Quid\Base;
use Quid\Core;

// userPasswordReset
// class to work with a password reset cell within a user table
class UserPasswordReset extends Core\CellAlias
{
    // hashSet
    // encrypte et set le nouveau mot de passe de réinitialisation
    // peut envoyer une exception
    final public function hashSet(string $value):self
    {
        $hash = null;
        $col = $this->col();
        $table = $this->table();
        $hashOption = $table->getAttr('crypt/passwordHash');
        $security = $col->getSecurity();

        if(Base\Validate::isPassword($value,$security))
        $hash = Base\Crypt::passwordHash($value,$hashOption);

        if(!empty($hash))
        $this->set($hash);

        else
        static::throw('invalidPassword');

        return $this;
    }
}

// init
UserPasswordReset::__init();
?>