<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Core;
use Quid\Base;

// json
class Json extends Core\ColAlias
{
	// config
	public static $config = array(
		'search'=>false,
		'panel'=>'admin',
		'onSet'=>array(Base\Json::class,'onSet'),
		'onGet'=>array(Base\Json::class,'onGet'),
		'check'=>array('kind'=>'text')
	);
}

// config
Json::__config();
?>