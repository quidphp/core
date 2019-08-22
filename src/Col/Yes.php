<?php
declare(strict_types=1);
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