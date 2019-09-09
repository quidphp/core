<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;
use Quid\Base;

// serialize
// class for a column which should serialize its value
class Serialize extends Core\ColAlias
{
	// config
	public static $config = [
		'search'=>false,
		'onSet'=>[Base\Crypt::class,'onSetSerialize'],
		'onGet'=>[Base\Crypt::class,'onGetSerialize'],
		'check'=>['kind'=>'text'],
		'onComplex'=>true
	];
}

// config
Serialize::__config();
?>