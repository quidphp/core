<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;
use Quid\Orm;
use Quid\Base;

// media
class Media extends FilesAlias
{
	// config
	public static $config = [
		'search'=>true,
		'preValidate'=>'fileUpload',
		'cell'=>Core\Cell\Media::class,
		'validateKeys'=>['extension'=>'extension','maxFilesize'=>'maxFilesize'] // custom
	];


	// preValidatePrepare
	// prépare le tableau de chargement avant la prévalidation
	public function preValidatePrepare($value)
	{
		$return = null;

		if(Base\File::isUploadNotEmpty($value))
		$return = $value;

		elseif(is_string($value) && !empty($value))
		{
			$value = Base\Json::decode($value);

			if(is_array($value) && Base\Arr::keysExists(['action','path'],$value))
			{
				if(in_array($value['action'],['delete','regenerate'],true))
				{
					$path = Base\Path::append($this->rootPath(),$value['path']);
					$return = Base\File::makeUploadArray($path,9,false);
					$return['action'] = $value['action'];
				}
			}
		}

		return $return;
	}


	// onSet
	// logique onSet pour un champ média
	// process ne sera lancé que si l'opération sur la ligne (insertion/mise à jour) a réussi
	public function onSet($value,array $row,?Orm\Cell $cell=null,array $option)
	{
		$return = null;
		$value = parent::onSet($value,$row,$cell,$option);

		if(!empty($cell))
		$return = $cell->value();

		if(is_array($value) && $this->allowFileUpload())
		{
			$array = $this->onSetFileUpload($value);
			$return = $array['return'];
			$value = $array['value'];
		}

		if($value instanceof Core\File)
		{
			$old = Core\Files::newOverload();
			$regenerate = false;
			$basename = $value->mimeBasename($value->getOption('uploadBasename'));
			$return = Base\Path::safeBasename($basename);

			if(!empty($cell))
			{
				$old = $cell->all();
				$action = $value->getOption('uploadAction');

				if(!empty($action))
				{
					if($action === 'delete')
					{
						$cell->checkCanBeDeleted();
						$value = null;
						$return = null;
					}

					elseif($action === 'regenerate')
					{
						$cell->checkCanBeRegenerated();
						$old = Core\Files::newOverload();
						$value = null;
						$regenerate = true;
						$return = $cell->value();
					}
				}
			}

			$this->setCommittedCallback('getNewFiles',function() use($value) {
				return Core\Files::newOverload($value);
			});

			$this->setCommittedCallback('onCommitted',function(Core\Cell $cell) use($old,$value,$regenerate,$option) {
				$cell->process($old,$value,$regenerate,$option);
			},$cell);
		}

		return $return;
	}


	// onSetFileUpload
	// gère le onSet si c'est upload fichier (array dans $_FILES)
	protected function onSetFileUpload(array $array):array
	{
		$return = [];
		$r = null;
		$value = null;
		$error = $array['error'] ?? null;

		if(Base\File::isUploadNotEmpty($array))
		{
			$action = $array['action'] ?? null;
			$name = Base\File::uploadBasename($array);
			$value = Core\File::new($array);
			$value->setOption('uploadBasename',$name);
			$value->setOption('uploadDeleteSource',true);

			if($error === 9 && !empty($action))
			$value->setOption('uploadAction',$action);
		}

		elseif($error === 9)
		$r = null;

		$return['return'] = $r;
		$return['value'] = $value;

		return $return;
	}


	// formComplexUpdate
	// génère l'élément de formulaire complexe média lors d'une mise à jour
	protected function formComplexUpdate(Core\Cell $value,array $attr,array $option):string
	{
		$return = $this->commonFormComplexUpdate(null,$value,$attr,$option);

		if(empty($return))
		$return = $this->formComplexNothing();

		return $return;
	}
}

// config
Media::__config();
?>