<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\Cell;
use Quid\Core;

// enum
// class to manage a cell containing a single relation (enum)
class Enum extends RelationAlias
{
    // config
    protected static array $config = [];


    // cast
    // pour cast de cellule relation retourne get plutôt que value
    final public function _cast()
    {
        return $this->get();
    }


    // relation
    // gère le retour d'une valeur de relation pour enum
    // cache est true
    final public function relation(?array $option=null)
    {
        return $this->colRelation()->get($this,false,true,$option);
    }


    // relationRow
    // retourne la valeur de la relation sous forme de row, peut retourner null
    // envoie une exception si le type de relation n'est pas table
    final public function relationRow(?array $option=null):?Core\Row
    {
        return $this->colRelation()->getRow($this,$option);
    }
}

// init
Enum::__init();
?>