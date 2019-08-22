<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Base;

// jsonExport
class JsonExport extends JsonAlias
{
	// config
	public static $config = array(
		'complex'=>'div',
		'onComplex'=>true,
		'visible'=>array('validate'=>'notEmpty')
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
JsonExport::__config();
?>