<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Base\Html;

// _generalPager
// trait that provides a method to make a general page navigator
trait _generalPager
{
	// makeGeneralPager
	// construit un block de navigation à partir d'un tableau general
	protected function makeGeneralPager(array $general,bool $firstLast=true,bool $prevNext=true,bool $str=false)
	{
		$return = [];

		if(!empty($general) && array_key_exists('total',$general) && $general['total'] > 1)
		{
			$loop = [];
			($firstLast === true)? ($loop[] = 'first'):null;
			($prevNext === true)? ($loop[] = 'prev'):null;
			$loop[] = 'closest';
			($prevNext === true)? ($loop[] = 'next'):null;
			($firstLast === true)? ($loop[] = 'last'):null;

			if(!empty($loop))
			{
				foreach ($loop as $v)
				{
					if(array_key_exists($v,$general) && !empty($general[$v]))
					{
						if($v === 'closest' && !empty($general['closest']) && is_array($general['closest']))
						{
							$closest = '';

							foreach ($general['closest'] as $v)
							{
								$route = $this->changeSegment('page',$v);
								$closest .= $route->a($v);
							}

							if(strlen($closest))
							$return['closest'] = Html::div($closest,'closest');
						}

						elseif(is_int($general[$v]))
						{
							$route = $this->changeSegment('page',$general[$v]);
							$return[$v] = $route->a(null,$v);
						}
					}
				}
			}
		}

		if($str === true)
		$return = implode('',$return);

		return $return;
	}
}
?>