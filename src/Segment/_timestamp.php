<?php
declare(strict_types=1);
namespace Quid\Core\Segment;

// _timestamp
trait _timestamp
{
	// structureSegmentTimestamp
	// gère le segment d'uri pour un timestamp, doit être plus grand que 0
	public static function structureSegmentTimestamp(string $type,$value,array &$keyValue) 
	{
		$return = false;
		
		if($type === 'make')
		$return = (is_int($value) && $value > 0)? $value:false;
		
		elseif($type === 'validate')
		$return = (is_int($value) && $value > 0)? $value:false;
		
		elseif($type === 'validateDefault')
		$return = null;
		
		return $return;
	}
}
?>