<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Core;
use Quid\Orm;
use Quid\Base;

// phone
class Phone extends Core\ColAlias
{
	// config
	public static $config = array(
		'tag'=>'inputText',
		'search'=>false,
		'validate'=>array(1=>'phone'),
		'onComplex'=>true,
		'check'=>array('kind'=>'char'),
		'phone'=>null // custom
	);
	
	
	// onGet
	// ramène le numéro de téléphone dans un format nord-américain
	public function onGet($return,array $option) 
	{
		$return = $this->value($return);
		
		if(!empty($return))
		$return = Base\Number::phoneFormat($return,null,$this->attr('phone'));
		
		return $return;
	}
	
	
	// onSet
	// gère la logique onSet pour un téléphone
	// enlève tous les caractères non numérique
	public function onSet($return,array $row,?Orm\Cell $cell=null,array $option) 
	{
		if(is_string($return))
		$return = Base\Str::keepNumber($return);
		
		return $return;
	}
}

// config
Phone::__config();
?>