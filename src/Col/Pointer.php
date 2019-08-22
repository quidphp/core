<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Core;

// pointer
class Pointer extends Core\ColAlias
{
	// config
	public static $config = [
		'required'=>true,
		'validate'=>['pointer'=>[self::class,'custom']]
	];
	
	
	// onGet
	// méthode appelé sur get, retourne la row ou null
	public function onGet($return,array $option) 
	{
		return static::getRow($this->value($return));
	}
	
	
	// getRow
	// retourne la row ou null
	public static function getRow($value):?Core\Row 
	{
		$return = null;
		
		if(is_string($value) && strlen($value))
		$return = static::boot()->db()->fromPointer($value);
		
		return $return;
	}
	
	
	// custom
	// méthode de validation custom pour le champ pointeur
	public static function custom($value) 
	{
		$return = null;
		$row = static::getRow($value);

		if(!empty($row))
		$return = true;
		
		return $return;
	}
}

// config
Pointer::__config();
?>