<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Core;

// specificCalendar
class SpecificCalendar extends Core\RouteAlias
{
	// trait
	use _common, Core\Route\_calendar, Core\Segment\_timestampMonth, Core\Segment\_str, Core\Segment\_selected;
	
	
	// config
	public static $config = array(
		'path'=>array(
			'fr'=>'specifique/calendrier/[timestamp]/[format]/[selected]',
			'en'=>'specific/calendar/[timestamp]/[format]/[selected]'),
		'segment'=>array(
			'timestamp'=>'structureSegmentTimestampMonth',
			'format'=>'structureSegmentStr',
			'selected'=>'structureSegmentSelected'),
		'match'=>array(
			'ajax'=>null,
			'role'=>array('>='=>20)),
		'widget'=>Core\Widget\Calendar::class
	);
	
	
	// setCallback
	// change les callback pour le calendrier de specific
	public function setCallback(Core\Widget\Calendar $return):Core\Widget\Calendar 
	{
		$return->setCallback('prev',function(int $value) {
			$route = $this->changeSegments(array('timestamp'=>$value));
			return $route->a(null,array('ajax','prev','white','icon','solo'));
		});
		$return->setCallback('next',function(int $value) {
			$route = $this->changeSegments(array('timestamp'=>$value));
			return $route->a(null,array('ajax','next','white','icon','solo'));
		});
		
		return $return;
	}
}

// config
SpecificCalendar::__config();
?>