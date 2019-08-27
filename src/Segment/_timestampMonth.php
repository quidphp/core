<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Segment;
use Quid\Base;

// _timestampMonth
trait _timestampMonth
{
	// structureSegmentTimestampMonth
	// gère le segment d'uri pour un timestamp de mois, utile pour un calendrier
	public static function structureSegmentTimestampMonth(string $type,$value,array &$keyValue)
	{
		$return = false;

		if($type === 'make')
		{
			if(is_string($value))
			{
				if(Base\Date::isFormat('ym',$value))
				$return = $value;

				elseif(Base\Date::isFormat('dateToDay',$value))
				$value = Base\Date::format('ym',$value,'dateToDay');
			}

			elseif(is_int($value))
			$return = Base\Date::format('ym',$value);
		}

		elseif($type === 'validate')
		{
			if(is_scalar($value))
			$return = $value;
		}

		elseif($type === 'validateDefault')
		$return = Base\Date::floorMonth();

		return $return;
	}
}
?>