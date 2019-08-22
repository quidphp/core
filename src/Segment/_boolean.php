<?php
declare(strict_types=1);
namespace Quid\Core\Segment;

// _boolean
trait _boolean
{
	// structureSegmentBoolean
	// gère le segment d'uri booléen
	public static function structureSegmentBoolean(string $type,$value,array &$keyValue) 
	{
		$return = false;
		
		if($type === 'make')
		$return = (in_array($value,[0,1],true))? $value:false;
		
		elseif($type === 'validate')
		$return = (in_array($value,[0,1],true))? $value:false;
		
		return $return;
	}
}
?>