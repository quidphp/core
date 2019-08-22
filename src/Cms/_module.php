<?php
declare(strict_types=1);
namespace Quid\Core\Cms;

// _module
trait _module
{
	// config
	public static $configModule = [
		'match'=>[
			'role'=>'admin'],
		'response'=>[
			'timeLimit'=>30],
		'group'=>'cms/module',
		'sitemap'=>false,
		'navigation'=>false,
		'ignore'=>false
	];
}
?>