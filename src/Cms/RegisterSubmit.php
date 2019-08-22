<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Core;

// registerSubmit
class RegisterSubmit extends Core\Route\RegisterSubmit
{
	//  config
	public static $config = [
		'parent'=>Register::class
	];
}

// config
RegisterSubmit::__config();
?>