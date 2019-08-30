<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Base\Html;

// _nobody
// trait that provides a common method for a route when the user is not logged in
trait _nobody
{
	// browscap
	// génère le html pour les capacités du browser (noscript et cookie)
	protected function browscap():string
	{
		$r = '';
		$r .= Html::noscript(static::langText('browscap/noscript'));
		$r .= Html::div(static::langText('browscap/cookie'),'cookie');

		return $r;
	}
}
?>