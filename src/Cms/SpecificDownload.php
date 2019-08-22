<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Core;

// specificDownload
class SpecificDownload extends Core\RouteAlias
{
	// trait
	use _common, Core\Route\_download, Core\Segment\_table, Core\Segment\_primary, Core\Segment\_col, Core\Segment\_int;
	
	
	// config
	public static $config = [
		'path'=>[
			'fr'=>'specifique/telechargement/[table]/[primary]/[col]/[index]',
			'en'=>'specific/download/[table]/[primary]/[col]/[index]'],
		'segment'=>[
			'table'=>'structureSegmentTable',
			'primary'=>'structureSegmentPrimary',
			'col'=>'structureSegmentCol',
			'index'=>'structureSegmentInt'],
		'match'=>[
			'role'=>['>='=>20]]
	];
	
	
	// onBefore
	// vérifie qu'il y a une colonne et que c'est un média
	protected function onBefore() 
	{
		$return = false;
		$table = $this->segment('table');
		
		if($table instanceof Core\Table && $table->hasPermission('download'))
		{
			$col = $this->segment('col');
			if(!empty($col) && $col->isMedia())
			$return = true;
		}
		
		return $return;
	}
	
	
	// cell
	// retourne la cellule défini par les segments
	public function cell():Core\Cell
	{
		$row = $this->segment('primary');
		$col = $this->segment('col');
		$return = $row->cell($col);
		
		return $return;
	}
}

// config
SpecificDownload::__config();
?>