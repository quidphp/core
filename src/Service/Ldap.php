<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Service;
use Quid\Main;

// ldap
// class that grants some methods to connect to a ldap server
class Ldap extends Main\Service
{
    // config
    public static $config = [
        'ping'=>2,
        'host'=>null,
        'port'=>389
    ];


    // dynamic
    protected $res = null; // garde une copie de la resource ldap


    // host
    // retourne le host de l'objet ldap
    final public function host():string
    {
        return $this->getAttr('host');
    }


    // port
    // retourne le port de l'objet ldap
    final public function port():int
    {
        return $this->getAttr('port');
    }


    // res
    // retourne une resource lien ldap
    // envoie une exception en cas d'erreur de création
    final public function res(bool $ping=false)
    {
        $return = $this->res;
        $attr = $this->attr();

        if(empty($return))
        {
            $host = $this->host();
            $port = $this->port();

            if($ping === true && is_int($attr['ping']))
            static::checkPing($host,$port,$attr['ping']);

            $return = ldap_connect($host,$port);
        }

        if(!is_resource($return))
        static::throw('invalidLdapResource');

        return $return;
    }


    // login
    // tente une connexion au serveur ldap
    // retourne vrai ou faux
    final public function login(string $username,string $password):bool
    {
        $return = false;
        $ldap = $this->res(true);

        if(!empty($ldap))
        $return = ldap_bind($ldap,$username,$password);

        return $return;
    }
}

// init
Ldap::__init();
?>