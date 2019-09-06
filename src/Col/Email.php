<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;
use Quid\Base;

// email
// class for a column managing email
class Email extends Core\ColAlias
{
	// config
	public static $config = [
		'validate'=>[1=>'email'],
		'general'=>true,
		'check'=>['kind'=>'char']
	];


	// onGet
	// sur onGet retourne le courriel dans un lien a:mailto
	public function onGet($return,array $option)
	{
		$return = $this->value($return);
		$option['context'] = (empty($option['context']))? null:$option['context'];

		if(is_string($return) && !empty($return))
		{
			if(!in_array($option['context'],['cms:generalExport','noHtml'],true))
			{
				if($option['context'] === 'cms:general' && empty($option['excerpt']))
				$option['excerpt'] = 30;

				$title = true;
				if(!empty($option['excerpt']))
				$title = Base\Str::excerpt($option['excerpt'],$return);

				$return = Base\Html::a($return,$title);
			}
		}

		return $return;
	}


	// formComplex
	// génère le formulaire complex pour email
	public function formComplex($value=true,?array $attr=null,?array $option=null):string
	{
		$return = '';
		$tag = $this->complexTag($attr);

		if(Base\Html::isFormTag($tag))
		$return .= parent::formComplex($value,$attr,$option);

		else
		$return .= parent::formComplex($value,$attr,$option);

		return $return;
	}
}

// config
Email::__config();
?>