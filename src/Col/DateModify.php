<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Core;
use Quid\Base;

// dateModify
class DateModify extends DateAlias
{
	// config
	public static $config = [
		'complex'=>'div',
		'visible'=>['validate'=>'notEmpty'],
		'date'=>'long',
		'duplicate'=>false,
		'onGet'=>[[Base\Date::class,'onGet'],'long'],
	];
	
	
	// onUpdate
	// sur mise à jour, retourne le timestamp
	public function onUpdate(Core\Cell $cell,array $option):int
	{
		return Base\Date::timestamp();
	}
}

// config
DateModify::__config();
?>