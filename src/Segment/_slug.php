<?php
declare(strict_types=1);
namespace Quid\Core\Segment;

// _slug
trait _slug
{
	// structureSegmentSlug
	// gère le segment d'uri pour un slug
	public static function structureSegmentSlug(string $type,$value,array &$keyValue) 
	{
		$return = false;
		$table = static::tableSegment($keyValue);
		
		if(!empty($table))
		{
			$rowClass = $table->classe()->row();
			
			if($type === 'make')
			{
				if(is_int($value))
				$value = $table->row($value);
				
				if(is_a($value,$rowClass,true))
				$return = $value->cellKey();
				
				elseif(is_string($value) && !empty($value))
				$return = $value;
			}
			
			elseif($type === 'validate')
			{
				if(is_string($value) && !empty($value))
				$return = $table->row($value) ?? false;
				
				elseif(is_a($value,$rowClass,true))
				$return = $value;
			}
			
			elseif($type === 'validateDefault')
			$return = static::structureSegmentSlugValidateDefault();
		}
		
		return $return;
	}
	
	
	// structureSegmentSlugValidateDefault
	// retourne la valeur par défaut pour le segment
	public static function structureSegmentSlugValidateDefault() 
	{
		return false;
	}
}
?>