<?php
declare(strict_types=1);
namespace Quid\Core\File;
use Quid\Main;

// log
class Log extends DumpAlias implements Main\Contract\Log, Main\Contract\FileStorage
{
	// trait
	use Main\File\_log;
	
	
	// config
	public static $config = [
		'dirname'=>'[storageLog]',
		'deleteTrim'=>500
	];
}

// config
Log::__config();
?>