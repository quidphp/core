<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Base\Html;
use Quid\Core;

// generalExportDialog
class GeneralExportDialog extends Core\RouteAlias
{
	// trait
	use _common, _export, Core\Route\_generalSegment, Core\Segment\_table, Core\Segment\_order, Core\Segment\_direction, Core\Segment\_filter, Core\Segment\_primaries;
	
	
	// config
	public static $config = array(
		'path'=>array(
			'en'=>'dialog/export/[table]/[order]/[direction]/[filter]/[in]/[notIn]',
			'fr'=>'dialogue/exportation/[table]/[order]/[direction]/[filter]/[in]/[notIn]'),
		'segment'=>array(
			'table'=>'structureSegmentTable',
			'order'=>'structureSegmentOrder',
			'direction'=>'structureSegmentDirection',
			'filter'=>'structureSegmentFilter',
			'in'=>'structureSegmentPrimaries',
			'notIn'=>'structureSegmentPrimaries'),
		'match'=>array(
			'ajax'=>true,
			'role'=>array('>='=>20)),
		'longExport'=>1500,
		'query'=>array('s'),
		'parent'=>General::class,
		'group'=>'dialog'
	);
	
	
	// trigger
	// html pour la page avant l'exportation, s'ouvre dans une box
	public function trigger() 
	{
		$r = '';
		$table = $this->table();
		$sql = $this->sql();
		$total = $sql->triggerRowCount();
		$longExport = static::longExport();
		$count = $total." ".static::langPlural($total,'lc|common/row');
		
		$r .= Html::divtableOpen();
		$r .= Html::h1(static::label());
		$r .= Html::h2($table->label());
		$r .= Html::div($count,'count');
		$r .= Html::h3(static::langText('export/encoding').":");
		$r .= Html::divCond($this->makeChoices(),'choices');
		
		$r .= Html::ulOp();
		$r .= Html::li(static::langText('export/office'));
		
		if($total > $longExport)
		$r .= Html::li(static::langText('export/long'));
		$r .= Html::ulCl();
		
		$r .= Html::divtableClose();
		
		return $r;
	}
	
	
	// makeChoices
	// génère les choix de route, en lien avec l'encodage
	protected function makeChoices():string 
	{
		$r = '';
		$encoding = static::getEncoding();
		$segment = $this->segment();
		$route = GeneralExport::makeOverload($segment);
		
		foreach ($encoding as $value) 
		{
			$route = $route->changeSegment('encoding',$value);
			$label = static::langText(array('export',$value));
			$r .= $route->a($label,array('submit','icon','padLeft','download'));
		}
		
		return $r;
	}
	
	
	// aDialog
	// retourne le lien dialog
	public function aDialog():string
	{
		return $this->aTitle(null,array('submit','icon','padLeft','download','data'=>array('jsBox'=>'dialogGeneralExport')));
	}
	
	
	// longExport
	// retourne le nombre de ligne pour considérer que c'est une longue exportation
	public static function longExport():int
	{
		return static::$config['longExport'];
	}
}

// config
GeneralExportDialog::__config();
?>