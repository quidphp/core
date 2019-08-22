<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Base\Html;
use Quid\Core;
use Quid\Base;

// jsonArrayRelation
class JsonArrayRelation extends Core\ColAlias
{
	// config
	public static $config = [
		'cell'=>Core\Cell\JsonArrayRelation::class,
		'required'=>true,
		'relationCols'=>null // custom
	];
	
	
	// onGet
	// méthode onGet pour jsonArrayRelation
	public function onGet($return,array $option)
	{
		if($return instanceof Core\Cell)
		{
			$value = $return->value();
			$return = (is_int($value))? $return->relationIndex($value):null;
		}
		
		else
		$return = parent::onGet($return,$option);
		
		return $return;
	}
	
	
	// formComplex
	// génère le formComplex pour jsonArrayRelation
	public function formComplex($value=true,?array $attr=null,?array $option=null):string 
	{
		$return = '';
		$tag = $this->complexTag($attr);
		$value = $this->valueComplex($value,$option);
		
		if(Html::isFormTag($tag,true))
		{
			$cell = ($value instanceof Core\Cell)? $value:null;
			$return .= parent::formComplex($value,$attr,$option);
			
			if(is_int($value) && !empty($cell))
			{
				$lang = $this->db()->lang();
				
				$label = $cell->relationIndex($value);
				if(is_string($label))
				$return .= Html::div($label,'underInput');
				else
				$return .= Html::div($lang->text('common/nothing'),['underInput','nothing']);
			}
		}
		
		else
		$return = Base\Debug::export($value);
		
		return $return;
	}
}

// config
JsonArrayRelation::__config();
?>