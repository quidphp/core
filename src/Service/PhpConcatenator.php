<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Service;
use Quid\Base;
use Quid\Core;
use Quid\Main;

// phpConcatenator
// class used for concatenating a bunch of php files within a single one
class PhpConcatenator extends Core\ServiceAlias
{
    // config
    public static $config = [
        'option'=>[
            'strictType'=>true, // s'il faut mettre un declare strict Type en haut du rendu
            'registerClosure'=>false, // s'il faut register la closure
            'concatenator'=>[], // option pour le compileur, voir la méthode statique concatenatorOption
            'credit'=>null, // permet de mettre un texte de crédit en haut du fichier
            'namespace'=>[]]// spécifie les namespace pour la compilation, closure, closureinitMethod et priority sont supportés
    ];


    // dynamique
    protected $concatenator = null; // conserve une copie de l'objet concatenator


    // construct
    // construit le service et et lie le concatenator
    public function __construct(string $key,?array $option=null)
    {
        parent::__construct($key,$option);
        $concatenator = ['concatenator'=>$this->concatenatorOption()];
        $this->option($concatenator);
        $this->concatenator = Main\Concatenator::newOverload($this->getOption('concatenator/base'));

        return;
    }


    // getConcatenator
    // retourne l'objet concatenator
    public function getConcatenator():Main\Concatenator
    {
        return $this->concatenator;
    }


    // trigger
    // trigger et retourne la string php concatene
    public function trigger():string
    {
        $return = null;
        $concatenator = $this->getConcatenator();
        $credit = $this->getOptionCall('credit');
        $namespaces = $this->getOption('namespace');

        if(!empty($credit))
        $concatenator->addStr($credit,$this->getOption('concatenator/credit'));

        foreach ($namespaces as $namespace => $value)
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
    public function triggerWrite(Core\File\Php $return):Core\File\Php
    {
        return $return->overwrite($this->trigger());
    }


    // entryOption
    // retourne le tableau d'option pour le namespace
    protected function entryOption(array $return):array
    {
        $return = Base\Arrs::replace($this->getOption('concatenator/entry'),$return);
        $closure = $return['closure'] ?? false;

        if($closure === true)
        {
            $closureinitMethod = $return['closureinitMethod'] ?? null;
            $namespaceAccoladeAutoloadClosure = static::namespaceAccoladeAutoloadClosure($closureinitMethod);
            $return['content'] = $namespaceAccoladeAutoloadClosure;
        }

        else
        $return['content'] = static::namespaceAccoladeClosure();

        return $return;
    }


    // concatenatorOption
    // retourne les options pour le concatenator
    public function concatenatorOption():array
    {
        $return = [];
        $strictType = $this->getOption('strictType');
        $registerClosure = $this->getOption('registerClosure');

        $start = '<?php'.PHP_EOL;
        if($strictType === true)
        $start .= 'declare(strict_types=1);'.PHP_EOL;

        $end = PHP_EOL;
        if($registerClosure === true)
        {
            $end .= PHP_EOL."namespace Quid\Main {";
            $end .= PHP_EOL.'Autoload::registerClosure();';
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
            'lineStart'=>2,
            'lineEnd'=>1,
            'extension'=>'php'
        ];

        return $return;
    }


    // namespaceAccoladeClosure
    // retourne la closure, ceci transforme les namespace dans les classes php en namespace avec accolade
    public static function namespaceAccoladeClosure():\Closure
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
    public static function namespaceAccoladeAutoloadClosure(?string $initMethod):\Closure
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
                        $lines[] = '';
                        $lines[] = "//$initMethod";
                        $lines[] = "$class::$initMethod();";
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

// config
PhpConcatenator::__config();
?>