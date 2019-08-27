<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Base\Html;
use Quid\Core;
use Quid\Main;
use Quid\Base;

// error
abstract class Error extends Core\RouteAlias
{
	// config
	public static $config = [
		'path'=>null,
		'priority'=>999,
		'group'=>'error',
		'sitemap'=>false,
		'response'=>[
			'code'=>404]
	];


	// onBefore
	// avant la route, applique le code de réponse immédiatemment
	// 404 est mis si le code d'erreur n'est pas déjà négatif
	protected function onBefore()
	{
		$return = true;
		static::setResponseCode();

		if(!$this->showHtml())
		$return = false;

		return $return;
	}


	// trigger
	// méthode trigger par défaut
	public function trigger()
	{
		return $this->html();
	}


	// showHtml
	// retourne vrai s'il faut générer le html
	public function showHtml():bool
	{
		return ($this->request()->hasExtension())? false:true;
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


	// makeTitleBox
	// génère le titre et sous-tire
	protected function makeTitleBox():string
	{
		$r = '';
		$r .= Html::h1($this->makeTitle());
		$r .= Html::h2($this->makeSubTitle());

		return $r;
	}


	// html
	// génère le html de la route error, version différente si la session est nobody ou somebody
	public function html():string
	{
		$r = '';

		if($this->session()->isNobody())
		$r = $this->nobody();

		else
		$r = $this->somebody();

		return $r;
	}


	// nobody
	// rendu de la route si nobody
	protected function nobody():string
	{
		return $this->makeTitleBox();
	}


	// somebody
	// rendu de la route si somebody
	protected function somebody():string
	{
		return $this->detail();
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


	// detail
	// retourne l'affichage de la page erreur pour somebody
	// possibile de spécifier la route pour le retour et d'affiche ou non le titleBox
	protected function detail(?Core\Route $route=null,bool $titleBox=true):string
	{
		$r = '';
		static::setResponseCode();

		if($titleBox === true)
		$r .= $this->makeTitleBox();

		$r .= Html::h3Cond($this->makeContent());

		if(!empty($route))
		{
			$r .= Html::divOp('back');
			$link = $route->a(static::langText('lc|common/here'));
			$r .= Html::span(static::langText('error/page/back',['link'=>$link]));
			$r .= Html::divCl();
		}

		return $r;
	}


	// fallback
	// s'il y a une exception dans le fallback de la route error, affiche le html de l'exception
	protected function fallback($context=null)
	{
		$return = parent::fallback($context);

		if($context instanceof Main\Exception)
		$return = $context->html();

		return $return;
	}
}

// config
Error::__config();
?>