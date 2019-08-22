<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Core;
use Quid\Base;

// serialize
class Serialize extends Core\ColAlias
{
	// config
	public static $config = array(
		'search'=>false,
		'onSet'=>array(Base\Crypt::class,'onSetSerialize'),
		'onGet'=>array(Base\Crypt::class,'onGetSerialize'),
		'check'=>array('kind'=>'text'),
		'onComplex'=>true
	);
	
	
	// onGet
	// onGet spécial si contexte est cms, retourne le résultat debug/export
	public function onGet($return,array $option)
	{
		$return = parent::onGet($return,$option);

		if(is_array($return) && !empty($option['context']) && $option['context'] === 'cms:specific')
		$return = Base\Debug::export($return);

		return $return;
	}
}

// config
Serialize::__config();
?>