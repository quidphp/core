<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Core;

// accountChangePasswordSubmit
class AccountChangePasswordSubmit extends Core\Route\AccountChangePasswordSubmit
{
	// config
	public static $config = array(
		'parent'=>AccountChangePassword::class
	);
	
	
	// routeSuccess
	// route utilisé pour rediriger après le formulaire
	public function routeSuccess():Core\Route 
	{
		return Home::makeOverload();
	}
}

// config
AccountChangePasswordSubmit::__config();
?>