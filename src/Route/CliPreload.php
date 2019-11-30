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
use Quid\Orm;
use Quid\Routing;
use Quid\Test;

// CliPreload
// abstract class for the cli route to generate a preload version of the PHP application
abstract class CliPreload extends Core\RouteAlias
{
    // config
    public static $config = [
        'path'=>['-preload'],
        'target'=>'[storage]/preload.php',
        'service'=>Core\Service\PhpConcatenator::class,
        'from'=>[
            Base::class=>[
                'priority'=>[
                    '_root.php',
                    'Root.php','Assoc.php','Listing.php','Set.php','Obj.php','Str.php','Finder.php','File.php','Request.php','Sql.php','Uri.php','Path.php']],
            Test\Base::class=>[],
            Main::class=>[
                'priority'=>[
                    '_root.php','_rootClone.php','File/_log.php','File/_storage.php','Map/_classeObj.php','Map/_obj.php','Map/_classe.php','Lang/_overload.php',
                    'Root.php','ArrObj.php','ArrMap.php','Exception.php','Map.php','Res.php','File.php','Service.php','Widget.php','File/Binary.php','File/Text.php','File/Html.php','File/Dump.php','File/Log.php','File/Serialize.php','File/Json.php']],
            Test\Main::class=>[],
            Orm::class=>[
                'priority'=>[
                    '_tableAccess.php',
                    'Relation.php','Exception.php','Pdo.php','Operation.php','RowOperation.php','TableOperation.php','Syntax.php']],
            Test\Orm::class=>[],
            Routing::class=>[],
            Test\Routing::class=>[],
            Core::class=>['closure'=>true],
            Test\Core::class=>[],
            //'%key%'=>['closure'=>true],
            ],
        'option'=>[
            'credit'=>[Core\Boot::class,'quidCredit'],
            'registerClosure'=>true,
            'bootPreload'=>true,
            'initMethod'=>'__init']
    ];


    // cliWrap
    // enrobe l'appel à la méthode cli
    abstract protected function cliWrap();


    // cli
    // méthode pour générer le script PHP concatener
    final protected function cli(bool $cli)
    {
        Cli::neutral(static::label());
        $return = $this->buildPreload();

        return $return;
    }


    // getTarget
    // retourne le fichier target
    final protected function getTarget():Main\File\Php
    {
        $return = null;
        $attr = $this->getAttr('target');

        if(!empty($attr))
        $return = Core\File\Php::newCreate($attr);

        return $return;
    }


    // getService
    // retourne le service de concatenation
    final protected function getService(?array $option=null):Main\Service
    {
        $service = $this->getAttr('service');

        if(!empty($service))
        $return = new $service(__METHOD__,$option);

        return $return;
    }


    // getFrom
    // retourne les namespaces à compiler dans un tableau
    final protected function getFrom():array
    {
        $return = $this->getAttr('from');

        if(is_array($return) && !empty($return))
        {
            $name = static::boot()->name(true);
            $return = Base\Arrs::keysReplace(['%key%'=>$name],$return);
            $return = Base\Arrs::valuesReplace(['%key%'=>$name],$return);
        }

        return $return;
    }


    // buildPreload
    // génère le script PHP
    final protected function buildPreload():array
    {
        $return = [];
        $method = 'neg';
        $value = 'x';

        $target = $this->getTarget();
        $from = $this->getFrom();
        $option = (array) $this->getAttr('option');
        $option = Base\Call::digStaticMethod($option);
        $service = $this->getService($option);
        $preload = $service->trigger($from,$target);

        if(!empty($preload))
        {
            $target->overwrite($preload);
            $method = 'pos';
            $value = [$target->path(),$target->size()];
        }

        Cli::$method($value);
        $return[] = [$method=>$value];

        return $return;
    }
}

// init
CliPreload::__init();
?>