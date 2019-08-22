<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Core;

// loginSubmit
class LoginSubmit extends Core\Route\LoginSubmit
{
	// trait
	use _common;
	
	
	// config
	public static $config = array(
		'parent'=>Login::class
	);
	
	
	// routeSuccess
	// retourne la route vers laquelle redirigé en cas de succès par défaut, si rien dans la mémoire
	public function routeSuccessDefault():Core\Route
	{
		return Home::makeOverload();
	}
}

// config
LoginSubmit::__config();
?>