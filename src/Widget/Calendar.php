<?php
declare(strict_types=1);
namespace Quid\Core\Widget;
use Quid\Core;
use Quid\Main;
use Quid\Base;

// calendar
class Calendar extends Core\WidgetAlias
{
	// trait
	use Main\Widget\_calendar;
	
	
	// config
	public static $config = [];
}

// config
Calendar::__config();
?>