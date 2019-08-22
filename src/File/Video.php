<?php
declare(strict_types=1);
namespace Quid\Core\File;

// video
class Video extends BinaryAlias
{
	// config
	public static $config = array(
		'group'=>'video'
	);
}

// config
Video::__config();
?>