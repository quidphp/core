<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cell;
use Quid\Core;
use Quid\Base;

// userPasswordReset
class UserPasswordReset extends Core\CellAlias
{
	// hashSet
	// encrypte et set le nouveau mot de passe de réinitialisation
	// peut envoyer une exception
	public function hashSet(string $value):self
	{
		$hash = null;
		$col = $this->col();
		$table = $this->table();
		$hashOption = $table->attr('crypt/passwordHash');
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

// config
UserPasswordReset::__config();
?>