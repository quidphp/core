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
    // dynamique
    protected $amount = 0; // conserve le nombre de loop
    protected $sleep = 1; // durée de sleep pour un cli live
    protected $stdin = null; // garde une copie de la resource stdin lors


    // live
    // loop live pour cli
    // la closure after permet de mettre un stop au loop
    final protected function live(\Closure $closure,$after=null):void
    {
        if($after === true)
        $after = $this->defaultExitClosure();

        while (true)
        {
            $continue = true;
            $closure();

            if($after instanceof \Closure)
            $continue = $after();

            if($continue !== false)
            $this->sleep();

            else
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
        return function() {
            return ($this->isStdinLine(['stop','exit','quit','die'],false,true))? false:true;
        };
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
    final protected function sleep():void
    {
        $sleep = $this->getSleep();
        if(!empty($sleep))
        Base\Response::sleep($sleep);

        return;
    }


    // getSleep
    // retourne la durée de sleep
    final protected function getSleep():float
    {
        return $this->sleep;
    }


    // setSleep
    // change la durée de sleep
    final protected function setSleep(float $value):void
    {
        if(is_float($value))
        $this->sleep = $value;

        else
        static::throw();

        return;
    }
}
?>