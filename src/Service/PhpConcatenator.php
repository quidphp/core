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

// phpConcatenator
// class used for concatenating a bunch of php files within a single one
class PhpConcatenator extends Main\Service
{
    // config
    public static $config = [
        'strictType'=>true, // s'il faut mettre un declare strict Type en haut du rendu
        'registerClosure'=>false, // s'il faut register la closure
        'bootPreload'=>false, // s'il faut mettre preload dans core/boot
        'concatenator'=>[], // option pour le compileur, voir la méthode statique concatenatorOption
        'credit'=>null, // permet de mettre un texte de crédit en haut du fichier
        'initMethod'=>null, // permet de spécifier la init méthode
        'entryLineStart'=>11, // ligne de départ pour chaque fichier
        'entryLineEnd'=>1 // ligne de fin pour chaque fichier
    ];


    // dynamique
    protected $concatenator = null; // conserve une copie de l'objet concatenator


    // construct
    // construit le service et et lie le concatenator
    final public function __construct(string $key,?array $attr=null)
    {
        parent::__construct($key,$attr);
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
        $closure = $return['closure'] ?? false;
        $initMethod = $this->getAttr('initMethod');

        if($closure === true)
        $return['content'] = static::namespaceAccoladeAutoloadClosure($initMethod);

        else
        $return['content'] = static::namespaceAccoladeClosure();

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
            $end .= PHP_EOL."namespace Quid\Main {";
            $end .= PHP_EOL.'Autoload::registerClosure(false,'.$initMethodStr.');';
            $end .= PHP_EOL.'}'.PHP_EOL;
        }
        if($bootPreload === true)
        {
            $end .= PHP_EOL."namespace Quid\Core {";
            $end .= PHP_EOL.'Boot::$config["autoload"] = "preload";';
            $end .= PHP_EOL.'}'.PHP_EOL;
        }
        $end .= PHP_EOL.'?>'.PHP_EOL;

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
            'lineStart'=>$this->getAttr('entryLineStart'),
            'lineEnd'=>$this->getAttr('entryLineEnd'),
            'extension'=>'php'
        ];

        return $return;
    }


    // namespaceAccoladeClosure
    // retourne la closure, ceci transforme les namespace dans les classes php en namespace avec accolade
    final public static function namespaceAccoladeClosure():\Closure
    {
        return function(string $return) {
            $lines = Base\Str::lines($return);
            if(!empty($lines[0]) && Base\Str::isEnd(';',$lines[0]))
            {
                $lines[0] = Base\Str::stripEnd(';',$lines[0]);
                $lines[0] .= ' {';
                $lines[] = '}';

                $return = Base\Str::lineImplode($lines);
            }

            return $return;
        };
    }


    // namespaceAccoladeAutoloadClosure
    // comme namespaceAccoladeClosure mais en plus la classe est storé dans main/autoload setClosure
    // possible d'init la classe si une initMethod est fourni en argument
    final public static function namespaceAccoladeAutoloadClosure(?string $initMethod=null):\Closure
    {
        return function(string $return) use($initMethod) {
            $namespaceAccoladeClosure = static::namespaceAccoladeClosure();
            $lines = Base\Str::lines($return);
            $namespace = Base\Str::stripStartEnd('namespace ',';',$lines[0]);

            foreach ($lines as $key => $value)
            {
                if(Base\Str::isStart('//',$value))
                {
                    $class = ucfirst(Base\Str::stripStart('// ',$value));

                    $newLine = ['\Quid\Main\Autoload::setClosure("'.$namespace.'","'.$class.'",function() {',''];
                    $lines = Base\Arr::insert($key,$newLine,$lines);

                    if(is_string($initMethod))
                    {
                        $initMethodStr = '::'.$initMethod.'();';
                        $last = Base\Arr::valueLast($lines);
                        if(is_string($last) && Base\Str::isEnd($initMethodStr,$last))
                        $lines = Base\Arr::spliceIndex(-3,3,$lines);
                    }

                    $lines[] = '});';

                    break;
                }
            }

            $return = Base\Str::lineImplode($lines);
            $return = $namespaceAccoladeClosure($return);

            return $return;
        };
    }
}

// init
PhpConcatenator::__init();
?>