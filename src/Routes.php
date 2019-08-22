<?php
declare(strict_types=1);
namespace Quid\Core;
use Quid\Routing;

// routes
class Routes extends Routing\Routes
{
	// trait
	use _fullAccess;
	
	
	// config
	public static $config = [];
}

// config
Routes::__config();
?>