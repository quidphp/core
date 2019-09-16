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
use Verot\Upload;

// classUpload
// class that provides methods to use verot/class.upload.php for resizing images
class ClassUpload extends Core\ServiceAlias
{
    // config
    public static $config = [
        'option'=>[
            'quality'=>null,
            'convert'=>null,
            'action'=>null,
            'width'=>null,
            'height'=>null,
            'autoRotate'=>null]
    ];


    // dynamique
    protected $upload = null; // conserve une copie de l'objet class upload


    // trigger
    // compresse une version du fichier image en utilisant la librairie class.upload
    // si le retour est string, c'est une exception, sinon retourne true/false
    public function trigger($source,string $dirname,?string $filename=null,?array $option=null)
    {
        $return = false;
        $option = Base\Arr::plus($this->option(),$option);

        if($source instanceof Core\File\Image && $source->isFileExists())
        $source = $source->path();

        if(!is_string($source) || !Base\File::is($source))
        $return = 'invalidSource';

        elseif(!Base\Dir::setOrWritable($dirname))
        $return = 'cannnotCreateFolderStructure';

        else
        {
            $this->setClassUpload($source,$filename,$option);
            $upload = $this->getClassUpload();

            if(!$this->isImage())
            $return = 'requiresImageRaster';

            else
            {
                $upload->process($dirname);

                if($upload->processed)
                $return = true;

                else
                $return = $upload->error;
            }
        }

        return $return;
    }


    // setClassUpload
    // créer instance de l'objet upload après avoir paramétré et lie à l'objet courant
    // méthode protégé
    protected function setClassUpload(string $source,?string $filename=null,array $option):self
    {
        $this->upload = new Upload\Upload($source);
        $filename = (is_string($filename))? $filename:Base\Path::filename($source);

        $this->reset($filename);
        $this->prepareImage($option);

        return $this;
    }


    // getClassUpload
    // retourne l'instance de l'objet class upload
    public function getClassUpload():Upload\Upload
    {
        return $this->upload;
    }


    // isImage
    // retourne vrai si le fichier dans upload est une image
    public function isImage():bool
    {
        $return = false;
        $upload = $this->getClassUpload();

        if($upload->file_is_image === true)
        $return = true;

        return $return;
    }


    // isConvertImageType
    // retourne vrai si le fichier de conversion sera l'un des types données en argument
    public function isConvertImageType(...$types):bool
    {
        $return = false;

        if($this->isImage())
        {
            $upload = $this->getClassUpload();
            $convert = $upload->image_convert;
            $type = $upload->image_src_type;

            if(is_string($convert) && !empty($convert))
            {
                if(in_array(strtolower($convert),$types,true))
                $return = true;
            }

            elseif(is_string($type) && !empty($type) && in_array(strtolower($type),$types,true))
            $return = true;
        }

        return $return;
    }


    // reset
    // applique les paramètres par défaut à l'objet upload
    // méthode protégé
    protected function reset(string $filename):self
    {
        $upload = $this->getClassUpload();
        $upload->dir_auto_create = false;
        $upload->dir_auto_chmod = false;
        $upload->file_overwrite = false;
        $upload->file_auto_rename = false;
        $upload->file_safe_name = false;
        $upload->file_force_extension = true;
        $upload->file_new_name_body = $filename;

        return $this;
    }


    // prepareImage
    // paramètre pour image
    // méthode protégé
    protected function prepareImage(array $option):self
    {
        if($this->isImage())
        {
            $upload = $this->getClassUpload();
            $upload->jpeg_quality = -1;
            $upload->png_compression = -1;
            $upload->image_convert = '';

            if(is_string($option['convert']))
            $upload->image_convert = strtolower($option['convert']);

            if(is_bool($option['autoRotate']))
            $upload->image_auto_rotate = $option['autoRotate'];

            if(is_int($option['quality']))
            {
                if($this->isConvertImageType('jpg','jpeg'))
                $upload->jpeg_quality = static::qualityJpg($option['quality']);

                elseif($this->isConvertImageType('png'))
                $upload->png_compression = static::qualityPng($option['quality']);
            }

            if(is_int($option['width']) || is_int($option['height']))
            $this->prepareResize($option['width'],$option['height'],$option['action']);
        }

        return $this;
    }


    // prepareResize
    // méthode pour paramétrer un resize dans l'objet upload
    // méthode protégé
    protected function prepareResize(?int $width=null,?int $height=null,?string $action=null):self
    {
        if(is_int($width) || is_int($height))
        {
            $upload = $this->getClassUpload();
            $upload->image_resize = true;

            if(is_int($width))
            $upload->image_x = $width;

            if(is_int($height))
            $upload->image_y = $height;

            $value = true;
            if(is_array($action) && count($action) === 1)
            {
                $value = current($action);
                $action = key($action);
            }

            if(is_string($action) && (is_int($width) || is_int($height)))
            {
                if($action === 'ratio')
                $upload->image_ratio = $value;

                elseif($action === 'ratio_x')
                $upload->image_ratio_x = $value;

                elseif($action === 'ratio_y')
                $upload->image_ratio_y = $value;

                if(is_int($width) && is_int($height))
                {
                    if($action === 'crop')
                    $upload->image_ratio_crop = $value;

                    elseif($action === 'fill')
                    $upload->image_ratio_fill = $value;

                    elseif($action === 'bestFit')
                    {
                        $imageWidth = $upload->image_src_x;
                        $imageHeight = $upload->image_src_y;

                        if(is_int($imageWidth) && is_int($imageHeight))
                        {
                            $bestFit = Base\ImageRaster::bestFit($width,$height,$imageWidth,$imageHeight,false);

                            if(!empty($bestFit))
                            {
                                $upload->image_x = $bestFit['width'];
                                $upload->image_y = $bestFit['height'];
                            }
                        }
                    }
                }
            }
        }

        return $this;
    }


    // qualityJpg
    // gère la qualité pour un jpg
    public static function qualityJpg(int $value):int
    {
        return ($value > 0 || $value <= 100)? $value:0;
    }


    // qualityPng
    // gère la qualité pour un png
    public static function qualityPng(int $value):int
    {
        $return = 0;

        if($value >= 10 && $value < 100)
        $return = (int) floor($value / 10);

        elseif($value === 100)
        $return = 9;

        return $return;
    }
}

// config
ClassUpload::__config();
?>