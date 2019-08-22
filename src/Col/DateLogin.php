<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Base;

// dateLogin
class DateLogin extends DateAlias
{
	// config
	public static $config = array(
		'general'=>false,
		'complex'=>'div',
		'date'=>'long',
		'visible'=>array('validate'=>'notEmpty'),
		'onGet'=>array(array(Base\Date::class,'onGet'),'long'),
	);
}

// config
DateLogin::__config();
?>