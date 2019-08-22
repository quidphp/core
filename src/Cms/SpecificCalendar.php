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
	public static $config = [
		'path'=>[
			'fr'=>'specifique/calendrier/[timestamp]/[format]/[selected]',
			'en'=>'specific/calendar/[timestamp]/[format]/[selected]'],
		'segment'=>[
			'timestamp'=>'structureSegmentTimestampMonth',
			'format'=>'structureSegmentStr',
			'selected'=>'structureSegmentSelected'],
		'match'=>[
			'ajax'=>null,
			'role'=>['>='=>20]],
		'widget'=>Core\Widget\Calendar::class
	];
	
	
	// setCallback
	// change les callback pour le calendrier de specific
	public function setCallback(Core\Widget\Calendar $return):Core\Widget\Calendar 
	{
		$return->setCallback('prev',function(int $value) {
			$route = $this->changeSegments(['timestamp'=>$value]);
			return $route->a(null,['ajax','prev','white','icon','solo']);
		});
		$return->setCallback('next',function(int $value) {
			$route = $this->changeSegments(['timestamp'=>$value]);
			return $route->a(null,['ajax','next','white','icon','solo']);
		});
		
		return $return;
	}
}

// config
SpecificCalendar::__config();
?>