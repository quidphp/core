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
use Quid\Core;
use Quid\Main;

// cliCompile
// class for a cli route to compile assets (js and css)
class CliCompile extends Core\RouteAlias
{
    // trait
    use _cliLive;


    // config
    protected static array $config = [
        'path'=>['-compile'],
        'priority'=>8030,
        'fileClass'=>[
            'css'=>Core\File\Css::class,
            'js'=>Core\File\Js::class],
        'opt'=>[
            'compress'=>0]
    ];


    // dynamique
    protected int $fileAmount = 0; // conserve le nombre de fichiers traités


    // cli
    // disponible via http et cli
    // mais si via cli, le script est éternel
    final protected function cli(bool $cli)
    {
        $this->cliWrite('neutral',static::label());
        $this->compileAssets();
    }


    // shouldCompress
    // retourne vrai s'il faut forcer la compression
    final protected function shouldCompress():bool
    {
        return $this->isOpt('compress');
    }


    // fileAmountIncrement
    // incrément la propriété fileAmount
    final protected function fileAmountIncrement():void
    {
        $this->fileAmount++;
    }


    // fileAmount
    // retourne la propriété fileAmount
    final protected function fileAmount():int
    {
        return $this->fileAmount;
    }


    // compileAssets
    // compile les assets liés au type courant de boot
    final protected function compileAssets():void
    {
        $boot = static::boot();
        $attr = $boot->compileAttr();
        $this->compileLive($attr);
    }


    // compileLive
    // permet de faire une compilation constante via console
    // boot est teardown avant le lancement du loop éternel
    final protected function compileLive(array $attr):void
    {
        $this->live(fn() => $this->compilePass($attr,[],Base\Server::isCli()));
    }


    // compilePass
    // fait une passe de compilation
    final protected function compilePass(array $attr,array $overOption):array
    {
        $return = [];

        if($this->isFirstLoop())
        $overOption['overwrite'] = true;

        if($this->shouldCompress())
        $overOption['compress'] = true;

        if(!empty($attr['compileCss']))
        {
            $class = $this->getFileClass('css');
            $css = $this->compilePassType($class,$attr['compileCss'],$overOption,$attr['compileCssOption']);

            if(!empty($css))
            $return['css'] = $css;
        }

        if(!empty($attr['compileJs']))
        {
            $class = $this->getFileClass('js');
            $js = $this->compilePassType($class,$attr['compileJs'],$overOption,$attr['compileJsOption']);

            if(!empty($js))
            $return['js'] = $js;
        }

        return $return;
    }


    // compilePassType
    // fait une passe de compilation pour pour une classe de fichier
    final protected function compilePassType(string $class,array $arrays,?array $overOption=null,?array $option=null):array
    {
        $return = [];

        foreach ($arrays as $key => $array)
        {
            if(is_array($array) && !empty($array))
            {
                $array = Base\Arrs::replace($option,$array,$overOption);

                if($class::shouldConcatenateOne($array))
                {
                    $microtime = Base\Datetime::microtime();
                    $result = $class::concatenateOne($array);

                    if(!empty($result))
                    {
                        $compress = $array['compress'] ?? false;
                        $time = Base\Debug::speed($microtime,2);
                        $output = $this->makePositiveOutput($result,$compress,$time);

                        $return[$key] = $output;
                        $this->cliWrite('pos',$output);
                    }
                }
            }
        }

        return $return;
    }


    // makeFileOutput
    // génère le output à afficher pour un fichier ayant été compiler
    final protected function makePositiveOutput(Main\File $file,bool $compress,float $time):array
    {
        $return = [];
        $this->fileAmountIncrement();

        $size = $file->size(true);
        if($compress === true)
        $size .= ' (+)';

        $return[] = '#'.$this->fileAmount();
        $return[] = $file->path();
        $return[] = $size;
        $return[] = $time.'s';

        return $return;
    }


    // getFileClass
    // retourne la classe à utiliser pour le type de fichier
    final protected function getFileClass(string $type):string
    {
        return $this->getAttr(['fileClass',$type]);
    }
}

// init
CliCompile::__init();
?>