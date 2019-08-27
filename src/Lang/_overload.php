<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Lang;
use Quid\Main;

// _overload
trait _overload
{
	// trait
	use Main\_overload;


	// getOverloadKeyPrepend
	// retourne le prepend de la clé à utiliser pour le tableau overload
	public static function getOverloadKeyPrepend():?string
	{
		return 'Lang';
	}
}
?>