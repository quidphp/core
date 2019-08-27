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

// table
class Table extends Orm\Table
{
	// trait
	use _routeAttr;
	use _accessAlias;


	// config
	public static $config = [
		'route'=>null, // permet de lier une classe de route à la table
		'@prod'=>[
			'colsExists'=>false],
		'@app'=>[
			'where'=>true,
			'route'=>[
				'cms'=>Cms\Specific::class],
			'search'=>false],
		'@cms'=>[
			'homeTask'=>null, // pour cms, ajouter un lien vers la page d'ajout dans task
			'specificAddNavLink'=>null, // pour le cms, permet de diviser le lien add et view en deux
			'generalOperation'=>null, // pour le cms, méthode pour ajouter un bouton en haut à droite dans general
			'specificOperation'=>null, // pour le cms, méthode pour ajouter un bouton en haut à droite dans specific
			'order'=>['id'=>'desc'],
			'relation'=>['appendPrimary'=>true],
			'route'=>[
				0=>Cms\Specific::class,
				'general'=>Cms\General::class,
				'cms'=>Cms\Specific::class]]
	];


	// tableFromFqcn
	// retourne l'objet table à partir du fqcn de la classe
	// utilise boot
	// envoie une erreur si la table n'existe pas
	public static function tableFromFqcn():self
	{
		$return = (static::class !== self::class)? static::boot()->db()->table(static::class):null;

		if(!$return instanceof self)
		static::throw();

		return $return;
	}


	// getOverloadKeyPrepend
	// retourne le prepend de la clé à utiliser pour le tableau overload
	public static function getOverloadKeyPrepend():?string
	{
		return (static::class !== self::class && !Base\Fqcn::sameName(static::class,self::class))? 'Table':null;
	}
}

// config
Table::__config();
?>