<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Core;

// countCommit
class CountCommit extends Core\ColAlias
{
	// config
	public static $config = [
		'required'=>true,
		'check'=>['kind'=>'int']
	];
	
	
	// onCommit
	// sur commit incrémente le count
	public function onCommit($value,array $row,?Core\Cell $cell=null,array $option):int
	{
		return (is_int($value))? ($value + 1):0;
	}
}

// config
CountCommit::__config();
?>