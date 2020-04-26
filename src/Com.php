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
use Quid\Main;

// com
// extended class that provides the logic to store communication messages
class Com extends Main\Com
{
    // trait
    use _bootAccess;


    // config
    public static array $config = [];


    // lang
    // retourne l'objet lang, peut utiliser celui dans inst
    // envoie une exception si introuvable
    final protected function lang(?Main\Lang $return=null):Main\Lang
    {
        if($return === null)
        {
            $boot = static::bootReady();
            if(!empty($boot))
            $return = $boot->lang();
        }

        return parent::lang($return);
    }
}

// init
Com::__init();
?>