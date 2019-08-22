<?php
declare(strict_types=1);
namespace Quid\Core\Test;
use Quid\Core;
use Quid\Base;

// widget
class Widget extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// getOverloadKeyPrepend
		
		// calendar
		$cal = new Core\Widget\Calendar(array(2018,12));
		$cal->setCallback('day',function($v) {
			return "-$v-";
		})->setCallback('prev',function() {
			return 'PREV';
		})->setCallback('next',function() {
			return 'NEXT';
		});
		$cal2 = clone $cal;
		assert(Base\Arrs::is($cal->toArray()));
		assert($cal->callback('prev') instanceof \Closure);
		assert($cal->_cast() === $cal->timestamp());
		assert(!empty($cal->timestamp()));
		assert(!empty($cal->prevTimestamp()));
		assert($cal->prevTimestamp() < $cal->timestamp());
		assert(!empty($cal->nextTimestamp()));
		assert($cal->nextTimestamp() > $cal->timestamp());
		assert($cal->setSelected(Base\Date::mk(2018,12,3)) === $cal);
		assert($cal->setFormat('dateToDay') === $cal);
		assert($cal->parseTimestamp(1543813203)['data-timestamp'] === 1543813200);
		assert($cal->parseTimestamp(1543813200)['data-timestamp'] === 1543813200);
		assert($cal->setFormat('dateToDay') === $cal);
		assert($cal->format() === 'dateToDay');
		assert($cal->setSelected(Base\Date::mk(2018,12,4)) === $cal);
		assert($cal->selected() === array(1543899600));
		assert(count($cal->structure()) === 6);
		assert(strlen($cal->output()) > 3600);
		
		return true;
	}
}
?>