<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cms;
use Quid\Core;

// account
// class for the account route of the CMS, by default redirects to the user's specific route
class Account extends Core\Route\Account
{
	// trait
	use _common;


	// config
	public static $config = [];


	// onBefore
	// au lancement de la route, vérifie si le user peut aller voir son compte
	protected function onBefore()
	{
		return (static::sessionUser()->can('account'))? true:false;
	}


	// trigger
	// ne fait rien
	public function trigger()
	{
		return;
	}


	// afterRouteRedirect
	// après trigger renvoie vers la page specifique du user
	// un code 301 sera utilisé
	public function afterRouteRedirect():?Core\Route
	{
		return static::sessionUser()->route();
	}
}

// config
Account::__config();
?>