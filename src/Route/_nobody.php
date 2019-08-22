<?php
declare(strict_types=1);
namespace Quid\Core\Route;
use Quid\Base\Html;

// _nobody
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