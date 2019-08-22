<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Core;
use Quid\Base;

// request
class Request extends Core\ColAlias
{
	// config
	public static $config = array(
		'general'=>true,
		'complex'=>'div',
		'onComplex'=>true,
		'required'=>true,
		'check'=>array('kind'=>'text')
	);
	
	
	// onInsert
	// retourner la requête en json sur insertion
	public function onInsert($value,array $row,array $option):?string
	{
		$return = null;
		$boot = static::bootReady();
		
		if(!empty($boot))
		$return = $boot->request()->toJson();
		
		return $return;
	}
	
	
	// onGet
	// sur onGet recrée l'objet request
	public function onGet($return,array $option)
	{
		if(!$return instanceof Core\Request)
		{
			$return = $this->value($return);

			if(is_scalar($return))
			{
				$return = Base\Json::decode($return);
				$return = Core\Request::newOverload($return);
				
				if(!empty($option['context']) && $option['context'] === 'cms:specific')
				$return = Base\Debug::export($return->safeInfo());
			}
		}
		
		return $return;
	}
}

// config
Request::__config();
?>