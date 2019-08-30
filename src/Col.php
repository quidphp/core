<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Orm;
use Quid\Base;

// col
// extended class to represent an existing column within a table
class Col extends Orm\Col
{
	// trait
	use _routeAttr;
	use _accessAlias;


	// config
	public static $config = [
		'route'=>null, // permet de définir la route à utiliser en lien avec complex
		'@cms'=>[
			'sortable'=>null, // le champ est sortable dans le cms
			'generalExcerptMin'=>100] // excerpt min pour l'affichage dans general
	];


	// getOverloadKeyPrepend
	// retourne le prepend de la clé à utiliser pour le tableau overload
	public static function getOverloadKeyPrepend():?string
	{
		return (static::class !== self::class && !Base\Fqcn::sameName(static::class,self::class))? 'Col':null;
	}
}

// config
Col::__config();
?>