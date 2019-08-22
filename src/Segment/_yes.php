<?php
declare(strict_types=1);
namespace Quid\Core\Segment;

// _yes
trait _yes
{
	// structureSegmentYes
	// gère le segment d'uri yes
	public static function structureSegmentYes(string $type,$value,array &$keyValue) 
	{
		$return = false;
		
		if($type === 'make' && $value === 1)
		$return = $value;
		
		elseif($type === 'validate' && $value === 1)
		$return = $value;
		
		elseif($type === 'validateDefault')
		$return = null;
		
		return $return;
	}
}
?>