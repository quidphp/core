<?php
declare(strict_types=1);
namespace Quid\Core\File;
use Quid\Main;

// cache
class Cache extends SerializeAlias implements Main\Contract\FileStorage
{
	// trait
	use Main\File\_storage;
	
	
	// config
	public static $config = [
		'dirname'=>'[storageCache]'
	];
}

// config
Cache::__config();
?>