<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Core;
use Quid\Base;

// _calendar
trait _calendar
{
	// config
	public static $configCalendar = [
		'widget'=>null // à spécifier, la classe du calendrier
	];


	// getTimestamp
	// retourne le timestamp
	public function getTimestamp():int
	{
		$return = null;
		$value = $this->segment('timestamp');

		if(is_string($value))
		{
			if(Base\Date::isFormat('ym',$value))
			$value = Base\Date::time($value,'ym');

			elseif(Base\Date::isFormat('dateToDay',$value))
			$value = Base\Date::time($value,'dateToDay');
		}

		if(!is_int($value))
		$value = null;

		$return = Base\Date::floorMonth($value);

		return $return;
	}


	// calendar
	// génère l'objet calendrier
	public function calendar():Core\Widget
	{
		$class = static::$config['widget'];

		if(empty($class))
		static::throw('noWidgetClassProvided');

		$timestamp = $this->getTimestamp();
		$return = $class::newOverload($timestamp);
		$timestamp = $return->timestamp();

		if($this->hasSegment('format'))
		{
			$format = $this->segment('format');
			if(!empty($format))
			$return->setFormat($format);
		}

		if($this->hasSegment('selected'))
		{
			$selected = $this->segment('selected');
			if(!empty($selected))
			$return->setSelected($selected);
		}

		$return = $this->setCallback($return);

		return $return;
	}


	// setCallback
	// méthode abstraite pour ajouter des callback à l'objet calendrier
	abstract public function setCallback(Core\Widget $value):Core\Widget;


	// html
	// génère le html pour la page
	public function html()
	{
		return $this->calendar()->output();
	}


	// trigger
	// lance la route calendrier
	public function trigger():string
	{
		return $this->html();
	}
}
?>