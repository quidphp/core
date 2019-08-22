<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Core;

// floating
class Floating extends Core\ColAlias
{
	// config
	public static $config = array(
		'cell'=>Core\Cell\Floating::class
	);
}

// config
Floating::__config();
?>