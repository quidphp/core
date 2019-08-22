<?php
declare(strict_types=1);
namespace Quid\Core\Segment;
use Quid\Core;

// _colRelation
trait _colRelation
{
	// structureSegmentColRelation
	// gère le segment d'uri pour colonne qui doit être de relation
	public static function structureSegmentColRelation(string $type,$value,array &$keyValue) 
	{
		$return = false;
		
		if($type === 'make')
		{
			if(is_string($value) || $value instanceof Core\Col)
			$return = $value;
			
			elseif($value instanceof Core\Cell)
			$return = $value->col();
		}
		
		elseif($type === 'validate')
		{
			$table = static::tableSegment($keyValue);
			if(!empty($table) && $table->hasCol($value))
			{
				$col = $table->col($value);
				
				if($col->canRelation())
				$return = $col;
			}
		}
		
		return $return;
	}
}
?>