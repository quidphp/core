<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Row;
use Quid\Base;
use Quid\Core;
use Quid\Main;

// logHttp
// class to represent a row of the logHttp table, stores bad HTTP requests
class LogHttp extends Core\RowAlias implements Main\Contract\Log
{
    // trait
    use _log;


    // config
    protected static array $config = [
        'priority'=>1002,
        'panel'=>false,
        'parent'=>'system',
        'cols'=>[
            'context'=>['class'=>Core\Col\Context::class],
            'request'=>['class'=>Core\Col\Request::class],
            'type'=>['general'=>true,'relation'=>'logHttpType']],
        'block'=>[ // liste des patterns de chemins à ne pas logger, insensible à la case
            'apple-touch-icon',
            'browserconfig.xml',
            'autodiscover.xml'],
        'type'=>[ // type de logHttp
            1=>'requestUnsafe',
            2=>'requestInvalid',
            3=>'externalPost',
            200=>200,
            301=>301,
            302=>302,
            400=>400,
            403=>403,
            404=>404,
            500=>500]
    ];


    // shouldLog
    // retourne vrai s'il faut logger la route à partir du chemin
    // voir static config block
    final public static function shouldLog(Main\Request $request):bool
    {
        return (Base\Arr::hasValueStart($request->pathStripStart(),static::$config['block'] ?? [],false))? false:true;
    }


    // getTypeCode
    // retourne le code à partir du type
    final public static function getTypeCode($type):?int
    {
        return (in_array($type,static::$config['type'],true))? array_search($type,static::$config['type'],true):null;
    }


    // prepareLogData
    // crée le tableau d'insertion
    final protected static function prepareLogData($type,$json=null):?array
    {
        $return = null;
        $type = static::getTypeCode($type);

        if(is_int($type))
        $return = ['type'=>$type,'json'=>$json];

        return $return;
    }


    // onCloseDown
    // ajoute un log au closeDown si le code de réponse n'est pas positif (pas 200, 301 ou 302)
    // et si la requête doit être loggé dans shouldLOg
    final public static function onCloseDown():void
    {
        static::logClosureCloseDown(function() {
            return static::logCurrentRequest();
        });
    }


    // logCurrentRequest
    // permet de logger la requête courante
    final public static function logCurrentRequest(bool $after=false):bool
    {
        $return = false;
        $data = [];
        $boot = static::bootReady();

        if(!empty($boot))
        {
            $response = $boot->response();
            $request = $boot->request();
            $code = $response->code();

            if(static::shouldLog($request))
            {
                $shouldLog = $response->isCodeLoggable();
                $data = $request->getLogData();

                if(!empty($data))
                $shouldLog = true;

                if($shouldLog === true)
                {
                    $return = true;
                    $route = $boot->route();

                    if(!empty($route))
                    $data['route'] = $route;
                }
            }
        }

        if($return === true)
        {
            $data = $data ?: null;
            static::logStrictAfter(false,$after,$code,$data);
        }

        return $return;
    }
}

// init
LogHttp::__init();
?>