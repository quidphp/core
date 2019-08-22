<?php
declare(strict_types=1);
namespace Quid\Core\Service;
use Quid\Core;
use Leafo;

// scssPhp
class ScssPhp extends Core\ServiceAlias
{
	// config
	public static $config = array(
		'format'=>array(
			Leafo\ScssPhp\Formatter\Expanded::class, // si compress est false
			Leafo\ScssPhp\Formatter\Crunched::class), // si compress est true
		'option'=>array(
			'compress'=>true, // permet de spécifier s'il faut compresser ou non le rendu
			'format'=>null), // permet de spécifier un format, ne prend pas en compte l'option compress
	);
	
	
	// dynamique
	protected $compiler = null; // conserve une copie de l'objet compiler
	
	
	// construct
	// construit le service et lit l'objet scssPhp
	public function __construct(string $key,?array $option=null) 
	{
		parent::__construct($key,$option);
		$this->compiler = new Leafo\ScssPhp\Compiler();
		
		return;
	}
	
	
	// getCompiler
	// retourne l'objet compiler
	public function getCompiler():Leafo\ScssPhp\Compiler
	{
		return $this->compiler;
	}
	
	
	// getFormat
	// retourne le format à utiliser
	public function getFormat():string 
	{
		$return = $this->getOption('format');
		
		if(empty($return))
		{
			$return = static::$config['format'][0];
			
			$compress = $this->getOption('compress');
			if($compress === true)
			$return = static::$config['format'][1];
		}
		
		return $return;
	}
	
	
	// trigger
	// permet de faire un rendu scss à partir d'une string ou objet file\css fourni en argument
	// possible de fournir des variables à déclarer avant le chargement du script
	// retourne la string css
	public function trigger($value,?array $importPaths=null,?array $variables=null):string 
	{
		$return = null;
		$compiler = $this->getCompiler();
		$format = $this->getFormat();
		$importPaths = (array) $importPaths;
		
		if($value instanceof Core\File\Css)
		{
			$importPaths[] = $value->dirname();
			$value = $value->read(true,true);
		}
		
		if(is_string($value))
		{
			$compiler->setFormatter($format);

			if(!empty($importPaths))
			$compiler->setImportPaths($importPaths);
			
			if(!empty($variables))
			$compiler->setVariables($variables);
			
			$return = $compiler->compile($value);
		}
		
		else
		static::throw('invalidValue');
		
		return $return;
	}
}

// config
ScssPhp::__config();
?>