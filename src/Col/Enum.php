<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Core;

// enum
class Enum extends RelationAlias
{
	// config
	public static $config = [
		'cell'=>Core\Cell\Enum::class,
		'enum'=>true,
		'required'=>true,
		'order'=>true,
		'complex'=>[0=>'select',11=>'search']
	];
	
	
	// isEnum
	// retourne vrai comme la colonne est de type relation enum
	public function isEnum():bool 
	{
		return true;
	}
}

// config
Enum::__config();
?>