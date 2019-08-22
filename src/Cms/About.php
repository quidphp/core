<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Base\Html;
use Quid\Core;
use Quid\Base;

// about
class About extends Core\RouteAlias
{
	// trait
	use _common;
	
	
	// config
	public static $config = array(
		'path'=>array(
			'en'=>'about',
			'fr'=>'a-propos'),
		'match'=>array(
			'ajax'=>true),
		'group'=>'dialog'
	);
	
	
	// trigger
	// html pour la page à propos, qui est accessible à tous peu importe le role
	public function trigger() 
	{
		$r = '';
		$boot = static::boot();
		
		$replace = array();
		$replace['bootLabel'] = $boot->label();
		$replace['version'] = $boot->version(true);
		$replace['author'] = $this->authorLink();
		$replace['supportEmail'] = $this->authorEmail();
		
		$r .= Html::divtableOpen();
		$r .= Html::h1(static::label());
		$r .= Html::h2($boot->label());
		$r .= Html::h3($boot->typeLabel());
		$r .= Html::divCond(static::langText('about/content',$replace),'content');		
		$r .= Html::divtableClose();
		
		return $r;
	}
	
	
	// aDialog
	// retourne le lien dialog
	public function aDialog(?array $attr=null):string
	{
		return $this->aTitle(null,Base\Attr::append($attr,array('data'=>array('jsBox'=>'dialogAbout'))));
	}
}

// config
About::__config();
?>