<?php
declare(strict_types=1);
namespace Quid\Core\File;

// audio
class Audio extends BinaryAlias
{
	// config
	public static $config = [
		'group'=>'audio'
	];
}

// config
Audio::__config();
?>