<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Main;

// flash
// extended class for a collection containing flash-like data (delete on read)
class Flash extends Main\Flash
{
	// trait
	use _bootAccess;


	// config
	public static $config = [];


	// onPrepareKey
	// préparation d'une clé pour flash
	// gestion de l'objet route
	protected function onPrepareKey($return)
	{
		if($return instanceof Route)
		$return = $return::classFqcn();

		return parent::onPrepareKey($return);
	}


	// setPost
	// flash les données de post
	// prend les données de post de l'objet request dans inst
	public function setPost(Route $route,bool $onlyCol=true,bool $stripTags=false)
	{
		$request = static::boot()->request();
		$post = $request->post($onlyCol,$stripTags);
		$key = $route::classFqcn();
		$this->set($key,$post);

		return $this;
	}
}
?>