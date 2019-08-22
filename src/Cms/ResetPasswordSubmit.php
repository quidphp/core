<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Core;

// resetPasswordSubmit
class ResetPasswordSubmit extends Core\Route\ResetPasswordSubmit
{
	// config
	public static $config = array(
		'parent'=>ResetPassword::class
	);
}

// config
ResetPasswordSubmit::__config();
?>