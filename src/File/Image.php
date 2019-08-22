<?php
declare(strict_types=1);
namespace Quid\Core\File;
use Quid\Base;

// image
abstract class Image extends BinaryAlias
{
	// img
	// génère un tag img à partir du fichier image
	// note si l'image n'a pas un chemin accessible via http, la resource sera affiché sous forme de base64
	public function img($alt=null,$attr=null,bool $absolute=false):?string 
	{
		$return = null;
		$src = $this->pathToUri($absolute) ?? $this->resource();
		$alt = $alt ?? $this->getOption('defaultAlt');
		$return = Base\Html::img($src,$alt,$attr);
		
		return $return;
	}
}

// config
Image::__config();
?>