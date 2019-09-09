<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;

// contextType
// class for the contextType column, a checkbox set relation with all boot types
class ContextType extends SetAlias
{
	// config
	public static $config = [
		'required'=>true,
		'complex'=>'checkbox',
		'relation'=>'contextType',
		'sortable'=>false
	];
}

// config
ContextType::__config();
?>