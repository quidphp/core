<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Base;

// _cliOpt
// trait that provides some methods to manage opt in a cli application
trait _cliOpt
{
    // config
    protected static array $configCliOpt = [
        'opt'=>[] // les opt par défaut
    ];


    // dynamique
    protected array $opt = []; // opt active pour la route


    // onGetOpt
    // méthode à étendre pour traiter la valeur après un getOpt
    protected function onGetOpt(string $key,$value)
    {
        return $value;
    }


    // onSetOpt
    // méthode à étendre pour traiter la valeur avant de set la opt
    protected function onSetOpt(string $key,$value,$initial)
    {
        return $value;
    }


    // processBefore
    // étend le processBefore de core/route
    // ajoute support pour les opts
    protected function processBefore():void
    {
        parent::processBefore();
        $this->prepareOpt();
    }


    // prepareOpt
    // prépare les opt, fait le merge entre les défaut et les paramètres query
    final protected function prepareOpt():void
    {
        $default = $this->defaultOpt();
        $current = $this->request()->queryArray();
        $opt = Base\Arr::replace($default,$current);

        foreach ($opt as $key => $value)
        {
            $this->setOpt($key,$value);
        }
    }


    // checkOptKey
    // envoie une excpetion si l'opt n'est pas défini dans les défaut
    final protected function checkOptKey(string $key):void
    {
        $default = $this->defaultOpt();
        if(!Base\Arr::keyExists($key,$default))
        static::throw($key,'notDefined');
    }


    // checkOptValue
    // envoie une excpetion si la valeur de opt n'est pas scalar
    final protected function checkOptValue($value):void
    {
        if(!is_scalar($value))
        static::throw($value,'notScalar');
    }


    // isDefaultOptBool
    // retourne vrai si la valeur par défaut d'une opt est un bool
    final public function isDefaultOptBool(string $key):bool
    {
        return is_bool(Base\Arr::get($key,$this->defaultOpt()));
    }


    // defaultOpt
    // retourne les opt par défaut
    final public function defaultOpt():array
    {
        return $this->getAttr('opt') ?? [];
    }


    // isOpt
    // retourne vrai si une opt est true ou similaire à true
    // envoie une exception si n'existe pas
    final public function isOpt(string $key):bool
    {
        return $this->getOpt($key,true);
    }


    // hasOpt
    // retourne vrai si une opt est défini
    final public function hasOpt(string $key):bool
    {
        return Base\Arr::keyExists($key,$this->opt());
    }


    // getOpt
    // retourne la valeur d'une opt
    // peut retourner la opt sous forme de true ou false
    final public function getOpt(string $key,bool $bool=false)
    {
        $this->checkOptKey($key);
        $value = Base\Arr::get($key,$this->opt());
        $return = $this->onGetOpt($key,$value);

        if($bool === true)
        $return = ($return == true);

        return $return;
    }


    // setOpt
    // permet de changer la valeur d'une opt
    final public function setOpt(string $key,$initial):void
    {
        $this->checkOptKey($key);

        $extraBool = $this->isDefaultOptBool($key);
        $value = Base\Obj::cast($initial);
        $value = Base\Scalar::castMore($value);
        if($extraBool === true)
        $value = Base\Boolean::cast($value,true);

        $value = $this->onSetOpt($key,$value,$initial);
        $this->checkOptValue($value);

        Base\Arr::setRef($key,$value,$this->opt);
    }


    // opt
    // retourne le tableau des opt actives
    final public function opt():array
    {
        return $this->opt;
    }
}
?>