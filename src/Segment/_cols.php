<?php
declare(strict_types=1);
namespace Quid\Core\Segment;
use Quid\Core;
use Quid\Base;

// _cols
trait _cols
{
	// structureSegmentCols
	// gère le segment d'uri pour plusieurs colonnes
	public static function structureSegmentCols(string $type,$value,array &$keyValue) 
	{
		$return = false;
		
		if($type === 'make')
		{
			if(!empty($value))
			{
				$default = static::getDefaultSegment();
				
				if($value instanceof Core\Cols)
				$value = $value->names();
				
				if(is_array($value))
				$value = implode($default,$value);
				
				if(is_string($value))
				$return = $value;
			}
		}
		
		else
		{
			$table = static::tableSegment($keyValue);
			
			if(!empty($table))
			{
				if($type === 'validate')
				{
					if(is_string($value) && !empty($value))
					{
						$default = static::getDefaultSegment();
						
						$array = Base\Str::explodeTrimClean($default,$value);
						$count = count($array);
						$value = $table->cols(...$array)->filter(['isVisibleGeneral'=>true]);
						
						if($value->isCount($count))
						$return = $value;
					}
					
					elseif($value instanceof Core\Cols)
					$return = $value;
				}
				
				elseif($type === 'validateDefault')
				$return = $table->cols()->general()->filter(['isVisibleGeneral'=>true]);
			}
		}
		
		return $return;
	}
}
?>