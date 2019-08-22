<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Base;

// timezone
class Timezone extends EnumAlias
{
	// config
	public static $config = array(
		'required'=>false,
		'relation'=>array(self::class,'getTimezones'),
		'check'=>array('kind'=>'int')
	);
	
	
	// description
	// retourne la description de la colonne, remplace le segment timezone si existant par la timezone courante
	public function description($pattern=null,?array $replace=null,?string $lang=null,?array $option=null):?string
	{
		return parent::description($pattern,Base\Arr::replace($replace,array('timezone'=>Base\Timezone::get())),$lang,$option);
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