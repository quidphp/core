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


    // trigger
    // méthode trigger par défaut
    public function trigger()
    {
        return $this->output('outputHtml');
    }


    // showOutputHtml
    // retourne vrai s'il faut générer le output
    protected function showOutputHtml():bool
    {
        return ($this->request()->hasExtension() || Base\Server::isCli())? false:true;
    }

    
    // output
    // génère le output, il faut fournir un nom de méthode à appeler si c'est outputHtml
    protected function output(string $method):string
    {
        $r = '';
        
        if($this->showOutputHtml())
        $r .= $this->$method();
        
        elseif(Base\Server::isCli())
        $r .= $this->outputCli();
        
        return $r;
    }
    
    
    // outputHtml
    // génère le output html de la route error
    protected function outputHtml():string 
    {
        $r = '';
        $route = static::$config['route'];
        $titleBox = static::$config['titleBox'];

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
    
    
    // outputCli
    // génère le output cli de la route error
    protected function outputCli():string 
    {
        $return = '';
        $boot = static::boot();

        $return .= Base\Cli::neg($this->makeTitle());
        $return .= Base\Cli::neg($this->makeSubTitle());
        $return .= Base\Cli::neutral($this->makeContent());
        $return .= Base\Cli::neutral($boot->typeEnvLabel());
        
        return $return;
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