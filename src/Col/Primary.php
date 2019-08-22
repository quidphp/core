<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Core;

// primary
class Primary extends Core\ColAlias
{
	// config
	public static $config = [
		'cell'=>Core\Cell\Primary::class,
		'general'=>true,
		'order'=>true,
		'complex'=>'inputHidden',
		'check'=>['kind'=>'int'],
		'@cms'=>[
			'search'=>true]
	];
}

// config
Primary::__config();
?>