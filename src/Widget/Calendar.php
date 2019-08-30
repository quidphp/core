<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Widget;
use Quid\Core;
use Quid\Main;

// calendar
// class that provides logic for the calendar widget
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