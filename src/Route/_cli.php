<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
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
    // config
    protected static array $configCli = [
        'priority'=>850,
        'match'=>[
            'cli'=>true,
            'role'=>['>='=>'admin']],
        'response'=>[
            'timeLimit'=>0],
        'group'=>'cli',
        'sitemap'=>false,
        'navigation'=>false,
        'history'=>false,
        'cliHtmlOverload'=>true, // si ce n'est pas cli, les méthodes cli génèrent du html
        'exception'=>['kill'=>false,'output'=>false,'cleanBuffer'=>false], // attribut par défaut pour traiter exception
        'logCron'=>Core\Row\LogCron::class // classe pour le logCron
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


    // outputException
    // gère l'affichage et le traitement des exceptions en cli
    final protected function outputException(string $type,\Exception $exception,?array $attr=null):void
    {
        $attr = Base\Arr::replace($this->getAttr('exception'),$attr);
        Main\Exception::staticCatched($exception,$attr);

        $topArray = [$type,Base\Datetime::sql(),get_class($exception)];
        $top = implode(', ',$topArray);
        $cli = [
            'message'=>$exception->getMessage(),
            'file'=>$exception->getFile(),
            'line'=>$exception->getLine(),
        ];

        Cli::neg($top);
        Cli::neg($cli);

        return;
    }


    // cliWrite
    // permet d'écrire une valeur dans cli en utilisant la méthode spéciale write
    final protected function cliWrite(string $method,$data,$separator=', '):void
    {
        Cli::write($method,$data,$separator);

        return;
    }
}
?>