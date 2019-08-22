<?php
declare(strict_types=1);
namespace Quid\Core\File;
use Quid\Core;

// binary
abstract class Binary extends Core\FileAlias
{
	// config
	public static $config = array();
}

// config
Binary::__config();
?>