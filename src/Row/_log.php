<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Row;
use Quid\Main;

// _log
// trait that adds log-related methods to a row
trait _log
{
    // trait
    use Main\_log;


    // configLog
    protected static array $configLog = [
        'search'=>false,
        'reservePrimary'=>false,
        'permission'=>[
            '*'=>['insert'=>true,'update'=>false,'delete'=>true],
            'nobody'=>['insert'=>true],
            'admin'=>['update'=>false]],
        'deleteTrim'=>1000 // custom
    ];


    // log
    // crée une nouvelle entrée du log maintenant
    // lance le logAfter après
    final public static function log(...$values):?Main\Contract\Log
    {
        return static::logStrictAfter(false,true,...$values);
    }


    // logStrictAfter
    // méthode utilisé pour faire le log
    // gère les options strict et after
    final public static function logStrictAfter(bool $strict,bool $after,...$values):?Main\Contract\Log
    {
        $return = null;
        $data = static::prepareLogData(...$values);

        if($data !== null)
        {
            static::logHad();
            $method = ($strict === true)? 'insert':'safeInsert';
            $return = static::$method($data);

            if(!empty($return) && $after === true)
            static::logAfter();
        }

        if($strict === true && empty($return))
        static::throw('couldNotLog');

        return $return;
    }


    // logTrim
    // trim la table de log pour la valeur paramétré dans static config
    // le maximum de vérification sont faites pour ne pas qu'il y ait d'erreurs de générer dans la méthode
    final public static function logTrim():?int
    {
        $return = null;
        $trim = static::$config['deleteTrim'];

        if(array_key_exists('deleteTrim',static::$config) && is_int($trim))
        {
            $table = static::safeTableFromFqcn();

            if(!empty($table) && $table->hasPermission('delete'))
            {
                $db = $table->db();
                $currentLog = $db->getAttr('log');
                $db->off();
                $return = $table->deleteTrim($trim);
                $db->on();
            }
        }

        return $return;
    }


    // setDeleteTrim
    // permet de changer la valeur de delete trim pour la classe
    final public static function setDeleteTrim(?int $value):void
    {
        static::$config['deleteTrim'] = $value;
    }
}
?>