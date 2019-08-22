<?php
declare(strict_types=1);
namespace Quid\Core\Segment;
use Quid\Core;

// _primary
trait _primary
{
	// structureSegmentPrimary
	// gère le segment d'uri pour une clé primaire (row)
	public static function structureSegmentPrimary(string $type,$value,array &$keyValue) 
	{
		$return = false;
		
		if($type === 'make')
		{
			if($value instanceof Core\Cell)
			$value = $value->row();
			
			if(is_int($value) || $value instanceof Core\Row)
			$return = $value;
		}
		
		elseif($type === 'validate')
		{
			$table = static::tableSegment($keyValue);
			
			if(!empty($table))
			{
				if(is_int($value) || $value instanceof Core\Row)
				{
					$row = $table->row($value);
					
					if(!empty($row) && $row->table() === $table)
					$return = $row;
				}
			}
		}
		
		return $return;
	}
}
?>