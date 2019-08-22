<?php
declare(strict_types=1);
namespace Quid\Core;
use Quid\Main;

// response
class Response extends Main\Response
{
	// trait
	use _bootAccess;
	
	
	// config
	public static $config = array();
}

// config
Response::__config();
?>