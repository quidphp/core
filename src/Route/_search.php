<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Base;

// _search
// trait that grants methods for search route
trait _search
{
	// config
	public static $configSearch = [
		'search'=>[
			'decode'=>0,
			'query'=>'s']
	];


	// isSearchValueValid
	// retourne vrai si la valeur de recherche est valide
	protected function isSearchValueValid(string $value):bool
	{
		return true;
	}


	// getSearchValue
	// retourne la valeur de la recherche,
	// peut retourner null
	protected function getSearchValue():?string
	{
		$return = null;
		$searchQuery = $this->getSearchQuery();
		$search = $this->request()->getQuery($searchQuery);

		if(is_scalar($search))
		{
			$search = (string) $search;
			$decode = static::getSearchDecodeType();

			if(strlen($search) && $this->isSearchValueValid($search))
			$return = Base\Uri::decode($search,$decode);
		}

		return $return;
	}


	// getSearchDecodeType
	// retourne le type de décodage à utiliser pour la query de recherche
	// par défaut 0, il faut utiliser 1 si la recherche est faite via GET sans javascript
	public static function getSearchDecodeType():int
	{
		return static::$config['search']['decode'];
	}


	// getSearchQuery
	// retourne la query à utiliser, envoie une exception si non existant
	public static function getSearchQuery():string
	{
		return static::$config['search']['query'];
	}
}
?>