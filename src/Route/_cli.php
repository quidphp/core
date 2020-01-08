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
use Quid\Core;

// _cli
// trait that provides some initial configuration for a cli route
trait _cli
{
    // config
    public static $configCli = [
        'priority'=>800,
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


    // clearValue
    // utilisé par clearCache et clearLog pour effacer une valeur
    // peut être un fichier, directoire, symlink ou table de données
    final protected function clearValue(string $value):array
    {
        $return = ['method'=>'neg','value'=>null];
        $value = Base\Finder::shortcut($value);
        $return['value'] = "? $value";

        if(is_a($value,Core\Row::class,true))
        {
            $db = static::db();

            if($db->hasTable($value))
            {
                $table = $db->table($value);
                $option = ['log'=>false];

                if($table->truncate($option) === true)
                $return = ['method'=>'pos','value'=>"x $value"];
            }
        }

        else
        {
            if(Base\Symlink::is($value) && Base\Symlink::unset($value))
            $return = ['method'=>'pos','value'=>"- $value"];

            elseif(Base\Dir::is($value) && Base\Dir::emptyAndUnlink($value))
            $return = ['method'=>'pos','value'=>"x $value"];
        }

        return $return;
    }
}
?>