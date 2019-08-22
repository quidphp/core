<?php
declare(strict_types=1);
namespace Quid\Core;
use Quid\Orm;
use Quid\Base;

// table
class Table extends Orm\Table
{
	// trait
	use _routeAttr, _accessAlias;
	
	
	// config
	public static $config = array(
		'route'=>null, // permet de lier une classe de route à la table
		'@prod'=>array(
			'colsExists'=>false),
		'@app'=>array(
			'where'=>true,
			'route'=>array(
				'cms'=>Cms\Specific::class),
			'search'=>false),
		'@cms'=>array(
			'homeTask'=>null, // pour cms, ajouter un lien vers la page d'ajout dans task
			'specificAddNavLink'=>null, // pour le cms, permet de diviser le lien add et view en deux
			'generalOperation'=>null, // pour le cms, méthode pour ajouter un bouton en haut à droite dans general
			'specificOperation'=>null, // pour le cms, méthode pour ajouter un bouton en haut à droite dans specific
			'order'=>array('id'=>'desc'),
			'relation'=>array('appendPrimary'=>true),
			'route'=>array(
				0=>Cms\Specific::class,
				'general'=>Cms\General::class,
				'cms'=>Cms\Specific::class))
	);
	
	
	// tableFromFqcn
	// retourne l'objet table à partir du fqcn de la classe
	// utilise boot
	// envoie une erreur si la table n'existe pas
	public static function tableFromFqcn():self
	{
		$return = (static::class !== self::class)? static::boot()->db()->table(static::class):null;
		
		if(!$return instanceof Table)
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