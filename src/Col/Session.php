<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;

// session
// class for a column which should store current session id
class Session extends EnumAlias
{
    // config
    protected static array $config = [
        'visible'=>['validate'=>'notEmpty'],
        'required'=>false,
        'complex'=>'div',
        'inRelation'=>false // custom, n'a pas besoin d'être dans la relation
    ];


    // onCommit
    // retourne le id de la session sur insertion ou sur update
    // note: retourne null si le storage de session n'est pas une row de base de données
    final protected function onCommit($value,?Core\Cell $cell=null,array $row,array $option):?Core\Row
    {
        $return = null;
        $boot = static::bootReady();

        if(!empty($boot))
        {
            $session = $boot->session();
            $storage = $session->storage();

            if($storage instanceof Core\Row)
            $return = $storage;
        }

        return $return;
    }
}

// init
Session::__init();
?>