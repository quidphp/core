<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Core;
use Quid\Orm;
use Quid\Base;

// medias
class Medias extends FilesAlias
{
	// config
	public static $config = [
		'search'=>false,
		'preValidate'=>'fileUploads',
		'onGet'=>[Base\Json::class,'onGet'],
		'cell'=>Core\Cell\Medias::class,
		'media'=>2, // custom
		'validateKeys'=>['extension'=>'extensions','maxFilesize'=>'maxFilesizes']
	];
	
	
	// hasIndex
	// retourne vrai comme medias supporte multiple indexes
	public function hasIndex():bool 
	{
		return true;
	}
	
	
	// preValidatePrepare
	// prépare le tableau de chargement avant la prévalidation
	public function preValidatePrepare($values) 
	{
		$return = null;
		
		if(Base\Arrs::is($values))
		{
			$return = [];
			
			foreach ($values as $key => $value) 
			{
				if(is_array($value))
				{
					if(Base\File::isUploadNotEmpty($value) || Base\File::isUploadTooBig($value))
					$return[$key] = $value;
				}
				
				elseif(is_string($value) && !empty($value))
				{
					$value = Base\Json::decode($value);
					
					if(is_array($value) && Base\Arr::keysExists(['action','path'],$value))
					{
						if(in_array($value['action'],['delete','regenerate'],true))
						{
							$path = Base\Path::append($this->rootPath(),$value['path']);
							$return[$key] = Base\File::makeUploadArray($path,9,false);
							$return[$key]['action'] = $value['action'];
						}
					}
				}
			}
		}
		
		return $return;
	}
	
	
	// onSet
	// logique onSet pour un champ médias
	// process ne sera lancé que si l'opération sur la ligne (insertion/mise à jour) a réussi
	public function onSet($value,array $row,?Orm\Cell $cell=null,array $option) 
	{
		$return = parent::onSet($value,$row,$cell,$option);
		$indexes = null;
		
		if(!empty($cell))
		{
			$indexes = $cell->indexes();
			$return = $cell->value();
		}
		
		if(is_array($value) && $this->allowFileUpload())
		$value = $this->onSetFileUpload($value,$indexes);
		
		if($value instanceof Core\Files)
		{
			$this->checkFilesIndex($value);
			$news = Core\Files::newOverload();
			$olds = Core\Files::newOverload();
			$regenerate = [];
			
			if(!empty($cell))
			{
				$return = Core\Files::newOverload();
				
				foreach($this->indexRange() as $i)
				{
					$current = null;
					
					if($indexes->exists($i))
					$current = $indexes->get($i);
					
					if($value->exists($i))
					{
						$new = $value->get($i);
						$action = $new->getOption('uploadAction');
						
						if(empty($action) || $action === 'delete')
						{
							if(empty($action))
							{
								$news->set($i,$new);
								$return->set($i,$new);
							}
							
							else
							$cell->checkCanBeDeleted($i);
							
							if(!empty($current))
							{
								$version = $cell->version($i);
								if(!empty($version))
								$olds->add($version);
								$olds->set(null,$current);
							}
						}
						
						elseif($action === 'regenerate')
						{
							$cell->checkCanBeRegenerated($i);
							$regenerate[] = $i;
							$return->set($i,$new);
						}
					}
					
					elseif(!empty($current))
					$return->set($i,$current);
				}
				
				$return->sort();
				$news->sort();
			}
			
			else
			$return = $news = $value;
			
			$array = [];
			foreach ($return as $key => $file) 
			{
				$basename = $file->mimeBasename($file->getOption('uploadBasename'));
				$array[$key] = Base\Path::safeBasename($basename);
			}
			
			$return = (!empty($array))? Base\Json::encode($array):null;
			
			$this->setCommittedCallback('getNewFiles',function() use($news) {
				return $news;
			});
			
			$this->setCommittedCallback('onCommitted',function(Core\Cell $cell) use($olds,$news,$regenerate,$option) {
				$cell->process($olds,$news,$regenerate,$option);
			},$cell);
		}
		
		return $return;
	}
	
	
	// onSetFileUpload
	// gère le onSet si c'est upload fichier (array dans $_FILES)
	protected function onSetFileUpload(array $array,?Core\Files $indexes):?Core\Files
	{
		$return = null;
		$value = null;
		
		if(Base\Column::is($array) && Base\File::isUploadArray(...array_values($array)))
		{
			$return = Core\Files::newOverload();
			
			foreach ($array as $k => $v) 
			{
				$error = $v['error'];
				
				if(Base\File::isUploadNotEmpty($v))
				{
					$return->set($k,$v);
					$file = $return->get($k);
					
					if(!empty($file))
					{
						$name = Base\File::uploadBasename($v);
						$file->setOption('uploadBasename',$name);
						$file->setOption('uploadDeleteSource',true);
						
						if($error === 9 && !empty($v['action']))
						$file->setOption('uploadAction',$v['action']);
					}
				}
				
				elseif($error === 9 && !empty($indexes))
				$indexes->remove($k);
			}
		}
		
		return $return;
	}
	
	
	// formComplexUpdate
	// génère l'élément de formulaire complexe média lors d'une mise à jour
	protected function formComplexUpdate(Core\Cell $value,array $attr,array $option):string 
	{
		$return = '';
		
		foreach($this->indexRange() as $index) 
		{
			$return .= $this->commonFormComplexUpdate($index,$value,$attr,$option);
		}
		
		if(empty($return))
		$return = $this->formComplexNothing();
		
		return $return;
	}
}

// config
Medias::__config();
?>