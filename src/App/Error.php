<?php
declare(strict_types=1);
namespace Quid\Core\App;
use Quid\Core;

// error
abstract class Error extends Core\Route\Error
{
	// config
	public static $config = [];
}

// config
Error::__config();
?>