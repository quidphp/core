<?php
declare(strict_types=1);
namespace Quid\Core\Cell;
use Quid\Core;

// medias
class Medias extends FilesAlias
{
	// config
	public static $config = array();
	
	
	// cast
	// cast la cellule, retourne le path http ou base64
	public function _cast():?string 
	{
		return $this->commonCast();
	}
	
	
	// pair
	// retourne un fichier si true ou int, sinon renvoie à parent
	public function pair($value=null,...$args) 
	{
		return $this->commonPair($value,...$args);
	}
	
	
	// canBeDeleted
	// retourne vrai si le média peut être effacé
	// si média est requis, il doit au moins y avoir un
	public function canBeDeleted(?int $index=null):bool
	{
		$return = parent::canBeDeleted($index);
		
		if($return === true && $this->isRequired())
		{
			$indexes = $this->indexes();
			if($indexes->count() <= 1)
			$return = false;
		}
		
		return $return;
	}
	
	
	// basename
	// retourne un basename de fichier dans la cellule
	public function basename(?int $index=null,$version=null):?string 
	{
		return $this->commonBasename($index,$version);
	}
	
	
	// cellPath
	// retourne le chemin de la cellule qui combine le nom de la table, le id de la ligne, le nom de la colonne et l'index
	public function cellPath($index=null,$version=null):string 
	{
		return $this->commonCellPath($index,$version);
	}
	
	
	// cellPathBasename
	// retourne le chemin de la cellule avec le basename et l'index, le fichier n'a pas besoin d'exister
	public function cellPathBasename(?int $index=null,$version=null):?string 
	{
		return $this->commonCellPathBasename($index,$version);
	}
	
	
	// basePath
	// retourne le base path à l'index qui est une combinaison du rootPath et cellPath
	public function basePath($index=null,$version=null):string 
	{
		return $this->commonBasePath($index,$version);
	}
	
	
	// filePath
	// retourne le file path à l'index qui combine le basePath et le basename qui est la valeur de la cellule
	public function filePath(?int $index=null,$version=null):?string 
	{
		return $this->commonFilePath($index,$version);
	}
	
	
	// fileExists
	// retourne vrai si le fichier à l'index existe
	public function fileExists(?int $index=null,$version=null):bool 
	{
		return $this->commonFileExists($index,$version);
	}
	
	
	// checkFileExists
	// envoie une exception si le fichier à l'index n'existe pas
	public function checkFileExists(?int $index=null,$version=null):self 
	{
		return $this->commonCheckFileExists($index,$version);
	}
	
	
	// file
	// retourne l'objet fichier, peut retourner null
	public function file(?int $index=null,$version=null):?Core\File 
	{
		return $this->commonFile($index,$version);
	}
	
	
	// checkFile
	// retourne l'objet fichier, envoie une exception si non existant
	public function checkFile(?int $index=null,$version=null):Core\File 
	{
		return $this->commonCheckFile($index,$version);
	}
	
	
	// isImage
	// retourne vrai si la cellule contient un fichier image raster ou vector
	public function isImage(?int $index=null):bool 
	{
		return $this->commonIsImage($index);
	}
	
	
	// isImageRaster
	// retourne vrai si la cellule contient un fichier image raster
	public function isImageRaster(?int $index=null):bool 
	{
		return $this->commonIsImageRaster($index);
	}
	
	
	// isImageVector
	// retourne vrai si la cellule contient un fichier image vector
	public function isImageVector(?int $index=null):bool 
	{
		return $this->commonIsImageVector($index);
	}
	
	
	// versionExtension
	// retourne l'extension a utilisé pour la version à l'index donné
	public function versionExtension(?int $index=null,$version=null,bool $exception=true):string 
	{
		return $this->commonVersionExtension($index,$version,$exception);
	}
	
	
	// version
	// retourne un objet files avec toutes les versions pour un index, retourne null si pas de version
	public function version(int $index):?Core\Files 
	{
		return $this->commonVersion($index);
	}
	
	
	// makeVersion
	// reconstruit les versions pour l'index d'une image à partir de la configuration de la colonne
	public function makeVersion($index=null,?array $option=null):array
	{
		return $this->commonMakeVersion($index,$option);
	}
	
	
	// generalOutput
	// génère le output pour général, retourne seulement la première image de la cellule
	public function generalOutput(?array $option=null):string
	{
		$return = '';
		$col = $this->col();
		
		foreach($col->indexRange() as $index) 
		{
			$return .= $this->commonGeneralOutput($index,$option);
			
			if(!empty($return))
			break;
		}
		
		return $return;
	}
	
	
	// downloadRoute
	// retourne la route pour le téléchargement
	public function downloadRoute(int $index):Core\Route 
	{
		return $this->commonDownloadRoute($index);
	}
	
	
	// process
	// lance le process de déplacement des médias lié
	public function process(Core\Files $olds,Core\Files $news,array $regenerate,?array $option=null):void
	{
		$this->unlinks($olds,$option);
		
		foreach ($news as $index => $new) 
		{
			$this->commonProcess($index,$new);
		}
		
		if(!empty($regenerate))
		$this->makeVersion($regenerate,$option);
		
		return;
	}
	
	
	// checkIndex
	// envoie une exception si l'index est invalid, sinon retourne l'index
	protected function checkIndex(?int $index=null):int 
	{
		$return = null;
		$amount = $this->col()->getAmount();
		
		if($index === null)
		$index = 0;
		
		if(is_int($index) && is_int($amount))
		{
			if($index >= 0 && $index < $amount)
			$return = $index;
		}
		
		if(!is_int($return))
		static::throw('invalidIndex',$this->name(),$index);
		
		return $return;
	}


	// indexes
	// retourne un objet files avec tous les index de l'objet
	// possible de spécifier une version
	public function indexes($version=null):Core\Files 
	{
		$return = Core\Files::newOverload();
		$indexes = $this->indexesExists($version);
		
		foreach ($indexes as $index) 
		{
			$return->set($index,$this->checkFile($index,$version));
		}
		
		return $return;
	}
	
	
	// indexesExists
	// retourne un tableau avec tous les indexes qui existent
	public function indexesExists($version=null):array
	{
		$return = array();
		$get = $this->get();
		
		if(is_array($get) && !empty($get))
		{
			foreach ($get as $index => $basename) 
			{
				if($this->fileExists($index,$version))
				$return[] = $index;
			}
		}
		
		return $return;
	}
	
	
	// all
	// retourne un objet files avec toutes les index, les versions et l'original
	// possible d'exclure les versions
	public function all(bool $addVersion=true):Core\Files 
	{
		$return = Core\Files::newOverload();
		$indexes = $this->indexes();

		foreach ($indexes as $index => $file) 
		{
			$return->set($index,$file);
			
			if($addVersion === true)
			{
				$version = $this->version($index);
				
				if(!empty($version))
				{
					foreach($version as $key => $file2)
					{
						$k = $index."/".$key;
						$return->set($k,$file2);
					}
				}
			}
		}
		
		return $return;
	}
}

// config
Medias::__config();
?>