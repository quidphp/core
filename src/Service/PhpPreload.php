<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\Service;
use Quid\Base;
use Quid\Core;
use Quid\Main;

// phpPreload
// class used for concatenating a bunch of php files within a single one, for use as preloading
class PhpPreload extends Main\Service
{
    // config
    public static array $config = [
        'strictType'=>true, // s'il faut mettre un declare strict Type en haut du rendu
        'registerClosure'=>false, // s'il faut register la closure
        'bootPreload'=>false, // s'il faut mettre preload dans core/boot
        'concatenator'=>[], // option pour le compileur, voir la méthode statique concatenatorOption
        'credit'=>null, // permet de mettre un texte de crédit en haut du fichier
        'initMethod'=>null // permet de spécifier la init méthode
    ];


    // dynamique
    protected Main\Concatenator $concatenator; // conserve une copie de l'objet concatenator


    // construct
    // construit le service et et lie le concatenator
    final public function __construct(?array $attr=null)
    {
        parent::__construct($attr);
        $this->setAttr('concatenator',$this->concatenatorOption());
        $this->concatenator = Main\Concatenator::newOverload($this->getAttr('concatenator/base'));

        return;
    }


    // getConcatenator
    // retourne l'objet concatenator
    final public function getConcatenator():Main\Concatenator
    {
        return $this->concatenator;
    }


    // trigger
    // trigger et retourne la string php concatene
    final public function trigger(array $from):string
    {
        $return = null;
        $concatenator = $this->getConcatenator();
        $credit = $this->getAttr('credit',true);

        if(!empty($credit))
        $concatenator->addStr($credit,$this->getAttr('concatenator/credit'));

        foreach ($from as $namespace => $value)
        {
            if(is_string($namespace))
            {
                $path = Base\Autoload::getDirPath($namespace);
                if(!empty($path))
                $concatenator->add($path,$this->entryOption($value));
            }
        }

        $return = $concatenator->trigger();

        return $return;
    }


    // triggerWrite
    // méthode qui permet de concatener du php en un fichier
    // retourne l'objet fichier
    final public function triggerWrite(array $from,Core\File\Php $return):Core\File\Php
    {
        return $return->overwrite($this->trigger($from));
    }


    // entryOption
    // retourne le tableau d'option pour le namespace
    final protected function entryOption(array $return):array
    {
        $return = Base\Arrs::replace($this->getAttr('concatenator/entry'),$return);
        $initMethod = $this->getAttr('initMethod');
        $withClosure = (!empty($return['closure']));
        $withInit = (!empty($return['init']));
        $init = ($withInit === true)? $initMethod:null;
        $option = Base\Arrs::replace($this->getAttr('entry'),['closure'=>$withClosure,'init'=>$init]);

        $closure = function(string $content) use ($option) {
            $return = [];
            $info = Main\File\Php::infoFromString($content);

            if($info['name'] === 'Boot' && $info['type'] === 'class')
            {
                $option['closure'] = false;
                $option['return'] = false;
            }

            $return = Main\File\Php::innerLinesFromString($content,$option);

            return $return;
        };

        $return['content'] = $closure;

        return $return;
    }


    // concatenatorOption
    // retourne les options pour le concatenator
    final public function concatenatorOption():array
    {
        $return = [];
        $strictType = $this->getAttr('strictType');
        $registerClosure = $this->getAttr('registerClosure');
        $bootPreload = $this->getAttr('bootPreload');
        $initMethod = $this->getAttr('initMethod');
        $initMethodStr = (is_string($initMethod))? "'$initMethod'":'null';

        $start = '<?php';
        $start .= PHP_EOL;
        if($strictType === true)
        $start .= 'declare(strict_types=1);'.PHP_EOL;

        $end = PHP_EOL;
        if($registerClosure === true)
        {
            $end .= "namespace Quid\Main {";
            $end .= PHP_EOL.'Autoload::registerClosure(false,'.$initMethodStr.');';
            $end .= PHP_EOL.'}'.PHP_EOL;
        }
        if($bootPreload === true)
        {
            $end .= "namespace Quid\Core {";
            $end .= PHP_EOL.'Boot::$config["autoload"] = "preload";';
            $end .= PHP_EOL.'}'.PHP_EOL;
        }
        $end .= '?>';

        $return['base'] = [
            'start'=>$start,
            'end'=>$end,
            'separator'=>PHP_EOL.PHP_EOL
        ];

        $return['credit'] = [
            'separator'=>PHP_EOL.PHP_EOL,
            'start'=>PHP_EOL.'/*'.PHP_EOL,
            'end'=>PHP_EOL.'*/'
        ];

        $return['entry'] = [
            'separator'=>PHP_EOL.PHP_EOL,
            'extension'=>'php'
        ];

        return $return;
    }
}

// init
PhpPreload::__init();
?>