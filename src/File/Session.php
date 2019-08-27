<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;
use Quid\Main;

// session
class Session extends SerializeAlias implements Main\Contract\Session, Main\Contract\FileStorage
{
	// trait
	use Main\File\_session;


	// config
	public static $config = [];
}

// config
Session::__config();
?>