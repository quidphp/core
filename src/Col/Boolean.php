<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Base;

// boolean
class Boolean extends EnumAlias
{
	// config
	public static $config = array(
		'complex'=>'radio',
		'required'=>true,
		'relation'=>'bool',
		'onSet'=>array(Base\Set::class,'onSet'),
		'check'=>array('kind'=>'int')
	);
}

// config
Boolean::__config();
?>