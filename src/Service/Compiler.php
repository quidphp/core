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
use Quid\Main;
use Quid\Base;
use Quid\Core;

// compiler
// class to compile or concatenate js, scss and php assets
class Compiler extends Main\Service
{
    // config
    public static $config = [
        'js'=>null,
        'jsOption'=>null,
        'scss'=>null,
        'scssOption'=>null,
        'php'=>null
    ];
    
    
    // trigger
    // lance la compilation de js, scss et php
    final public function trigger():array
    {
        $return = array();
        $return['js'] = $this->triggerJs();
        $return['scss'] = $this->triggerScss();
        $return['php'] = $this->triggerPhp();

        return $return;
    }
    
    
    // triggerJs
    // permet de concatener plusieurs fichiers et directoires js
    final protected function triggerJs():?Main\Files
    {
        $return = null;
        $js = $this->getAttr('js');
        $jsOption = $this->getAttr('jsOption');
        
        if(is_array($js) && !empty($js))
        {
            $class = Core\File\Js::getOverloadClass();
            $return = $class::concatenateMany($js,$jsOption);
        }
        
        return $return;
    }
    
    
    // triggerScss
    // permet de compiler plusieurs fichiers et directoires scss
    final protected function triggerScss():?Main\Files
    {
        $return = null;
        $scss = $this->getAttr('scss');
        $scssOption = $this->getAttr('scssOption');
        
        if(is_array($scss) && !empty($scss))
        {
            $class = Core\File\Css::getOverloadClass();
            $return = $class::compileMany($scss,$scssOption);
        }
        
        return $return;
    }
    
    
    // triggerPhp
    // permet de concatener plusieurs namespaces php
    final protected function triggerPhp():?Main\Files
    {
        $return = null;
        $php = $this->getAttr('php');
        
        if(is_array($php) && !empty($php))
        {
            $class = Core\File\Php::getOverloadClass();
            $return = $class::concatenateMany($php);
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