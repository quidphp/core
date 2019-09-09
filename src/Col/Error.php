<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;
use Quid\Base;

// error
// class for a column that manages an error object as a value
class Error extends Core\ColAlias
{
	// config
	public static $config = [
		'general'=>true,
		'complex'=>'div',
		'onComplex'=>true,
		'check'=>['kind'=>'text']
	];


	// onGet
	// sur onGet recrée l'objet error si c'est du json
	public function onGet($return,array $option)
	{
		if(!$return instanceof Core\Error)
		{
			$return = $this->value($return);

			if(is_scalar($return))
			{
				$return = Base\Json::decode($return);
				$return = Core\Error::newOverload($return);
			}
		}

		return $return;
	}
}

// config
Error::__config();
?>