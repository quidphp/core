<?php
declare(strict_types=1);
namespace Quid\Core\Route;
use Quid\Core;

// home
abstract class Home extends Core\RouteAlias
{
	// config
	public static $config = [
		'path'=>'',
		'group'=>'home',
		'priority'=>1
	];
	
	
	// onReplace
	// comme titre, met le bootLabel
	protected function onReplace(array $return):array 
	{
		$return['title'] = $return['bootLabel'];
		
		return $return;
	}
}

// config
Home::__config();
?>