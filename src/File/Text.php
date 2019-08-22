<?php
declare(strict_types=1);
namespace Quid\Core\File;
use Quid\Core;

// text
abstract class Text extends Core\FileAlias
{
	// config
	public static $config = array(
		'group'=>'text'
	);
}

// config
Text::__config();
?>