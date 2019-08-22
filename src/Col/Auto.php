<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Core;
use Quid\Orm;
use Quid\Base;

// auto
class Auto extends Core\ColAlias
{
	// config
	public static $config = [
		'complex'=>'div',
		'setPriority'=>9,
		'include'=>true,
		'required'=>false,
		'visible'=>['validate'=>'notEmpty'],
		'auto'=>[ // custom
			'pair'=>'cellName',
			'callable'=>[],
			'cols'=>[],
			'separator'=>', ']
	];
	

	// onSet
	// sur onSet créer les valeurs automatiques à partir des colonnes spécifiés et du séparateur
	public function onSet($return,array $row,?Orm\Cell $cell=null,array $option) 
	{
		$return = '';
		$attr = $this->autoAttr();
		$array = [];
		
		foreach ($attr['cols'] as $segment) 
		{
			if(is_string($segment))
			{
				$hasSegment = Base\Segment::has(null,$segment);
				
				if($hasSegment === true)
				$cols = Base\Segment::get(null,$segment);
				else
				$cols = [$segment];
				
				if(!empty($cols))
				{
					$callable = $attr['callable'][$segment] ?? null;
					$value = $this->autoCols($segment,$cols,$row,$callable);
					
					if(!empty($value) && !in_array($value,$array,true))
					$array[] = $value;
				}
			}
		}
		
		if(!empty($array))
		$return = implode($attr['separator'],$array);
		
		return $return;
	}
	
	
	// autoCols
	// va chercher les éléments à partir des tableaux colonnes et row
	public function autoCols($segment,array $cols,array $row,?callable $callable=null) 
	{
		$return = null;
		$attr = $this->autoAttr();
		$table = $this->table();
		$value = Base\Arr::gets($cols,$row);
		$value = Base\Arr::trimClean($value);
		$hasSegment = Base\Segment::has(null,$segment);
		
		if(!empty($value))
		{
			foreach ($value as $k => $v) 
			{
				if(is_string($k) && $table->hasCol($k))
				{
					$col = $table->col($k);
					if($col->isRelation())
					{
						$rows = $col->relation()->rows($v);
						$names = $rows->pair($attr['pair']);
						$value[$k] = implode($attr['separator'],$names);
					}
				}
			}
			
			$value = Base\Arr::trimClean($value);
			
			if($hasSegment === true)
			$value = ((count($value) === count($cols)))? Base\Segment::sets(null,$value,$segment):null;
			
			else
			$value = (!empty($value))? current($value):null;
			
			if(!empty($value))
			{
				if(!empty($callable))
				$value = $callable($value,$this);
				
				$return = $value;
			}
		}
		
		return $return;
	}
	
	
	// autoAttr
	// retourne les attr pour auto
	// peut envoyer une exception si attr invalide
	public function autoAttr():array
	{
		$return = null;
		$attr = $this->attr('auto');
		
		if(is_array($attr) && !empty($attr['separator']) && is_string($attr['separator']) && !empty($attr['pair']) && !empty($attr['cols']))
		{
			$return = $attr;
			$return['cols'] = (array) $return['cols'];
		}
		
		else
		static::throw();
		
		return $return;
	}
}

// config
Auto::__config();
?>