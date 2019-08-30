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

// row
// extended class to represent an existing row within a table
class Row extends Orm\Row
{
	// trait
	use _routeAttr;
	use _accessAlias;


	// config
	public static $config = [];


	// inAllSegment
	// retourne vrai si la route doit être ajouté allSegment de route
	// en lien avec le sitemap
	public function inAllSegment():bool
	{
		return false;
	}


	// tableFromFqcn
	// retourne l'objet table à partir du fqcn de la classe
	// envoie une erreur si la table n'existe pas
	public static function tableFromFqcn():Table
	{
		$return = (static::class !== self::class)? static::boot()->db()->table(static::class):null;

		if(!$return instanceof Table)
		static::throw();

		return $return;
	}


	// row
	// permet de retourner un objet row de la table
	public static function row($row):?self
	{
		return static::tableFromFqcn()->row($row);
	}


	// rows
	// permet de retourner l'objet rows de la table
	public static function rows(...$values):Rows
	{
		return static::tableFromFqcn()->rows(...$values);
	}


	// rowsVisible
	// permet de retourner l'objet rows de la table, mais l'objet contient seulement les lignes visibles
	public static function rowsVisible(...$values):Rows
	{
		return static::tableFromFqcn()->rowsVisible(...$values);
	}


	// rowsVisibleOrder
	// permet de retourner l'objet rows de la table, mais l'objet contient seulement les lignes visibles et dans l'ordre par défaut de la table
	public static function rowsVisibleOrder(...$values):Rows
	{
		return static::tableFromFqcn()->rowsVisibleOrder(...$values);
	}


	// select
	// permet de faire une requête select sur la table de la classe via méthode static
	public static function select(...$values):?self
	{
		return static::tableFromFqcn()->select(...$values);
	}


	// selects
	// permet de faire une requête selects sur la table de la classe via méthode static
	public static function selects(...$values):Rows
	{
		return static::tableFromFqcn()->selects(...$values);
	}


	// grab
	// permet de faire une requête selects (grab) sur la table de la classe via méthode static
	public static function grab($where=null,$limit=null,bool $visible=false):Rows
	{
		return static::tableFromFqcn()->grab($where,$limit,$visible);
	}


	// grabVisible
	// permet de faire une requête select (grabVisible) sur la table de la classe via méthode static
	// seuls les rows qui passent la méthode isVisible sont retournés
	public static function grabVisible($where=true,$limit=null):Rows
	{
		return static::tableFromFqcn()->grabVisible($where,$limit);
	}


	// insert
	// permet d'insérer une ligne dans la table à partir du fqcn
	public static function insert(array $set=[],?array $option=null)
	{
		return static::tableFromFqcn()->insert($set,$option);
	}


	// getOverloadKeyPrepend
	// retourne le prepend de la clé à utiliser pour le tableau overload
	public static function getOverloadKeyPrepend():?string
	{
		return (static::class !== self::class && !Base\Fqcn::sameName(static::class,self::class))? 'Row':null;
	}
}
?>