<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cms;
use Quid\Base\Html;
use Quid\Core;
use Quid\Base;

// homeSearch
class HomeSearch extends Core\RouteAlias
{
	// trait
	use _common; use Core\Route\_search;
	// config
	public static $config = [
		'path'=>[
			'en'=>'home/search',
			'fr'=>'accueil/recherche'],
		'match'=>[
			'ajax'=>true,
			'role'=>['>='=>20],
			'query'=>['s'=>true]],
		'search'=>['query'=>'s'],
		'parent'=>Home::class,
		'group'=>'submit'
	];


	// onBefore
	// avant le trigger de la route, vérifie si la recherche est possible
	protected function onBefore()
	{
		$return = false;

		if(static::sessionUser()->can('home/search'))
		{
			$search = $this->getSearchValue();

			if($search !== null)
			$return = true;
		}

		return $return;
	}


	// isSearchValueValid
	// retourne vrai si le terme de recherche est valide
	protected function isSearchValueValid(string $value):bool
	{
		$return = false;
		$searchable = $this->searchable();

		if($searchable->isNotEmpty() && $searchable->isSearchTermValid($value))
		$return = true;

		return $return;
	}


	// trigger
	// lance la route homeSearch
	public function trigger():string
	{
		$r = '';
		$search = $this->getSearchValue();
		$searchable = $this->searchable();
		$results = $searchable->search($search);
		$r .= $this->makeResults($results);

		if(empty($r))
		$r = Html::h3(static::langText('home/notFound'));

		return $r;
	}


	// makeResults
	// retourne les résultats de la recherche
	protected function makeResults(array $array):string
	{
		$r = '';
		$tables = $this->db()->tables();
		$search = $this->getSearchValue();
		$searchQuery = General::getSearchQuery();

		if(!empty($array))
		{
			$r .= Html::ulOp();

			foreach ($array as $key => $value)
			{
				if(is_array($value) && !empty($value))
				{
					$table = $tables->get($key);
					$count = count($value);
					$route = General::makeOverload(['table'=>$table]);
					$uri = Base\Uri::changeQuery([$searchQuery=>$search],$route->uri());
					$title = $route->title("% ($count)");

					$r .= Html::liOp();
					$r .= Html::a($uri,$title);
					$r .= Html::liCl();
				}
			}

			$r .= Html::ulCl();
		}

		return $r;
	}


	// searchable
	// retourne les tables cherchables et ayant les permission
	// est public car utilisé dans home
	public function searchable(bool $cols=true):Core\Tables
	{
		return $this->db()->tables()->searchable($cols)->hasPermission('search','view');
	}
}

// config
HomeSearch::__config();
?>