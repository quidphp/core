<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Base;

// timezone
// class for a column which is an enum relation to the PHP timezone array
class Timezone extends EnumAlias
{
	// config
	public static $config = [
		'required'=>false,
		'relation'=>[self::class,'getTimezones'],
		'check'=>['kind'=>'int']
	];


	// description
	// retourne la description de la colonne, remplace le segment timezone si existant par la timezone courante
	public function description($pattern=null,?array $replace=null,?string $lang=null,?array $option=null):?string
	{
		return parent::description($pattern,Base\Arr::replace($replace,['timezone'=>Base\Timezone::get()]),$lang,$option);
	}


	// getTimezones
	// retourne un tableau avec les timezones
	public static function getTimezones():array
	{
		return Base\Timezone::all();
	}
}

// config
Timezone::__config();
?>