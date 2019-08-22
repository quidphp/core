<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Core;

// integer
class Integer extends Core\ColAlias
{
	// config
	public static $config = [
		'cell'=>Core\Cell\Integer::class
	];
}

// config
Integer::__config();
?>