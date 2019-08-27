<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Base\Html;
use Quid\Core;

// _specificRelation
trait _specificRelation
{
	// trait
	use _colRelation;


	// trigger
	// lance la route specificRelation
	public function trigger():string
	{
		$r = '';
		$results = $this->relationSearch();

		if(is_array($results) && !empty($results))
		{
			$col = $this->segment('col');
			$selected = $this->segment('selected');
			$loadMore = $this->loadMore();
			$r .= static::makeResults($results,$col,$selected,$loadMore);
		}

		if(empty($r))
		$r = Html::h3(static::langText('common/nothing'));

		return $r;
	}


	// col
	// retourne l'objet colonne
	public function col():Core\Col
	{
		return $this->segment('col');
	}


	// makeResults
	// génère les résultats d'affichage pour les relations
	public static function makeResults(array $array,Core\Col $col,array $selected=[],?string $loadMore=null):string
	{
		$r = '';

		if(!empty($array))
		{
			$r .= Html::ulOp();
			$rel = $col->relation();

			foreach ($array as $key => $value)
			{
				$html = $col->formComplexSearchChoices($key);
				$class = (in_array($key,$selected,true))? 'selected':null;
				$data = ['data-value'=>$key,'data-html'=>$html];
				$value = Html::div($value,'label');
				$r .= Html::li($value,[$class,'data'=>$data]);
			}

			if(!empty($r) && is_string($loadMore))
			$r .= $loadMore;

			$r .= Html::ulCl();
		}

		return $r;
	}
}
?>