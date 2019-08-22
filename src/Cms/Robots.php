<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Core;

// robots
class Robots extends Core\Route\Robots
{
	// config
	public static $config = [
		'allow'=>false
	];
}

// config
Robots::__config();
?>