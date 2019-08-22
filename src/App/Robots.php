<?php
declare(strict_types=1);
namespace Quid\Core\App;
use Quid\Core;

// robots
class Robots extends Core\Route\Robots
{
	// config
	public static $config = array();
}

// config
Robots::__config();
?>