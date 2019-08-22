<?php
declare(strict_types=1);
namespace Quid\Core\Cms;

// _export
trait _export
{
	// trait
	use _general;
	
	
	// onBefore
	// vérifie que l'utilisateur a la permission pour exporter la table et qu'il y a des rows à exporter
	protected function onBefore()
	{
		$return = false;
		$table = $this->table();
		
		if(!empty($table) && $table->hasPermission('export'))
		{
			$sql = $this->sql();
			$total = $sql->triggerRowCount();
			
			if($total > 0)
			$return = true;
		}
		
		return $return;
	}
	
	
	// generalSegments
	// retourne les segments à utiliser pour la création de l'objet sql de generalExport
	protected function generalSegments():array 
	{
		return $this->segment(['order','direction','filter','in','notIn']);
	}
	
	
	// isEncoding
	// retourne vrai si la valeur est un encodage valide
	public static function isEncoding($value):bool 
	{
		return (is_string($value) && in_array($value,static::getEncoding(),true))? true:false;
	}
	
	
	// getEncoding
	// retourne les encodages permis
	public static function getEncoding():array
	{
		return ['utf8','latin1'];
	}
	
	
	// defaultEncoding
	// retourne l'encodage par défaut à utiliser
	public static function defaultEncoding():string 
	{
		return current(static::getEncoding());
	}
}
?>