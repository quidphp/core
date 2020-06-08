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
use Quid\Base;
use Quid\Main;
use Quid\Routing;

// route
// extended abstract class for a route that acts as both a View and a Controller
abstract class Route extends Routing\Route
{
    // trait
    use _fullAccess;


    // config
    protected static array $config = [ // config pour la route
        'metaTitle'=>['bootLabel'=>true,'typeLabel'=>false], // éléments à ajouter à la fin du titre
        'dbHistory'=>null, // permet d'activer ou désactiver l'historique de base de données pour la route
        'row'=>null, // permet de spécifier la classe row en lien avec la route
        'docOpen'=>[ // utilisé pour l'ouverture du document
            'html'=>['data-type'=>'%type%','data-env'=>'%env%','data-role'=>'%role%','data-user'=>'%sessionUser%','data-error'=>'none']],
        '@dev'=>[
            'jsonEncodePretty'=>true,
            'debug'=>1] // store dans debug
    ];


    // type
    // retourne le type de la route
    // si pas de type, utilise celui de boot
    // peut envoyer une exception
    final public static function type():string
    {
        $return = static::$config['type'] ?? null;

        if(!is_string($return))
        {
            $boot = Boot::instReady();
            $return = $boot->type();

            if(is_string($return))
            static::setType($return);

            else
            static::throw('noType');
        }

        return $return;
    }


    // processBefore
    // étend le processBefore de routing
    // ajoute support pour activer/désactiver dbHistory
    protected function processBefore():void
    {
        parent::processBefore();

        $dbHistory = $this->getAttr('dbHistory');
        if(is_bool($dbHistory))
        static::boot()->db()->setHistory($dbHistory);

        return;
    }


    // getBaseReplace
    // retourne le tableau de remplacement de base
    // utilisé par docOpen et docClose
    final public function getBaseReplace():array
    {
        $return = [];
        $boot = static::boot();
        $session = static::session();
        $lang = $boot->lang();
        $request = $this->request();
        $parent = static::parent();
        $lang = $this->lang();

        $return = $boot->getReplace();

        $return['label'] = $this->title();
        $return['name'] = static::name(true);
        $return['type'] = static::type();
        $return['uri'] = $request->uri();
        $return['metaUri'] = $request->uri();
        $return['group'] = static::group();
        $return['parent'] = (!empty($parent))? $parent::name(true):null;
        $return['title'] = $return['label'];
        $return['metaKeywords'] = $lang->safe('meta/keywords');
        $return['metaDescription'] = $lang->safe('meta/description') ?? $return['description'] ?? null;
        $return['metaImage'] = $lang->safe('meta/image');
        $return['htmlAttr'] = null;
        $return['bodyAttr'] = null;

        return $return;
    }


    // prepareTitle
    // prépare le titre après le onReplace
    final protected function prepareTitle($return,array $array):array
    {
        $titleConfig = $this->getAttr('metaTitle') ?? [];

        if(!is_array($return))
        $return = [$return];

        $last = Base\Arr::valueLast($return);

        if(!empty($titleConfig['bootLabel']))
        {
            if(!empty($array['bootLabel']) && $last !== $array['bootLabel'])
            $return[] = $array['bootLabel'];
        }

        if(!empty($titleConfig['typeLabel']))
        {
            $type = static::type();
            $return[] = static::lang()->typeLabel($type);
        }

        return $return;
    }


    // rowExists
    // retourne vrai s'il y a une row lié à la route
    public function rowExists():bool
    {
        return false;
    }


    // row
    // retourne la row lié à la route
    public function row():?Row
    {
        return null;
    }


    // getOtherMeta
    // retourne la row meta lié à la route
    // par défaut vérifie si la row a l'interface meta
    final public function getOtherMeta():?Main\Contract\Meta
    {
        $return = null;

        if($this->rowExists())
        {
            $row = $this->row();

            if($row instanceof Main\Contract\Meta)
            $return = $row;
        }

        return $return;
    }


    // getFormText
    // retourne le texte à utiliser pour form, gère unload et confirm
    // true renvoie vers lang common
    final protected function getFormText($value,string $type):string
    {
        if($value === true)
        $value = ['common',$type];

        return parent::getFormText($value,$type);
    }


    // host
    // retourne le host pour la route
    // utilise le type de la route et la méthode host dans boot
    final public static function host():?string
    {
        $return = null;
        $type = static::type();

        if(is_string($type))
        $return = static::boot()->host(true,$type);

        return $return;
    }


    // schemeHost
    // retourne le schemeHost pour la route
    // utilise le type de la route et la méthode schemeHost dans boot
    final public static function schemeHost():?string
    {
        $return = null;
        $type = static::type();

        if(is_string($type))
        $return = static::boot()->schemeHost(true,$type);

        return $return;
    }


    // routes
    // retourne l'objet routes de boot du type dela route
    final public static function routes():Routing\Routes
    {
        return static::boot()->routes(static::type());
    }


    // tableSegment
    // reourne un objet table à partir du tableau keyValue utilisé dans segment
    // sinon, utilise la rowClass
    // peut retourner null
    protected static function tableSegment(array &$keyValue):?Table
    {
        $return = null;
        $table = $keyValue['table'] ?? null;

        if(!empty($table))
        {
            $db = static::db();
            if($db->hasTable($table))
            $return = $db->table($table);
        }

        if(empty($return))
        {
            $rowClass = static::rowClass();
            if(!empty($rowClass))
            $return = $rowClass::tableFromFqcn();
        }

        return $return;
    }


    // rowClass
    // retourne la classe row lié à la route
    // par défaut retourne la classe overload
    final public static function rowClass(bool $overload=true):?string
    {
        $return = static::$config['row'] ?? null;

        if(!empty($return) && $overload === true)
        $return = $return::classOverload();

        return $return;
    }


    // tableFromRowClass
    // retourne l'objet table à partir de la classe row lié à la route
    // envoie une exception si pas de rowClass
    final public static function tableFromRowClass():Table
    {
        $return = null;
        $row = static::rowClass();

        if(empty($row))
        static::throw('noRowClass');
        else
        $return = static::db()->table($row);

        return $return;
    }


    // routeBaseClasses
    // retourne les classes bases de routes (donc abstraite)
    final public static function routeBaseClasses():array
    {
        return [self::class,Routing\Route::class];
    }
}

// init
Route::__init();
?>