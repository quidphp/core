<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Base\Html;

// _common
trait _common
{
	// isTableTop
	// retourne vrai si la table courante est dans le tableau
	// la page n'a pas nécessairement une table
	protected function isTableTop(array $value) 
	{
		$return = false;
		
		if(method_exists($this,'table'))
		{
			$table = $this->table()->name();
			
			if(!empty($table) && in_array($table,$value,true))
			$return = true;
		}
		
		return $return;
	}
	
	
	// tableHiddenInput
	// génère le input hidden pour table
	public function tableHiddenInput():string
	{
		return Html::inputHidden($this->table(),static::tableInputName());
	}


	// tableInputName
	// retourne le nom du input pour table
	public static function tableInputName():string 
	{
		return '-table-';
	}


	// panelInputName
	// retourne le nom du input pour panel
	public static function panelInputName():string 
	{
		return '-panel-';
	}
	
	
	// authorLink
	// retourne le lien web pour l'auteur
	public static function authorLink():string 
	{
		return Html::a(static::langText('author/uri'),static::langText('author/name'));
	}
	
	
	// authorEmail
	// retourne le lien email pour l'auteur
	public static function authorEmail():string 
	{
		return Html::a(static::langText('author/email'),true);
	}
}
?>