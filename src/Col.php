<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Orm;
use Quid\Routing;

// col
// extended class to represent an existing column within a table
class Col extends Orm\Col
{
    // trait
    use _accessAlias;
    use Routing\_attrRoute;


    // config
    public static $config = [
        'route'=>null // permet de définir la route à utiliser en lien avec complex
    ];


    // generalCurrentLang
    // retourne vrai si le nom de la colonne a un pattern de la langue courante
    final public static function generalCurrentLang(self $col):bool
    {
        $return = false;
        $boot = static::boot();
        $langCode = $col->langCode();
        
        if($boot->lang()->currentLang() === $langCode)
        $return = true;

        return $return;
    }
}

// init
Col::__init();
?>