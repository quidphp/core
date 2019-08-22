<?php
declare(strict_types=1);
namespace Quid\Core;
use Quid\Main;

// bootException
class BootException extends Main\Exception
{
	// config
	public static $config = array(
		'code'=>35 // code de l'exception
	);
}

// config
BootException::__config();
?>