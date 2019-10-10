<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Base;
use Quid\Base\Html;
use Quid\Core;
use Quid\Main;

// error
// abstract class for an error route
abstract class Error extends Core\RouteAlias
{
    // config
    public static $config = [
        'path'=>null,
        'priority'=>999,
        'debug'=>true,
        'match'=>[
            'cli'=>null,
            'method'=>null],
        'group'=>'error',
        'sitemap'=>false,
        'response'=>[
            'code'=>404],
        'route'=>Home::class,
        'titleBox'=>true
    ];


    // onBefore
    // avant la route, applique le code de réponse immédiatemment
    // 404 est mis si le code d'erreur n'est pas déjà négatif
    protected function onBefore()
    {
        static::setResponseCode();
        
        return true;
    }


    // trigger
    // méthode trigger par défaut
    public function trigger()
    {
        return ($this->showErrorOutput())? $this->output():null;
    }


    // showErrorOutput
    // retourne vrai s'il faut générer le output
    public function showErrorOutput():bool
    {
        return ($this->request()->hasExtension())? false:true;
    }


    // output
    // génère le output de la route error
    // peut retourner null
    public function output():?string
    {
        $r = '';
        $route = static::$config['route'];
        $titleBox = static::$config['titleBox'];
        static::setResponseCode();

        $r .= Html::divOp('ajax-parse-error');

        if($titleBox === true)
        $r .= $this->makeTitleBox();

        $r .= Html::h3Cond($this->makeContent());

        if(!empty($route))
        {
            $route = $route::makeOverload();
            $r .= Html::divOp('back');
            $link = $route->a(static::langText('lc|common/here'));
            $r .= Html::span(static::langText('error/page/back',['link'=>$link]));
            $r .= Html::divCl();
        }

        $r .= Html::divCl();

        return $r;
    }


    // makeTitleBox
    // génère le titre et sous-titre
    protected function makeTitleBox():string
    {
        $r = '';
        $r .= Html::h1($this->makeTitle());
        $r .= Html::h2($this->makeSubTitle());

        return $r;
    }


    // makeTitle
    // génère le titre pour la route
    protected function makeTitle(?string $lang=null):string
    {
        return static::label().' '.Base\Response::code();
    }


    // makeSubTitle
    // génère le sous-titre pour la route
    protected function makeSubTitle(?string $lang=null):string
    {
        return static::lang()->headerResponseStatus(Base\Response::code());
    }


    // makeContent
    // génère le contenu pour l'explication du code erreur http
    // peut retourner null
    protected function makeContent():?string
    {
        $return = null;
        $code = Base\Response::code();
        $lang = static::lang();
        $return = $lang->safe('error/page/content/'.$code);

        return $return;
    }
}

// init
Error::__init();
?>