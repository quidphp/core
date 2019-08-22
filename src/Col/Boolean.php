<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Base;

// boolean
class Boolean extends EnumAlias
{
	// config
	public static $config = [
		'complex'=>'radio',
		'required'=>true,
		'relation'=>'bool',
		'onSet'=>[Base\Set::class,'onSet'],
		'check'=>['kind'=>'int']
	];
}

// config
Boolean::__config();
?>