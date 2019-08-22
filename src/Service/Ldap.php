<?php
declare(strict_types=1);
namespace Quid\Core\Service;
use Quid\Core;

// ldap
class Ldap extends Core\ServiceAlias
{
	// config
	public static $config = array(
		'option'=>array(
			'ping'=>2,
			'host'=>null,
			'port'=>389) // port par défaut
	);
	
	
	// dynamic
	protected $res = null; // garde une copie de la resource ldap
	
	
	// host
	// retourne le host de l'objet ldap
	public function host():string 
	{
		return $this->getOption('host');
	}
	
	
	// port
	// retourne le port de l'objet ldap
	public function port():int 
	{
		return $this->getOption('port');
	}
	
	
	// res
	// retourne une resource lien ldap
	// envoie une exception en cas d'erreur de création
	public function res(bool $ping=false) 
	{
		$return = $this->res;
		$option = $this->option();
		
		if(empty($return))
		{
			$host = $this->host();
			$port = $this->port();
			
			if($ping === true && is_int($option['ping']))
			static::checkPing($host,$port,$option['ping']);
			
			$return = ldap_connect($host,$port);
		}
		
		if(!is_resource($return))
		static::throw('invalidLdapResource');
		
		return $return;
	}
	
	
	// login
	// tente une connexion au serveur ldap
	// retourne vrai ou faux
	public function login(string $username,string $password):bool 
	{
		$return = false;
		$ldap = $this->res(true);
		
		if(!empty($ldap))
		$return = ldap_bind($ldap,$username,$password);
		
		return $return;
	}
}

// config
Ldap::__config();
?>