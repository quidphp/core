<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cms;
use Quid\Core;

// error
// class for the error route of the CMS
class Error extends Core\Route\Error
{
	// trait
	use _templateAlias;


	// config
	public static $config = [];


	// main
	// génère la page erreur dans la balise main
	protected function main():string
	{
		return $this->html();
	}


	// somebody
	// génère la page erreur si l'utilisateur est somebody (connecté)
	protected function somebody():string
	{
		return $this->detail(Home::makeOverload());
	}
}

// config
Error::__config();
?>