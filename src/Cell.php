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
use Quid\Routing;

// cell
// extended class to represent an existing cell within a row
class Cell extends Orm\Cell
{
    // trait
    use _accessAlias;
    use Routing\_attrRoute;


    // config
    protected static array $config = [];


    // hasImage
    // retourne vrai si la colonne peut potentiellement fournir une image
    public function hasImage():bool
    {
        return $this->isMedia();
    }


    // isMediaOrLike
    // retourne vrai si la colonne contient un media ou similaire à média
    public function isMediaOrLike():bool
    {
        return $this->isMedia();
    }


    // generalExcerptMin
    // retourne la longueur de l'excerpt pour general
    final public function generalExcerptMin():?int
    {
        return $this->col()->generalExcerptMin();
    }
}

// init
Cell::__init();
?>