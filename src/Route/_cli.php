<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Base;
use Quid\Core;

// _cli
// trait that provides some initial configuration for a cli route
trait _cli
{
    // config
    public static $configCli = [
        'match'=>array(
            'cli'=>true,
            'role'=>array('>='=>'admin')),
        'response'=>[
            'timeLimit'=>0],
        'group'=>'cli',
        'sitemap'=>false,
        'navigation'=>false,
        'cliHtmlOverload'=>true, // si ce n'est pas cli, les méthodes cli génèrent du html
        'logCron'=>Core\Row\LogCron::class // classe pour le logCron
    ];

    
    // cli
    // méthode abstraite à étendre pour les routes cli
    abstract protected function cli(bool $cli);
    
    
    // trigger
    // génère le cli ou le template
    public function trigger()
    {
        return $this->cliWrap();
    }


    // cliWrap
    // enrobe l'appel à la méthode cli
    // si le retour est un tableau, log dans logCron
    protected function cliWrap()
    {
        $return = $this->cli(Base\Server::isCli());
        
        if(is_array($return))
        {
            $this->logCron($return);
            $return = null;
        }
        
        return $return;
    }
    
    
    // logCron
    // permet de logger des données dans la table log cron
    public function logCron(array $data):?Core\Row
    {
        $return = null;
        $class = $this->getAttr('logCron');

        if(!empty($class))
        $return = $class::log($this,$data);

        return $return;
    }
}
?>