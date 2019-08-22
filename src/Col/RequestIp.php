<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Core;

// requestIp
class RequestIp extends Core\ColAlias
{
	// config
	public static $config = [
		'general'=>true
	];
	
	
	// onCommit
	// donne le ip de la requête courante lors d'un insert ou un update
	public function onCommit($value,array $row,?Core\Cell $cell=null,array $option):?string
	{
		$return = null;
		$boot = static::bootReady();
		
		if(!empty($boot))
		$return = $boot->request()->ip();
		
		return $return;
	}
}

// config
RequestIp::__config();
?>