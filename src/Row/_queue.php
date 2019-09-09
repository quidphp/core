<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Row;
use Quid\Main;

// _queue
// trait that adds queuing-related methods to a row
trait _queue
{
    // trait
    use Main\_queue;
    use _new;


    // getQueued
    // retourne un objet rows avec toutes les rows queued
    abstract public static function getQueued(?int $limit=null):?Main\Map;


    // queue
    // créer une nouvelle entrée dans la queue
    public static function queue(...$values):?Main\Contract\Queue
    {
        return static::new(...$values);
    }
}
?>