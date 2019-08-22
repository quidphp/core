<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Base;

// yes
class Yes extends EnumAlias
{
	// config
	public static $config = array(
		'complex'=>'checkbox',
		'required'=>false,
		'relation'=>'yes',
		'preValidate'=>array('arrMaxCount'=>1),
		'onSet'=>array(Base\Set::class,'onSet'),
		'check'=>array('kind'=>'int')
	);
}

// config
Yes::__config();
?>