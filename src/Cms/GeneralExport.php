<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Core;
use Quid\Orm;
use Quid\Base;

// generalExport
class GeneralExport extends Core\RouteAlias
{
	// trait
	use _common, _export, Core\Route\_generalSegment, Core\Segment\_table, Core\Segment\_order, Core\Segment\_direction, Core\Segment\_filter, Core\Segment\_primaries;
	
	
	// config
	public static $config = [
		'path'=>[
			'en'=>'table/export/[encoding]/[table]/[order]/[direction]/[filter]/[in]/[notIn]',
			'fr'=>'table/exportation/[encoding]/[table]/[order]/[direction]/[filter]/[in]/[notIn]'],
		'segment'=>[
			'encoding'=>'structureSegmentEncoding',
			'table'=>'structureSegmentTable',
			'order'=>'structureSegmentOrder',
			'direction'=>'structureSegmentDirection',
			'filter'=>'structureSegmentFilter',
			'in'=>'structureSegmentPrimaries',
			'notIn'=>'structureSegmentPrimaries'],	
		'match'=>[
			'role'=>['>='=>20]],
		'response'=>[
			'timeLimit'=>300],
		'query'=>['s'],
		'parent'=>General::class,
		'group'=>'submit',
		'navigation'=>false
	];
	

	// isLatin1
	// retourne vrai si l'encodage doit se fait en latin1
	protected function isLatin1():bool 
	{
		return ($this->segment('encoding') === 'latin1')? true:false;
	}
	
	
	// structureSegmentEncoding
	// gère le segment encoding de la route
	public static function structureSegmentEncoding(string $type,$value,array &$keyValue) 
	{
		$return = false;
		
		if($type === 'make')
		$return = (static::isEncoding($value))? $value:static::defaultEncoding();
		
		elseif($type === 'validate')
		$return = (static::isEncoding($value))? $value:false;
		
		return $return;
	}
	
	
	// file
	// retourne l'objet fichier
	protected function file():Core\File
	{
		$return = null;
		$table = $this->table();
		$basename = $table->name().'_'.Base\Date::format(0);
		$return = Core\File\Csv::new(true,['basename'=>$basename]);
		
		return $return;
	}
	
	
	// insertRows
	// insère les lignes dans l'objet
	// fait le par chunk car c'est trop long s'il y a plusieurs lignes
	protected function insertRows(Core\File $file,Orm\Sql $sql,int $limit=100):void
	{
		$offset = 0;
		$total = $sql->triggerRowCount();
		$option = ['header'=>true,'latin1'=>$this->isLatin1()];
		$storage = $this->session()->storage();
		
		if(!$storage instanceof Core\Row)
		static::throw('sessionStorageNeedsToBeRow');
		
		$not = Core\RowsIndex::newOverload($storage,static::sessionUser());
		
		if(is_int($total) && $total > 0)
		{
			while ($offset < $total) 
			{
				$sql->limit($limit,$offset);
				$rows = $sql->triggerRows();
				
				if(!empty($rows) && $rows->isNotEmpty())
				{
					$rows->writeFile($file,$option);
					$option['header'] = false;
					$rows->unlink($not);
				}
				
				$offset += $limit;
			}
		}
		
		return;
	}
	
	
	// trigger
	// lance la route generalExport
	public function trigger()
	{
		$sql = $this->sql();
		$file = $this->file();
		$this->insertRows($file,$sql);
		return $file->download();
	}
}

// config
GeneralExport::__config();
?>