<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
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
        'docOpen'=>[
            'html'=>['data-error'=>'route']],
        'group'=>'error',
        'sitemap'=>false,
        'response'=>[
            'code'=>404],
        'route'=>true,
        'titleBox'=>true
    ];


    // trigger
    // méthode trigger par défaut
    public function trigger()
    {
        return $this->output('outputHtml');
    }


    // onFallback
    // sur fallback de la route error, il y a un problème
    // ça indique que même la page erreur ne peut pas s'afficher
    // ne trigger pas l'erreur car le log a probablement déjà été fait dans route/start
    final protected function onFallback($context=null)
    {
        if($context instanceof \Exception)
        $context->echoOutput();

        return;
    }


    // showOutputHtml
    // retourne vrai s'il faut générer le output
    final protected function showOutputHtml():bool
    {
        return ($this->request()->hasExtension() || Base\Server::isCli())? false:true;
    }


    // output
    // génère le output, il faut fournir un nom de méthode à appeler si c'est outputHtml
    final protected function output(string $method):string
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
    final protected function outputHtml():string
    {
        $r = '';
        $route = $this->getAttr('route');
        $titleBox = $this->getAttr('titleBox');

        $r .= Html::divOp('ajax-parse-error');

        if($titleBox === true)
        $r .= $this->makeTitleBox();

        $r .= Html::h3Cond($this->makeContent());

        if(!empty($route))
        {
            $here = static::langText('lc|common/here');

            if(is_string($route))
            {
                $route = $route::make();
                $link = $route->a($here);
            }

            else
            $link = Html::a('/',$here);

            $r .= Html::divOp('back');

            $r .= Html::span(static::langText('error/page/back',['link'=>$link]));
            $r .= Html::divCl();
        }

        $r .= Html::divCl();

        return $r;
    }


    // outputCli
    // génère le output cli de la route error
    final protected function outputCli():string
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
    final protected function makeTitle(?string $lang=null):string
    {
        return static::label().' '.Base\Response::code();
    }


    // makeSubTitle
    // génère le sous-titre pour la route
    final protected function makeSubTitle(?string $lang=null):string
    {
        return static::lang()->headerResponseStatus(Base\Response::code());
    }


    // makeContent
    // génère le contenu pour l'explication du code erreur http
    // peut retourner null
    final protected function makeContent():?string
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