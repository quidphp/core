<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cms;
use Quid\Base\Html;
use Quid\Core;
use Quid\Base;

// specific
class Specific extends Core\RouteAlias
{
	// trait
	use _templateAlias; use _general; use _specific; use Core\Route\_specificNav; use Core\Route\_specificPrimary; use Core\Segment\_table; use Core\Segment\_primary;
	// config
	public static $config = [
		'path'=>[
			'en'=>'table/[table]/[primary]',
			'fr'=>'table/[table]/[primary]'],
		'segment'=>[
			'table'=>'structureSegmentTable',
			'primary'=>'structureSegmentPrimary'],
		'match'=>[
			'role'=>['>='=>20]],
		'sitemap'=>true
	];


	// onBefore
	// validation avant le lancement de la route
	protected function onBefore()
	{
		$return = false;
		$table = $this->segment('table');

		if($table instanceof Core\Table && $table->hasPermission('view','specific'))
		$return = true;

		return $return;
	}


	// selectedUri
	// retourne les uris sélectionnés pour la route
	// la route account peut être sélectionner
	public function selectedUri():array
	{
		$return = [];
		$table = $this->table();
		$user = static::sessionUser();

		$root = static::session()->routeTableGeneral($table);
		$uri = $root->uri();
		$return[$uri] = true;

		if($user->route()->uri() === $this->uri())
		{
			$account = Account::makeOverload()->uri();
			$return[$account] = true;
		}

		return $return;
	}


	// allSegment
	// retourne tous les segments pour la route specific, un par table et id
	public static function allSegment():array
	{
		$return = [];
		$db = static::db();

		foreach ($db->tables() as $table)
		{
			if($table->hasPermission('view'))
			{
				$name = $table->name();
				$primary = $table->primary();
				foreach ($db->selectColumns($primary,$table,null,[$primary=>'desc'],100) as $id)
				{
					$return[] = ['table'=>$name,'primary'=>$id];
				}
			}
		}

		return $return;
	}


	// isUpdateable
	// retourne vrai si la row peut être modifié
	public function isUpdateable():bool
	{
		$return = false;
		$table = $this->table();
		$row = $this->row();

		if($table->hasPermission('modify','update') && $row->isUpdateable())
		$return = true;

		return $return;
	}


	// isDeleteable
	// retourne vrai si la row peut être effacé
	public function isDeleteable():bool
	{
		$return = false;
		$table = $this->table();
		$row = $this->row();

		if($table->hasPermission('delete','remove') && $row->isDeleteable())
		$return = true;

		return $return;
	}


	// isUpdateableOrDeleteable
	// retourne vrai si la row peut être modifié ou effacé
	public function isUpdateableOrDeleteable():bool
	{
		$return = $this->isUpdateable();

		if($return === false)
		$return = $this->isDeleteable();

		return $return;
	}


	// isPanelVisible
	// retourne vrai si le panneau est visible
	protected function isPanelVisible(Core\Cols $cols):bool
	{
		$return = true;
		$row = $this->row();
		$cells = $row->cells()->gets($cols);
		$session = static::session();

		if($cells->isHidden($session))
		$return = false;

		return $return;
	}


	// main
	// fait main pour specific
	public function main():string
	{
		$r = $this->makeTop();
		$r .= $this->makeForm();

		return $r;
	}


	// makeTitleBox
	// génère le titre pour la page specific
	protected function makeTitleBox():string
	{
		$r = Html::h1($this->makeTitle());
		$r .= Html::divCond($this->makeRelationChilds(),['relationChilds','countInfo','withPopup','anchorCorner']);

		return $r;
	}


	// makeTitle
	// génère le titre pour la route
	protected function makeTitle(?string $lang=null):string
	{
		return $this->row()->label(null,$lang);
	}


	// makeRelationChilds
	// génère le block pour les enfants direct
	protected function makeRelationChilds():string
	{
		$r = '';
		$table = $this->table();

		if($table->hasPermission('relationChilds'))
		{
			$row = $this->row();
			$relationChilds = $row->relationChilds();

			if(is_array($relationChilds) && !empty($relationChilds))
			{
				$count = Base\Arrs::countLevel(2,$relationChilds);
				$text = static::langPlural($count,'specific/relationChilds',['count'=>$count]);
				$r .= Html::div($text,['count','icon','info','padLeft']);
				$r .= Html::divOp('popup');
				$r .= Html::ul($this->makeRelationChildsInner($relationChilds));
				$r .= Html::divCl();
			}
		}

		return $r;
	}


	// makeRelationChildsInner
	// génère les li dans le block enfants directs
	// méthode protégé
	protected function makeRelationChildsInner(array $value):string
	{
		$r = '';
		$db = $this->db();
		$row = $this->row();
		$primary = $row->primary();

		foreach ($value as $table => $array)
		{
			if(is_string($table) && $db->hasTable($table) && is_array($array) && !empty($array))
			{
				$table = $db->table($table);
				$routeClass = $table->routeClass('general');

				foreach ($array as $colName => $primaries)
				{
					if(is_string($colName) && $table->hasCol($colName) && is_array($primaries) && !empty($primaries))
					{
						$col = $table->col($colName);
						$c = count($primaries);
						$text = $table->label().' / '.$col->label()." ($c)";
						$segment = ['table'=>$table,'filter'=>[$colName=>$primary]];

						if($table->hasPermission('view'))
						{
							$route = $routeClass::makeOverload($segment);
							$html = $route->a($text);
						}

						else
						$html = Html::span($text);

						$r .= Html::liCond($html);
					}
				}
			}
		}

		return $r;
	}


	// makeNav
	// génère la nav en haut à droite
	protected function makeNav():string
	{
		$r = '';
		$table = $this->table();

		if($table->hasPermission('nav'))
		{
			$row = $this->row();
			$general = $this->general();
			$attr = ['first'=>'hashFollow','prev'=>'hashFollow','next'=>'hashFollow','last'=>'hashFollow'];
			$specific = $this->makeSpecificNav($general,$row,'primary','highlight',$attr);
			$r .= Html::divOp('nav');

			if(!empty($specific))
			{
				if(!empty($specific['first']))
				$r .= $specific['first'];

				if(!empty($specific['prev']))
				$r .= $specific['prev'];

				if(!empty($specific['count']))
				{
					$popup = $general->makeInfoPopup(true);
					$attr = ['count',(!empty($popup))? ['withPopup','anchorCorner']:null];

					$r .= Html::divOp($attr);
					$r .= $specific['count'];
					$r .= $popup;
					$r .= Html::divCl();
				}

				if(!empty($specific['next']))
				$r .= $specific['next'];

				if(!empty($specific['last']))
				$r .= $specific['last'];

				if($table->hasPermission('insert','add'))
				$r .= SpecificAdd::makeOverload($table)->a(static::langText('common/add'));

				if($table->hasPermission('back') && !empty($specific['back']))
				$r .= $specific['back'];
			}

			$r .= Html::divCl();
		}

		return $r;
	}


	// makeForm
	// génère le formulaire
	protected function makeForm():string
	{
		$r = '';
		$dispatch = $this->isUpdateableOrDeleteable();

		$r .= Html::divOp('container');
		$r .= Html::divOp('form');

		if($dispatch === true)
		{
			$data = ['unload'=>static::langText('common/unload')];
			$r .= SpecificDispatch::makeOverload($this->segment())->formOpen(['data'=>$data]);
			$r .= $this->makeFormPrimary();
			$r .= $this->makeFormSubmit('hidden');
		}

		$r .= $this->makeFormTop();
		$r .= $this->makeFormInner();
		$r .= $this->makeFormBottom();

		if($dispatch === true)
		$r .= Html::formCl();

		$r .= Html::divCl();
		$r .= Html::divCl();

		return $r;
	}


	// makeFormPrimary
	// génère le input hidden pour la colonne primaire
	protected function makeFormPrimary():string
	{
		$r = '';
		$primary = static::db()->primary();
		$row = $this->row();
		$cell = $row->cell($primary);
		$r = $cell->formHidden(null,static::context());

		return $r;
	}


	// colCell
	// retourne la cellule à partir d'une colonne
	protected function colCell(Core\Col $col):Core\Cell
	{
		return $this->row()->cell($col);
	}


	// colCellVisible
	// retourne la cellule si elle est visible
	protected function colCellVisible(Core\Col $col):?Core\Cell
	{
		$return = null;

		$cell = $this->colCell($col);
		$session = static::session();
		if($cell->isVisible(null,$session))
		$return = $cell;

		return $return;
	}


	// makeFormWrap
	// génère un wrap label -> field pour le formulaire
	protected function makeFormWrap(Core\Cell $cell,array $replace):string
	{
		return $cell->formComplexWrap(static::getFormWrap(),'%:',$this->formWrapAttr($cell),$replace,static::context());
	}


	// formWrapAttr
	// retourne les attributs par défaut pour le formWrap
	protected function formWrapAttr(Core\Cell $cell):?array
	{
		$return = null;
		$table = $this->table();

		if(!$this->isUpdateable() || !$cell->isEditable())
		$return = ['tag'=>'div'];

		return $return;
	}


	// makeOperation
	// génère le bloc opération en haut à droite
	// possible que la table ait une opération spécifique dans ses attributs, doit être une callable
	protected function makeOperation():string
	{
		$r = '';

		$row = $this->row();
		$callback = $row->table()->attr('specificOperation');

		if(static::classIsCallable($callback))
		$r .= $callback($row);

		$r .= $this->makeViewRoute();
		$r .= $this->makeDuplicate();
		$r .= $this->makeFormSubmit('top');

		return $r;
	}


	// makeViewRoute
	// génère le lien view vers une route
	// par défaut, utilise app
	protected function makeViewRoute(string $key='app'):string
	{
		$r = '';
		$row = $this->row();
		$table = $this->table();
		$session = $this->session();

		if($table->hasPermission('viewApp') && $session->canViewRow($row))
		{
			$row = $this->row();
			$route = $row->routeSafe($key);

			if(!empty($route) && $route::hasPath() && $route::allowed())
			$r .= $route->a(static::langText('common/view'),['submit','icon','view','padLeft','target'=>false]);
		}

		return $r;
	}


	// makeDuplicate
	// génère le lien pour dupliquer la ligne si permis
	protected function makeDuplicate():string
	{
		$r = '';
		$table = $this->table();

		if($table->hasPermission('duplicate'))
		{
			$route = SpecificDuplicate::class;
			$data = ['confirm'=>static::langText('common/confirm')];
			$attr = ['icon','duplicate','padLeft','name'=>'--duplicate--','value'=>1,'data'=>$data];
			$r .= Html::submit($route::label(),$attr);
		}

		return $r;
	}


	// makeFormSubmit
	// génère le submit pour le formulaire
	protected function makeFormSubmit(string $type):string
	{
		$r = '';

		if($this->isUpdateable())
		{
			if($type === 'hidden')
			$r .= Html::submit(null,['name'=>'--modify--','value'=>1,'hidden']);

			else
			{
				$text = 'specific/modify'.ucfirst($type);
				$r .= Html::submit(static::langText($text),['name'=>'--modify--','value'=>1,'icon','modify','padLeft']);
			}
		}

		return $r;
	}


	// makeFormBottom
	// génère la partie inférieure du formulaire
	protected function makeFormBottom():string
	{
		$r = '';

		$r .= Html::divOp('bottom');
		$r .= Html::div(null,'left');
		$r .= Html::div($this->makeFormSubmit('bottom'),'center');
		$r .= Html::div($this->makeFormDelete(),'right');
		$r .= Html::divCl();

		return $r;
	}


	// makeFormDelete
	// génère le bouton submit pour la suppression
	protected function makeFormDelete():string
	{
		$r = '';

		if($this->isDeleteable())
		{
			$data = ['confirm'=>static::langText('common/confirm')];
			$attr = ['name'=>'--delete--','value'=>1,'icon','remove','padLeft','data'=>$data];
			$r .= Html::submit(static::langText('common/remove'),$attr);
		}

		return $r;
	}
}

// config
Specific::__config();
?>