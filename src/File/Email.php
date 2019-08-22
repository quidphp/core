<?php
declare(strict_types=1);
namespace Quid\Core\File;
use Quid\Main;

// email
class Email extends JsonAlias implements Main\Contract\Email
{
	// trait
	use Main\File\_email;
	
	
	// config
	public static $config = [];
}

// config
Email::__config();
?>