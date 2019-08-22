<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Base\Html;
use Quid\Core;
use Quid\Orm;
use Quid\Base;

// textarea
class Textarea extends Core\ColAlias
{
	// config
	public static $config = array(
		'tag'=>'textarea',
		'search'=>true,
		'check'=>array('kind'=>'text'),
		'relative'=>null, // custom, type pour absoluteReplace, utilise ceci pour ramener les liens absoluts dans leur version relative 
		'tableRelation'=>null, // custom, défini les tables en relation
		'@cms'=>array(
			'route'=>array('tableRelation'=>Core\Cms\SpecificTableRelation::class))
	);
	
	
	// onSet
	// gère la logique onSet pour textarea
	// la seule chose géré est le remplacement des liens absoluts pour leur version relatives
	public function onSet($return,array $row,?Orm\Cell $cell=null,array $option) 
	{
		$return = parent::onSet($return,$row,$cell,$option);
		
		if(is_string($return))
		$return = $this->absoluteReplace($return);
		
		return $return;
	}
	
	
	// hasTableRelation
	// retourne vrai si le textarea a des table relation
	public function hasTableRelation():bool 
	{
		$return = false;
		$relations = $this->attr('tableRelation');
		
		if(is_array($relations) && !empty($relations))
		$return = true;
		
		return $return;
	}
	
	
	// classHtml
	// retourne la classe additionnelle à utiliser
	public function classHtml():array
	{
		$return = array(parent::classHtml());
		
		if($this->hasTableRelation())
		$return[] = 'tableRelation';
		
		return $return;
	}
	
	
	// formComplex
	// génère le formComplex pour tinymce, avec une box relation
	public function formComplex($value=true,?array $attr=null,?array $option=null):string 
	{
		$tag = $this->tag($attr);
		$return = parent::formComplex($value,$attr,$option);
		
		if($this->hasTableRelation() && Html::isFormTag($tag,true))
		{
			$relations = $this->attr('tableRelation');
			$tables = $this->db()->tables();
			$tables = $tables->gets(...array_values($relations));
			
			if($tables->isNotEmpty())
			$return .= Html::divCond($this->relationBox($tables),'relations');
		}
		
		return $return;
	}
	
	
	// relationBox
	// génère la box relation pour le champ wysiwyg
	public function relationBox(Core\Tables $tables):string 
	{
		$r = '';
		
		foreach ($tables as $table) 
		{
			$route = $this->route('tableRelation',array('table'=>$table));
			$r .= $route::makeClickOpen($table,$route,array('clickOpen','filter','anchorCorner'));
		}
		
		return $r;
	}
	

	// absoluteReplace
	// remplacement des liens absoluts vers relatifs dans le bloc texte
	protected function absoluteReplace(string $return):string 
	{
		$relative = $this->attr('relative');

		if(!empty($relative))
		{
			$relative = (array) $relative;
			$boot = static::boot();
			$replace = array();
			
			foreach ($relative as $type) 
			{
				foreach ($boot->schemeHostEnvs($type) as $schemeHost) 
				{
					$schemeHost .= "/";
					$replace[$schemeHost] = '/';
				}
			}
			
			if(!empty($replace))
			$return = Base\Str::replace($replace,$return);
		}
		
		return $return;
	}
}

// config
Textarea::__config();
?>