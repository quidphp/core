<?php
declare(strict_types=1);
namespace Quid\Core\Col;

// contextType
class ContextType extends SetAlias
{
	// config
	public static $config = [
		'required'=>true,
		'complex'=>'checkbox',
		'relation'=>'contextType',
		'sortable'=>false, 
		'default'=>'app'
	];
}

// config
ContextType::__config();
?>