<?php
declare(strict_types=1);
namespace Quid\Core\Segment;
use Quid\Core;
use Quid\Base;

// _primaries
trait _primaries
{
	// structureSegmentPrimaries
	// gère le segment d'uri pour plusieurs clés primaires (rows)
	public static function structureSegmentPrimaries(string $type,$value,array &$keyValue) 
	{
		$return = false;
		
		if($type === 'make')
		{
			if(!empty($value))
			{
				$default = static::getDefaultSegment();
				
				if($value instanceof Core\Row)
				$value = $value->primary();
				
				if($value instanceof Core\Rows)
				$value = $value->primaries();
				
				if(is_array($value))
				$value = implode($default,$value);
				
				if(is_scalar($value) && !is_bool($value))
				$return = (string) $value;
			}
		}
		
		elseif($type === 'validate')
		{
			if($value instanceof Core\Row)
			$value = $value->primary();
			
			if(is_scalar($value) && !is_bool($value) && !empty($value))
			{
				$default = static::getDefaultSegment();
				$value = (string) $value;
				
				$array = Base\Str::explodeTrimClean($default,$value);
				if(Base\Arr::onlyNumeric($array))
				$return = Base\Arr::cast($array);
			}
			
			elseif($value instanceof Core\Rows)
			$return = $value->primaries();
		}
		
		elseif($type === 'validateDefault')
		$return = array();
		
		return $return;
	}
}
?>