<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\Service;
use Quid\Base;
use Quid\Core;
use Quid\Main;

// compiler
// class to compile or concatenate js and css assets
class Compiler extends Main\Service
{
    // config
    public static $config = [
        'css'=>null, // tableau des compilations css
        'cssOption'=>null, // option supplémentaires à utiliser pour toutes les compilations css
        'js'=>null, // tableau des compilations js
        'jsOption'=>null // option supplémentaires à utiliser pour toutes les compilations js
    ];


    // trigger
    // lance la compilation de js et css
    final public function trigger():array
    {
        $return = [];

        $return['css'] = $this->triggerOne(Core\File\Css::class);
        $return['js'] = $this->triggerOne(Core\File\Js::class);

        return $return;
    }


    // triggerOne
    // permet de compiler plusieurs fichiers et directoires d'un type
    final protected function triggerOne(string $class):?Main\Files
    {
        $return = null;
        $type = $class::className(true);
        $config = $this->getAttr($type);
        $option = (array) $this->getAttr($type.'Option');

        if(is_array($config) && !empty($config))
        $return = $this->loop($class::getOverloadClass(),$config,$option);

        return $return;
    }


    // loop
    // loop à travers toutes les concatenations pour un type
    final protected function loop(string $class,array $value,array $option):Main\Files
    {
        $return = Main\Files::newOverload();

        foreach ($value as $key => $array)
        {
            if(is_string($key) && is_array($array) && !empty($array))
            {
                $array = Base\Arrs::replace($option,$array);
                $file = $this->loopOne($class,$key,$array);

                if(!empty($file))
                $return->add($file);
            }
        }

        return $return;
    }


    // loopOne
    // loop à travers une demande de concatenation pour un type
    // si overwrite est true, écrase le fichier dans tous les cas
    // si overwrite est null, écrase le fichier seulement si la date de modifcation des sources est plus récente
    final protected function loopOne(string $class,string $key,array $array):?Main\File
    {
        $return = null;
        $extension = $class::concatenateExtension();

        $to = $array['to'] ?? null;
        $from = $array['from'] ?? null;
        $overwrite = $array['overwrite'] ?? null;

        if(is_string($to) && !empty($to) && !empty($from))
        {
            if($overwrite === true || Base\Dir::isOlderThanFrom($to,$from,true,['visible'=>true,'extension'=>$extension]))
            {
                $keys = ['to','from','overwrite'];
                $option = Base\Arr::keysStrip($keys,$array);

                $return = Main\File::newCreate($to);
                $return->concatenateFrom($from,$option);
            }
        }

        return $return;
    }


    // staticTrigger
    // méthode statique pour créer l'objet et lancer le trigger
    // retourne un array
    final public static function staticTrigger(?array $attr=null):array
    {
        $return = null;
        $compiler = new static(__METHOD__,$attr);
        $return = $compiler->trigger();

        return $return;
    }
}

// init
Compiler::__init();
?>