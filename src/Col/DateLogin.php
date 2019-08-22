<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Base;

// dateLogin
class DateLogin extends DateAlias
{
	// config
	public static $config = [
		'general'=>false,
		'complex'=>'div',
		'date'=>'long',
		'visible'=>['validate'=>'notEmpty'],
		'onGet'=>[[Base\Date::class,'onGet'],'long'],
	];
}

// config
DateLogin::__config();
?>