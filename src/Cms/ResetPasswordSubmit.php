<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cms;
use Quid\Core;

// resetPasswordSubmit
// class for the submit reset password route of the CMS
class ResetPasswordSubmit extends Core\Route\ResetPasswordSubmit
{
	// config
	public static $config = [
		'parent'=>ResetPassword::class
	];
}

// config
ResetPasswordSubmit::__config();
?>