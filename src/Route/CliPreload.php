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
use Quid\Orm;
use Quid\Routing;

// CliPreload
// abstract class for a cli route to generate a preload version of the PHP application
abstract class CliPreload extends Core\RouteAlias
{
    // config
    protected static array $config = [
        'path'=>['-preload'],
        'priority'=>8020,
        'service'=>Core\Service\PhpPreload::class,
        'compile'=>[
            'root'=>[
                'to'=>'[storage]/preload/quidphp.php',
                'from'=>[
                    Base::class=>[
                        'priority'=>[
                            '_root.php',
                            'Root.php','Assoc.php','Listing.php','Set.php','Obj.php','Str.php','Finder.php','File.php','Request.php','Sql.php','Uri.php','Path.php']],
                    Main::class=>[
                        'priority'=>[
                            '_root.php','_rootClone.php','File/_log.php','File/_storage.php','Map/_classeObj.php','Map/_obj.php','Map/_classe.php','Lang/_overload.php',
                            'Root.php','ArrObj.php','ArrMap.php','Exception.php','Map.php','Res.php','File.php','Service.php','Widget.php','File/Binary.php','File/Text.php','File/Html.php','File/Dump.php','File/Log.php','File/Serialize.php','File/Json.php']],
                    Orm::class=>[
                        'priority'=>[
                            '_tableAccess.php',
                            'Relation.php','Exception.php','Pdo.php','Operation.php','RowOperation.php','TableOperation.php','Syntax.php']],
                    Routing::class=>[]],
                'option'=>[
                    'credit'=>[Core\Boot::class,'quidCredit']]],
            'closure'=>[
                'to'=>'[storage]/preload/quidphp-closure.php',
                'from'=>[
                    Core::class=>['closure'=>true]],
                'option'=>[
                    'credit'=>[Core\Boot::class,'quidCredit'],
                    'registerClosure'=>true,
                    'bootPreload'=>true]],
            'app'=>[
                'to'=>'[storage]/preload/app.php',
                'from'=>[
                    '%key%'=>['closure'=>true,'initMethod'=>true]],
                'option'=>[
                    'registerClosure'=>true,
                    'bootPreload'=>true
                ]]],
        'option'=>[
            'initMethod'=>'__init',
            'entry'=>[
                'emptyLine'=>false,
                'comment'=>false,
                'closure'=>false,
                'initMethod'=>false]]
    ];


    // cliWrap
    // enrobe l'appel à la méthode cli
    abstract protected function cliWrap();


    // canTrigger
    // retourne vrai si la route peut être trigger
    public function canTrigger():bool
    {
        return parent::canTrigger() && !static::boot()->isPreload();
    }


    // cli
    // méthode pour générer le script PHP concatener
    final protected function cli(bool $cli)
    {
        $this->cliWrite('neutral',static::label());
        $return = $this->preload();

        return $return;
    }


    // getService
    // retourne le service de concatenation
    final protected function getService(?array $option=null):Main\Service
    {
        $service = $this->getAttr('service');

        if(!empty($service))
        $return = new $service($option);

        return $return;
    }


    // getTarget
    // retourne un fichier target
    final protected function getTarget(array $array):Main\File\Php
    {
        $return = null;
        $target = $array['to'] ?? null;

        if(is_string($target) && !empty($target))
        $return = Core\File\Php::newCreate($target);

        return $return;
    }


    // getFrom
    // retourne les namespaces à compiler dans un tableau
    final protected function getFrom(array $array):array
    {
        $return = null;
        $from = $array['from'] ?? null;

        if(is_array($from) && !empty($from))
        {
            $name = static::boot()->name(true);
            $return = $from;
            $return = Base\Arrs::keysReplace(['%key%'=>$name],$return);
            $return = Base\Arrs::valuesReplace(['%key%'=>$name],$return);
        }

        return $return;
    }


    // getOption
    // retourne les options pour une compilation
    final protected function getOption(array $array):array
    {
        $return = null;
        $base = $this->getAttr('option');
        $option = $array['option'] ?? null;
        $option = Base\Arrs::replace($base,$option);
        $return = Base\Call::dig(true,$option);

        return $return;
    }


    // preload
    // génère le script PHP
    final protected function preload():array
    {
        $return = [];
        $compile = (array) $this->getAttr('compile');

        foreach ($compile as $key => $array)
        {
            $target = $this->getTarget($array);
            $from = $this->getFrom($array);
            $option = $this->getOption($array);
            ['method'=>$method,'value'=>$value] = $this->compileOne($target,$from,$option);

            $this->cliWrite($method,$value,false);
            $return[] = [$method=>$value];
        }

        return $return;
    }


    // compileOne
    // permet de faire une compilation
    final protected function compileOne(Main\File $target,array $from,array $option):array
    {
        $return = ['method'=>'neg','value'=>'x'];
        $service = $this->getService($option);
        $preload = $service->trigger($from,$target);

        if(!empty($preload))
        {
            $target->overwrite($preload);
            $return['method'] = 'pos';
            $return['value'] = [$target->path()=>$target->size()];
        }

        return $return;
    }
}

// init
CliPreload::__init();
?>