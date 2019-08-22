<?php
declare(strict_types=1);
namespace Quid\Core\App;
use Quid\Core;

// home
abstract class Home extends Core\Route\Home
{
	// config
	public static $config = array();
}

// config
Home::__config();
?>