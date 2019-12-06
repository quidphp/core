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
use Quid\Base\Cli;
use Quid\Core;
use Quid\Main;

// cliCompile
// class for the cli route to compile assets (js and css)
// the script cannot see if the attributes have changed in boot
class CliCompile extends Core\RouteAlias
{
    // trait
    use _cliLive;


    // config
    public static $config = [
        'path'=>['-compile'],
        'longSleep'=>3,
        'fileClass'=>[
            'css'=>Core\File\Css::class,
            'js'=>Core\File\Js::class]
    ];


    // cli
    // disponible via http et cli
    // mais si via cli, le script est éternel
    final protected function cli(bool $cli)
    {
        Cli::neutral(static::label());
        $this->compileAssets($cli);

        return;
    }


    // isLive
    // retourne vrai si la route est présentement live (en cli)
    final protected function isLive(bool $cli):bool
    {
        return ($cli === true && !$this->request()->isQuery('once'))? true:false;
    }


    // shouldCompress
    // retourne vrai s'il faut forcer la compression
    final protected function shouldCompress(bool $cli):bool
    {
        return ($cli === true && $this->request()->isQuery('compress'))? true:false;
    }


    // compileAssets
    // compile les assets liés au type courant de boot
    final protected function compileAssets(bool $cli):void
    {
        $results = [];
        $method = 'neg';
        $value = 'x';
        $boot = static::boot();
        $attr = $boot->compileAttr();
        $overOption = [];

        if($this->shouldCompress($cli))
        $overOption['compress'] = true;

        $results = $this->compileFirstPass($attr,$overOption);

        if(empty($results))
        Cli::neg($value);

        else
        {
            $method = 'pos';
            $value = $results;
        }

        $return[] = [$method=>$value];

        if($this->isLive($cli))
        $this->compileLive($attr,$overOption);

        return;
    }


    // compileFirstPass
    // première passe de compilation
    final protected function compileFirstPass(array $attr,?array $overOption=null):array
    {
        return $this->compilePass($attr,Base\Arrs::replace($overOption,['overwrite'=>true]));
    }


    // compileLive
    // permet de faire une compilation constante via console
    final protected function compileLive(array $attr,?array $overOption=null):void
    {
        $this->live(function() use($attr,$overOption) {
            $this->compilePass($attr,$overOption);
        },true);

        return;
    }


    // compilePass
    // fait une passe de compilation
    final protected function compilePass(array $attr,?array $overOption=null):array
    {
        $return = [];

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
                    try
                    {
                        $microtime = Base\Datetime::microtime();
                        Cli::neutral(Base\Datetime::sql());
                        $result = $class::concatenateOne($array);

                        if(!empty($result))
                        {
                            $compress = $array['compress'] ?? false;
                            $time = Base\Debug::speed($microtime,2);
                            $output = $this->makePositiveOutput($result,$compress,$time);

                            $return[$key] = $output;
                            Cli::pos($output);
                        }
                    }

                    catch (\Exception $e)
                    {
                        $output = $this->makeNegativeOutput($e);
                        Cli::neg($output);
                    }
                }
            }
        }

        return $return;
    }


    // makeFileOutput
    // génère le output à afficher pour un fichier ayant été compiler
    final protected function makePositiveOutput(Main\File $file,bool $compress,float $time):string
    {
        $return = '';
        $this->amountIncrement();
        $compress = ($compress === true)? '+':'-';

        $return .= $this->amount();
        $return .= '. ';
        $return .= $file->path();
        $return .= ' | ';
        $return .= $compress;
        $return .= $file->size(true);
        $return .= $compress;
        $return .= ' | ';
        $return .= $time.'s';

        return $return;
    }


    // makeNegativeOutput
    // génère le output lors d'une exception lancé lors de la compilation
    final protected function makeNegativeOutput(\Exception $exception):string
    {
        $return = '';
        $this->amountIncrement();

        $return .= $this->amount();
        $return .= '. ';
        $return .= $exception->getMessage();

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