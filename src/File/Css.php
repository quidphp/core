<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;
use Quid\Base;
use Quid\Core;
use Quid\Main;

// css
// class for a css or scss file
class Css extends Main\File\Css
{
    // config
    protected static array $config = [
        'concatenateService'=>Core\Service\ScssPhp::class,
        'importPathMin'=>10 // cette variable défini après quel clé l'import path est considéré
    ];


    // getConcatenatorOption
    // retourne les options pour le concatenateur
    protected function getConcatenatorOption(array $values,array $option):?array
    {
        $return = parent::getConcatenatorOption($values,$option);

        $return['callable'] = function(string $value) use($values,$option) {
            $service = $this->concatenateService();
            $option['variables'] = $this->getScssVariables();
            $option['importPaths'] = $this->getImportPaths($values);

            return $service::staticTrigger($value,$option);
        };

        return $return;
    }


    // getImportPaths
    // retourne les chemins d'importations à partir du tableau de valeur
    final protected function getImportPaths(array $values):array
    {
        $return = [];
        $min = $this->getAttr('importPathMin');

        foreach ($values as $key => $value)
        {
            if(!is_string($value) || Base\Finder::is($value))
            {
                if(is_string($value) && Base\Dir::is($value))
                $dirname = $value;

                else
                {
                   $value = static::new($value);
                   $dirname = $value->dirname();
                }

                if($key >= $min && !in_array($dirname,$return,true))
                $return[] = $dirname;
            }
        }

        return $return;
    }


    // getScssVariables
    // génère un tableau de variable à injecter dans la feuille de style scss
    final protected static function getScssVariables():array
    {
        $return = [];

        foreach (Base\Finder::allShortcuts() as $key => $value)
        {
            if(!Base\Lang::is($value))
            $value = Base\Finder::normalize($value);
            $key = 'finder'.ucfirst($key);

            $return[$key] = $value;
        }

        foreach (Base\Uri::allShortcuts() as $key => $value)
        {
            if(!Base\Lang::is($value))
            $value = Base\Uri::output($value);
            $key = 'uri'.ucfirst($key);

            $return[$key] = $value;
        }

        return $return;
    }
}

// init
Css::__init();
?>