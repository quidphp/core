<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Core;

// logout
class Logout extends Core\Route\Logout
{
	// trait
	use _common;
	
	
	// config
	public static $config = [
		'match'=>[
			'role'=>['>='=>20]],
		'parent'=>Login::class
	];
}

// config
Logout::__config();
?>