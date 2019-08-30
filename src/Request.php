<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Main;

// request
// extended class with methods to manage an HTTP request
class Request extends Main\Request
{
	// trait
	use _bootAccess;


	// config
	public static $config = [];


	// match
	// retourne un tableau avec toutes les routes qui matchs avec la requête
	public function match(Routes $routes,?Session $session=null):?array
	{
		return $routes->match($this,$session);
	}


	// matchOne
	// retourne la première route qui match avec la requête
	public function matchOne(Routes $routes,?Session $session=null):?string
	{
		return $routes->matchOne($this,$session);
	}


	// route
	// retourne la première route qui match avec la requête
	// la route retourné est triggé
	public function route(Routes $routes,?Session $session=null):?Route
	{
		return $routes->route($this,$session);
	}
}

// config
Request::__config();
?>