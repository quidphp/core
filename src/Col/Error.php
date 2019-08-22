<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Core;
use Quid\Base;

// error
class Error extends Core\ColAlias
{
	// config
	public static $config = [
		'general'=>true,
		'complex'=>'div',
		'onComplex'=>true,
		'check'=>['kind'=>'text']
	];
	
	
	// onGet
	// sur onGet recrée l'objet error si c'est du json
	public function onGet($return,array $option)
	{
		if(!$return instanceof Core\Error)
		{
			$return = $this->value($return);
			
			if(is_scalar($return))
			{
				$return = Base\Json::decode($return);
				$return = Core\Error::newOverload($return);
				
				if(!empty($option['context']) && $option['context'] === 'cms:specific')
				$return = Base\Debug::export($return);
			}
		}
		
		return $return;
	}
}

// config
Error::__config();
?>