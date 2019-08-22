<?php
declare(strict_types=1);
namespace Quid\Core\Segment;

// _int
trait _int
{
	// structureSegmentInt
	// gère le segment d'uri pour un chiffre entier, int, accepte 0
	public static function structureSegmentInt(string $type,$value,array &$keyValue) 
	{
		$return = false;
		$default = static::structureSegmentIntDefault();
		
		if($type === 'make')
		$return = (is_int($value) && $value >= 0)? $value:false;
		
		elseif($type === 'validate')
		{
			$return = (is_int($value) && $value >= 0)? $value:false;
			
			$possible = static::structureSegmentIntPossible();
			if($return !== false && !empty($possible))
			$return = (in_array($value,$possible,true))? $value:false;
		}
		
		elseif($type === 'validateDefault')
		$return = $default;
		
		return $return;
	}
	
	
	// structureSegmentIntDefault
	// retourne le int par défaut pour le segment
	public static function structureSegmentIntDefault() 
	{
		return null;
	}
	
	
	// structureSegmentIntPossible
	// retourne un tableau avec les int possible pour la route
	public static function structureSegmentIntPossible():?array
	{
		return null;
	}
}
?>