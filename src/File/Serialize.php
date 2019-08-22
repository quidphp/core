<?php
declare(strict_types=1);
namespace Quid\Core\File;

// serialize
class Serialize extends TextAlias
{
	// config
	public static $config = array(
		'group'=>null,
		'type'=>'serialize'
	);
}

// config
Serialize::__config();
?>