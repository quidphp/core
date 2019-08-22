<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Core;
use Quid\Base;

// context
class Context extends Core\ColAlias
{
	// config
	public static $config = array(
		'required'=>true,
		'general'=>true,
		'visible'=>array('validate'=>'notEmpty'),
		'complex'=>'div',
		'onComplex'=>true,
		'onGet'=>array(Base\Json::class,'onGet'),
		'onSet'=>array(Base\Json::class,'onSet'),
		'check'=>array('kind'=>'char')
	);
	
	
	// onCommit
	// ajoute le contexte sur insertion ou mise à jour
	public function onCommit($value,array $row,?Core\Cell $cell=null,array $option):?array
	{
		$return = null;
		$boot = static::bootReady();
		
		if(!empty($boot))
		$return = $boot->context();
		
		return $return;
	}
	
	
	// onGet
	// format spécial si le contexte est cms (le type courant)
	public function onGet($return,array $option)
	{
		$return = parent::onGet($return,$option);
		
		if(is_array($return) && !empty($option['context']) && $option['type'] === 'cms')
		$return = implode(' - ',$return);
		
		return $return;
	}
}

// config
Context::__config();
?>