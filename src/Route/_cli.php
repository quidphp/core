<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Base;
use Quid\Base\Cli;
use Quid\Core;
use Quid\Main;

// _cli
// trait that provides some initial configuration for a cli route
trait _cli
{
    // trait
    use _cliOpt;


    // config
    protected static array $configCli = [
        'match'=>[
            'cli'=>true,
            'session'=>'canAccessCli'],
        'response'=>[
            'timeLimit'=>0],
        'group'=>'cli',
        'sitemap'=>false,
        'navigation'=>false,
        'history'=>false,
        'cliHtmlOverload'=>true, // si ce n'est pas cli, les méthodes cli génèrent du html
        'exception'=>['log'=>true], // attribut par défaut pour traiter exception
        'logCron'=>Core\Row\LogCron::class, // classe pour le logCron
        'opt'=>[ // opt par défaut, output permet d'activer ou désactiver le output via cliWrite
            'output'=>true]
    ];


    // cli
    // méthode abstraite à étendre pour les routes cli
    abstract protected function cli(bool $cli);


    // trigger
    // génère le cli ou le template
    final public function trigger()
    {
        return $this->cliWrap();
    }


    // cliWrap
    // enrobe l'appel à la méthode cli
    // si le retour est un tableau, log dans logCron
    final protected function cliWrap()
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
    final public function logCron(array $data):?Core\Row
    {
        $return = null;
        $class = $this->getAttr('logCron');

        if(!empty($class))
        $return = $class::log($this,$data);

        return $return;
    }


    // logThrowable
    // permet de logger une throwable
    final protected function logThrowable(\Throwable $throwable,?array $attr=null):void
    {
        $attr = Base\Arr::replace($this->getAttr('exception'),$attr);
        Main\Exception::staticCatched($throwable,$attr);
    }


    // outputThrowable
    // gère l'affichage et le traitement des throwable en cli
    final protected function outputThrowable(string $type,\Throwable $throwable,?array $attr=null):void
    {
        $this->logThrowable($throwable,$attr);
        $this->cliWrite('neg',[$type,$throwable::class]);
        $array = Main\Exception::staticToArray($throwable);
        $this->cliWrite('neg',$array,false);
    }


    // cliWrite
    // permet d'écrire une valeur dans cli en utilisant la méthode spéciale write
    // cliWrite se désactive automatiquement si opt output est false
    final protected function cliWrite(?string $method,$data,$separator=', '):void
    {
        if($this->isOpt('output'))
        Cli::write($method,$data,$separator);
    }
}
?>