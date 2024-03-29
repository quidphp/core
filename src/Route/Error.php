<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Base;
use Quid\Base\Cli;
use Quid\Base\Html;
use Quid\Core;

// error
// abstract class for an error route
abstract class Error extends Core\RouteAlias
{
    // config
    protected static array $config = [
        'path'=>null,
        'priority'=>9999,
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
    final protected function outputHtml(?bool $titleBox=null):string
    {
        $r = '';
        $route = $this->getAttr('route');
        $titleBox ??= $this->getAttr('titleBox');
        $lang = static::lang();

        if($titleBox === true)
        $r .= $this->makeTitleBox();

        $r .= Html::h3Cond($this->makeContent());

        if(!empty($route))
        {
            $here = $lang->text('lc|common/here');

            if(is_string($route))
            {
                $route = $route::make();
                $link = $route->a($here);
            }

            else
            $link = Html::a('/',$here);

            $span = Html::span($lang->text('error/page/back',['link'=>$link]));
            $r .= Html::div($span,'back');
        }

        return Html::div($r,'ajax-parse-error');
    }


    // outputCli
    // génère le output cli de la route error
    final protected function outputCli():string
    {
        $return = '';
        $boot = static::boot();

        $return .= Cli::neg($this->makeTitle());
        $return .= Cli::neg($this->makeSubTitle());
        $return .= Cli::neutral($this->makeContent());
        $return .= Cli::neutral($boot->typeEnvLabel());

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
        return static::label().' '.static::response()->code();
    }


    // makeSubTitle
    // génère le sous-titre pour la route
    final protected function makeSubTitle(?string $lang=null):string
    {
        return static::lang()->headerResponseStatus(static::response()->code());
    }


    // makeContent
    // génère le contenu pour l'explication du code erreur http
    // peut retourner null
    final protected function makeContent():?string
    {
        $code = static::response()->code();
        $lang = static::lang();

        return $lang->safe('error/page/content/'.$code);
    }
}

// init
Error::__init();
?>