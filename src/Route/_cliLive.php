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

// _cliLive
// trait that provides some methods for a cli script which loops
trait _cliLive
{
    // config
    protected static array $configCliLive = [
        'dbHistory'=>false // désactive db history, sinon le RAM va sauter la limite
    ];


    // dynamique
    protected int $amount = 0; // conserve le nombre de loop
    protected $stdin = null; // garde une copie de la resource stdin lors


    // getSleep
    // retourne la durée de sleep
    protected function getSleep():int
    {
        return 1;
    }


    // isLive
    // retourne vrai si la route est présentement live (en cli)
    final protected function isLive(bool $cli):bool
    {
        return $cli === true && !$this->request()->isQuery('once');
    }


    // live
    // loop live pour cli
    // la closure exit permet de mettre un terme au loop, si vide utilise la méthode par défaut
    // possible de terminer le boot avant d'enclencher le loop
    final protected function live(\Closure $closure,?\Closure $exit=null,bool $teardown=false):void
    {
        if($teardown === true)
        static::boot()->teardown();

        if(empty($exit))
        $exit = $this->defaultExitClosure();

        while (true)
        {
            $continue = $exit();

            if($continue === true)
            {
                try
                {
                    $result = $closure();

                    if(is_array($result))
                    $this->logCron($result);

                    elseif(is_bool($result))
                    $continue = $result;
                }

                catch (\Exception $e)
                {
                    dd($e->getMessage());
                    $this->outputException('Interruption',$e);
                }
            }

            if($continue === true)
            $continue = $exit();

            if($continue === true)
            $continue = $this->sleep($exit);

            if($continue === false)
            break;
        }

        return;
    }


    // stdin
    // retourne la resource stdin
    // emmagasine dans une propriété si non initialisé
    final protected function stdin(bool $block=true)
    {
        $return = $this->stdin;

        if(!is_resource($return))
        $this->stdin = $return = Base\Res::stdin(['block'=>$block]);

        return $return;
    }


    // isStdinLine
    // retourne vrai si la dernière ligne est la valeur en argument
    final protected function isStdinLine($value,bool $block=true,bool $lower=false):bool
    {
        return Base\Cli::isInLine($value,$this->stdin($block),$lower);
    }


    // stdinLine
    // retourne la dernière ligne du stdin
    // par défaut tout est remené en lowerCase
    final protected function stdinLine(bool $block=true,bool $lower=false):?string
    {
        return Base\Cli::inLine($this->stdin($block),$lower);
    }


    // defaultExitClosure
    // retourne la closure à utiliser pour terminer le script cli
    // fin du loop si stop, exit, quit et die
    final protected function defaultExitClosure():\Closure
    {
        return fn() => $this->isStdinLine(['stop','exit','quit','die','bye','kill'],false,true)? false:true;
    }


    // amountIncrement
    // incrément la propriété amount
    final protected function amountIncrement():void
    {
        $this->amount++;

        return;
    }


    // amount
    // retourne la propriété amount
    final protected function amount():int
    {
        return $this->amount;
    }


    // sleep
    // le script dort
    // retourne false s'il faut break
    final protected function sleep(\Closure $exit):bool
    {
        $return = true;
        $sleep = $this->getSleep();

        while ($sleep > 0)
        {
            Base\Response::sleep(1);
            $sleep--;

            $return = $exit();

            if($return === false)
            break;
        }

        return $return;
    }
}
?>