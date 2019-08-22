<?php
declare(strict_types=1);
namespace Quid\Core\File;

// pdf
class Pdf extends BinaryAlias
{
	// config
	public static $config = [
		'group'=>'pdf'
	];
}

// config
Pdf::__config();
?>