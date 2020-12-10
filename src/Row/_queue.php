<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
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


    // config
    protected static array $configQueue = [
        'queuePrepareMethod'=>'prepareQueueData' // méthode à utiliser pour préparer les données
    ];


    // getQueued
    // retourne un objet rows avec toutes les rows queued
    abstract public static function getQueued(?int $limit=null):?Main\Map;


    // queue
    // crée une nouvelle entrée du queue
    final public static function queue(...$values):?Main\Contract\Queue
    {
        $method = static::$config['queuePrepareMethod'] ?? static::throw('invalidQueuePrepareMethod');
        $data = static::$method(...$values);
        return ($data !== null)? static::safeInsert($data):null;
    }
}
?>