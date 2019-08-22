<?php
declare(strict_types=1);
namespace Quid\Core\Col;

// fragment
class Fragment extends SlugAlias
{
	// config
	public static $config = array(
		'unique'=>false,
		'validate'=>array(1=>'fragment')
	);
}

// config
Fragment::__config();
?>