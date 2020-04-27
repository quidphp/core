<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core;
use Quid\Orm;

// lang
// extended class for a collection object containing language texts and translations
class Lang extends Orm\Lang
{
    // trait
    use _bootAccess;


    // config
    protected static array $config = [
        'path'=>[ // chemin pour des types de texte précis liés à des méthodes
            'bootLabel'=>'label',
            'bootDescription'=>'description',
            'typeLabel'=>'relation/contextType',
            'envLabel'=>'relation/contextEnv',
            'routeLabel'=>'route/label',
            'routeDescription'=>'route/description']
    ];


    // bootLabel
    // retourne le label du boot courant
    final public function bootLabel(?string $lang=null,?array $option=null):?string
    {
        return $this->text($this->getPath('bootLabel'),null,$lang,$option);
    }


    // bootDescription
    // retourne la description du boot courant
    final public function bootDescription(?string $lang=null,?array $option=null):?string
    {
        return $this->safe($this->getPath('bootDescription'),null,$lang,$option);
    }


    // typeLabel
    // retourne le label du type de context
    final public function typeLabel(string $type,?string $lang=null,?array $option=null):?string
    {
        return $this->def($this->getPath('typeLabel',$type),null,$lang,$option);
    }


    // envLabel
    // retourne le label de l'env de context
    final public function envLabel(string $env,?string $lang=null,?array $option=null):?string
    {
        return $this->text($this->getPath('envLabel',$env),null,$lang,$option);
    }


    // routeLabel
    // retourne le label d'une route
    // si la route n'existe pas, utilise def
    final public function routeLabel(string $route,?string $lang=null,?array $option=null):?string
    {
        return $this->def($this->getPath('routeLabel',$route),null,$lang,$option);
    }


    // routeDescription
    // retourne la description d'une route
    // par défaut, la méthode error n'est pas lancé et retournera null si aucune description
    final public function routeDescription(string $route,?array $replace=null,?string $lang=null,?array $option=null):?string
    {
        return $this->safe($this->getPath('routeDescription',$route),$replace,$lang,$option);
    }
}

// init
Lang::__init();
?>