<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Core;

// account
class Account extends Core\Route\Account
{
	// trait
	use _common;
	
	
	// config
	public static $config = array();
	
	
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