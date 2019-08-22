<?php
declare(strict_types=1);
namespace Quid\Core\Route;
use Quid\Core;

// logout
abstract class Logout extends Core\RouteAlias
{
	// config
	public static $config = array(
		'path'=>array(
			'en'=>'logout',
			'fr'=>'deconnexion'),
		'match'=>array(
			'role'=>array('>'=>'nobody')),
		'parent'=>Login::class,
		'sitemap'=>false,
		'redirectable'=>false,
		'com'=>true,
		'navigation'=>false
	);
	
	
	// afterRouteRedirect
	// renvoie vers le parent en cas de succès
	public function afterRouteRedirect():Core\Route
	{
		return static::makeParentOverload();
	}
	
	
	// trigger
	// lance la route logout, redirige vers le parent
	public function trigger()
	{
		static::session()->logoutProcess(array('com'=>true));
		
		return;
	}
}

// config
Logout::__config();
?>