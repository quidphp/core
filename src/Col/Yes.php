<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Base;

// yes
class Yes extends EnumAlias
{
	// config
	public static $config = [
		'complex'=>'checkbox',
		'required'=>false,
		'relation'=>'yes',
		'preValidate'=>['arrMaxCount'=>1],
		'onSet'=>[Base\Set::class,'onSet'],
		'check'=>['kind'=>'int']
	];
}

// config
Yes::__config();
?>