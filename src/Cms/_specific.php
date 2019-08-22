<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Base\Html;
use Quid\Core;
use Quid\Main;
use Quid\Base;

// _specific
trait _specific
{
	//  configSpecific
	public static $configSpecific = array(
		'formWrap'=>"<div class='left'><div class='label'>%label%%popup%</div>%description%%details%</div><div class='right'>%form%</div>"
	);
	
	
	// onReplace
	// tableau onReplace pour la route
	protected function onReplace(array $return):array 
	{
		$return['title'] = $this->title();
		$return['metaDescription'] = $this->table()->description();
		
		return $return;
	}
	
	
	// panel
	// retourne le tableau des panneaux
	protected function panel():array 
	{
		return $this->cache(__METHOD__,function() {
			$return = array();
			$table = $this->table();
			$cols = $table->cols()->withoutPrimary();
			
			if($table->hasPanel())
			$group = $cols->group('panel');
			
			else
			$group = array($cols);
			
			foreach ($group as $key => $cols) 
			{
				if($cols->isNotEmpty())
				{
					if($this->isPanelVisible($cols))
					$return[$key] = $cols;
				}
			}
			
			return $return;
		});
	}
	
	
	// hasPanel
	// retourne vrai si la route a des panneaux
	protected function hasPanel():bool 
	{
		$return = false;
		$panel = $this->panel();
		
		if(count($panel) > 1)
		$return = true;
		
		return $return;
	}
	
	
	// makeTop
	// génère la partie supérieure de la page specifique
	protected function makeTop():string 
	{
		$r = '';
		$table = $this->table();
		
		$r .= Html::divOp("top");
		$r .= Html::divOp("left");
		
		$r .= Html::div($this->makeTitleBox(),'title');
		
		if($table->hasPermission('description'))
		$r .= Html::divCond($table->description(),array('description','subTitle'));
		
		$r .= Html::divCl();
		
		$r .= Html::divCond($this->makeNav(),'right');
		$r .= Html::divCl();
		
		return $r;
	}
	

	// makeFormTop
	// génère la partie supérieure du formulaire spécifique
	protected function makeFormTop():string 
	{
		$r = '';
		$r .= $this->makeFormHidden();
		
		$r .= Html::divOp("top");
		$r .= Html::divOp('left');
		$r .= $this->makeFormPanel();
		$r .= Html::divCl();
		
		$r .= Html::divCond($this->makeOperation('top'),'right');
		$r .= Html::divCl();
		
		return $r;
	}
	
	
	// makeFormHidden
	// génère les input hiddens du formulaire
	protected function makeFormHidden():string 
	{
		$r = $this->tableHiddenInput();
		
		if($this->hasPanel())
		$r .= Html::inputHidden(null,static::panelInputName());
		
		return $r;
	}
	
	
	// makeFormPanel
	// crée le conteneur des panneaux
	protected function makeFormPanel():string 
	{
		$r = '';
		
		if($this->hasPanel())
		{
			$table = $this->table();
			$lang = $this->lang();
			$panel = $this->panel();
			
			$r .= Html::ulOp();
			
			foreach ($panel as $key => $cols) 
			{
				if($cols->isNotEmpty())
				{
					$data = array();
					
					if($table->hasPermission('panelDescription'))
					$data['description'] = $lang->panelDescription($key);
					
					$r .= Html::liOp();
					$r .= Html::aOpen("#$key",null,array('data'=>$data));
					$r .= Html::h3($lang->panelLabel($key));
					$r .= Html::aCl();
					$r .= Html::liCl();
				}
			}
			
			$r .= Html::ulCl();
			
			if($table->hasPermission('panelDescription'))
			$r .= Html::div(true,'description');
		}
		
		return $r;
	}
	
	
	// makeFormPopup
	// génère le popup d'informations pour une colonne
	protected function makeFormPopup(Core\Col $col):string 
	{
		$r = '';
		$table = $this->table();
		$colPopup = $table->permission('colPopup');
		
		if(is_array($colPopup) && !empty($colPopup))
		{
			$lang = $this->lang();
			
			$r .= Html::divOp('popup');
			$r .= Html::div(null,array('icon','topRight','solo','info'));
			$r .= Html::ulOp();
			
			foreach ($colPopup as $v) 
			{
				$label = static::langText('specific/'.$v);
				
				if($v === 'required')
				$value = $col->isRequired();
				
				elseif($v === 'unique')
				$value = $col->shouldBeUnique();
				
				elseif($v === 'editable')
				$value = $col->isEditable();
				
				elseif($v === 'pattern')
				$value = $col->rulePattern(true);
				
				elseif($v === 'preValidate')
				$value = $col->rulePreValidate(true);
				
				elseif($v === 'validate')
				$value = $col->ruleValidate(true);
				
				elseif($v === 'compare')
				$value = $col->ruleCompare(true);
				
				elseif($v === 'classCol')
				$value = $col->classFqcn();
				
				elseif($v === 'classCell')
				$value = $col->table()->classe()->cell($col);
				
				elseif($v === 'default')
				{
					$value = $col->default();
					if($value === null && $col->hasNullDefault())
					$value = 'NULL';
				}
				
				elseif($v === 'orderable')
				$value = $col->isOrderable();
				
				elseif($v === 'filterable')
				$value = $col->isFilterable();
				
				elseif($v === 'searchable')
				$value = $col->isSearchable();
				
				else
				$value = $col->$v();
				
				if(is_bool($value))
				$value = $lang->bool($value);
				
				if(is_array($value))
				$value = Base\Arr::clean($value);
				
				if(!Base\Validate::isReallyEmpty($value))
				{
					$r .= Html::liOp();
					$r .= Html::span($label.":");
					
					if(is_array($value))
					{
						$lis = Html::liMany(...$value);
						$r .= Html::ulCond($lis);
					}
					
					else
					$r .= Html::span($value);
					
					$r .= Html::liCl();
				}
			}
			
			$r .= Html::ulCl();
			$r .= Html::divCl();
		}
		
		return $r;
	}
	
	
	// makeFormInner
	// génère l'intérieur d'un panneau avec tous les champs inclus
	protected function makeFormInner():string 
	{
		$r = '';
		$hasPanel = $this->hasPanel();
		$panel = $this->panel();
		$lang = $this->lang();
		
		if(!empty($panel))
		{
			$r .= Html::divOp('inside');
			
			foreach ($panel as $key => $cols) 
			{
				if($cols->isNotEmpty())
				{
					$data = ($hasPanel === true)? array('fragment'=>$key):null;
					$r .= Html::divOp(array('panel','data'=>$data));
					
					foreach ($cols as $col) 
					{
						$r .= $this->makeFormOne($col);
					}
					
					$r .= Html::divCl();
				}
			}
			
			$r .= Html::divCl();
		}
		
		return $r;
	}
	
	
	// makeFormOne
	// génère un champ du formulaire
	protected function makeFormOne(Core\Col $col):string 
	{
		$r = '';
		$colCell = $this->colCellVisible($col);
		
		if(!empty($colCell))
		{
			$description = $colCell->description();
			$details = $colCell->details();
			$popup = $this->makeFormPopup($col);
			$formWrap = '';
			
			$class = (array) $col->classHtml();
			$class[] = ($col->isRequired())? 'required':null;
			$class[] = (!empty($popup) || $col->isDate() || $col->isRelation())? 'anchorCorner':null;
			$class[] = (!empty($popup))? array('hasColPopup'):null;
			$class[] = ($colCell->hasFormLabelId($this->formWrapAttr($colCell),true))? 'pointer':null;
			$attr = array('element',$class,'data'=>array('col'=>$col));
			
			$detailsHtml = Html::liMany(...$details);
			$detailsHtml = Html::ulCond($detailsHtml);
			 
			$replace = array();
			$replace['description'] = (!empty($description))? Html::div($description,'description'):'';
			$replace['popup'] = $popup;
			$replace['details'] = (!empty($details))? Html::divCond($detailsHtml,'details'):'';
			
			try 
			{
				$formWrap = $this->makeFormWrap($colCell,$replace);
			} 
			
			catch (Main\CatchableException $e) 
			{
				$attr[] = 'exception';
				$formWrap = Html::div($colCell->label(),'label');
				$e->onCatched();
			}
			
			finally 
			{
				$r .= Html::div($formWrap,$attr);
			}
		}
		
		return $r;
	}
	
	
	// getFormWrap
	// retourne la string formWrap a utilisé pour chaque champ
	public static function getFormWrap():string 
	{
		return static::$config['formWrap'];
	}
}
?>