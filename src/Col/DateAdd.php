<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Base;

// dateAdd
class DateAdd extends DateAlias
{
	// config
	public static $config = [
		'general'=>true,
		'date'=>'long',
		'complex'=>'div',
		'visible'=>['validate'=>'notEmpty'],
		'duplicate'=>false,
		'editable'=>false,
		'onGet'=>[[Base\Date::class,'onGet'],'long']
	];
	
	
	// onInsert
	// sur insert retourne le timestamp
	public function onInsert($value,array $row,array $option):int 
	{
		return Base\Date::timestamp();
	}
}

// config
DateAdd::__config();
?>