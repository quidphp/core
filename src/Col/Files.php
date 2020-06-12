<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\Col;
use Quid\Base;
use Quid\Core;
use Quid\Main;

// files
// abstract class extended by the media and medias cols
abstract class Files extends Core\ColAlias
{
    // config
    protected static array $config = [
        'tag'=>'inputText',
        'export'=>false,
        'order'=>false,
        'panel'=>'media',
        'duplicate'=>false,
        'check'=>['kind'=>'text'],
        'version'=>null, // custom
        'generalExcerptMin'=>null,
        'defaultVersionExtension'=>['jpg','png'], // extension par défaut
        'path'=>'[storagePublic]/storage', // chemin du media
        'media'=>1, // nombre de media
        'validateKeys'=>['extension'=>null,'maxFilesize'=>null], // clés de validation, remplacé dans media/medias
        'extension'=>null, // extensions permises
        'maxFilesize'=>null, // max file size spécifique pour l'upload, sinon utilise php ini, si false désactive
        'compressAction'=>['ratio','ratio_x','ratio_y','crop','fill','bestFit'], // liste des actions de compressions admises
        'defaultVersion'=>[ // défaut pour version
            'quality'=>90,
            'convert'=>'jpg',
            'action'=>null,
            'width'=>null,
            'height'=>null,
            'autoRotate'=>false],
        'versionDefault'=>[], // permet d'attribuer une version par défaut pour un type
        'fromPath'=>true, // s'il faut détecter le mime type du fichier via son path ou via finfo lors de la création d'un nouveau fichier
        'fileUpload'=>true, // permet le chargement par fichier (input file)
        'route'=>['download'=>null] // route à ajouter
    ];


    // onMakeAttr
    // gère onMakeAttr pour media et medias
    // note pour medias: si required est true, alors le nombre de media devient le validate/fileCount sauf si un fileCount est deja set
    final protected function onAttr(array $return):array
    {
        $maxFilesize = $return['maxFilesize'] ?? null;
        if($maxFilesize !== false)
        {
            $maxFilesize = static::makeMaxFilesize($maxFilesize);
            $iniMaxFilesize = Base\Ini::uploadMaxFilesize(1);
            if($maxFilesize > $iniMaxFilesize)
            static::throw('maxFilesizeCannotBeLargetThanIni');
            $maxFilesizeKey = $return['validateKeys']['maxFilesize'];
            $return['validate'][$maxFilesizeKey] = $this->maxFilesizeClosure();
        }

        $extensionKey = $return['validateKeys']['extension'];
        $return['validate'][$extensionKey] = $this->extensionClosure();

        $required = $return['required'] ?? null;
        if($this->hasIndex() && $required === true && !array_key_exists('fileCount',$return['validate']))
        {
            $media = $return['media'] ?? null;
            if(is_int($media))
            $return['validate']['fileCount'] = $media;
            else
            static::throw('invalidAmount',$media);
        }

        return $return;
    }


    // allowfileUpload
    // retourne vrai si le chargement par fichier est permis
    final public function allowFileUpload():bool
    {
        return $this->getAttr('fileUpload') === true;
    }


    // showDetailsMaxLength
    // n'affiche pas le détail sur le maxLength de la colonne
    final public function showDetailsMaxLength():bool
    {
        return false;
    }


    // extensionClosure
    // méthode anonyme pour valider si l'extension du ou des fichiers est conforme à extension
    final protected function extensionClosure():\Closure
    {
        return function(string $context,$value=null) {
            $return = null;
            $extension = $this->extension();

            if($context === 'lang' && !empty($extension))
            $return = $extension;

            elseif($context === 'validate')
            {
                $return = true;
                $extensionKey = $this->getValidateKey('extension');
                $files = $this->getCommittedCallback('getNewFiles');

                if(!empty($files))
                {
                    $files = $files();

                    if(!empty($files) && $files->isNotEmpty() && !empty($extension))
                    {
                        foreach ($files as $file)
                        {
                            $basename = $file->mimeBasename($file->getAttr('uploadBasename'));

                            if(!Base\Path::isExtension($extension,$basename))
                            {
                                $return = [$extensionKey=>$extension];
                                break;
                            }
                        }
                    }
                }
            }

            return $return;
        };
    }


    // maxFilesizeClosure
    // méthode anonyme pour valider si le ou les fichiers respectent max file size
    final protected function maxFilesizeClosure():\Closure
    {
        return function(string $context,$value=null) {
            $return = null;
            $format = $this->maxFilesizeFormat();

            if($context === 'lang' && !empty($format))
            $return = $format;

            elseif($context === 'validate')
            {
                $return = true;
                $maxFilesizeKey = $this->getValidateKey('maxFilesize');
                $maxSize = $this->maxFilesize();
                $files = $this->getCommittedCallback('getNewFiles');

                if(!empty($files))
                {
                    $files = $files();

                    if(!empty($files) && $files->isNotEmpty())
                    {
                        foreach ($files as $file)
                        {
                            $path = $file->path();

                            if(!Base\File::isMaxSize($maxSize,$path))
                            {
                                $return = [$maxFilesizeKey=>$format];
                                break;
                            }
                        }
                    }
                }
            }

            return $return;
        };
    }


    // checkWritable
    // envoie une exception si quelque chose n'est pas écrivable dans le dossier
    final public function checkWritable():self
    {
        $tablePath = $this->tablePath();

        if(!Base\Dir::isWritableOrCreatable($tablePath))
        static::catchable(null,'pathNotWritable',$tablePath);

        return $this;
    }


    // getValidateKey
    // retourne une clé de validation dans les attributs
    final public function getValidateKey(string $key):string
    {
        return $this->getAttr(['validateKeys',$key]);
    }


    // hasIndex
    // retourne faux par défaut
    public function hasIndex():bool
    {
        return false;
    }


    // extension
    // retourne les extensions permises
    // si extension est vide et qu'il a y a une version, utilise defaultVersionExtension
    final public function extension():array
    {
        $return = $this->getAttr('extension');

        if(empty($return) && $this->hasVersion())
        $return = $this->defaultVersionExtension();

        if(!is_array($return))
        $return = (array) $return;

        return $return;
    }


    // extensionFormat
    // retourne les extensions admises en string, divisés par un séparateur
    final public function extensionFormat(string $separator=', '):string
    {
        return implode($separator,$this->extension());
    }


    // hasDistinctMaxFilesize
    // retourne vrai s'il y a une limite de taille de fichier distincte à la colonne, donc plus petite que php ini
    final public function hasDistinctMaxFilesize():bool
    {
        $return = false;
        $iniMaxFilesize = Base\Ini::uploadMaxFilesize(1);
        $maxFilesize = $this->maxFilesize();

        if($maxFilesize < $iniMaxFilesize)
        $return = true;

        return $return;
    }


    // maxFilesize
    // retourne le max filesize pour la colonne
    final public function maxFilesize():int
    {
        return static::makeMaxFilesize($this->getAttr('maxFilesize'));
    }


    // maxFilesizeFormat
    // retourne le max filesize formaté pour la colonne
    final public function maxFilesizeFormat():string
    {
        return Base\Num::sizeFormat($this->maxFilesize());
    }


    // getAmount
    // retourne le nombre de fichiers dans le champ médias
    // peut envoyer une exception
    final public function getAmount():int
    {
        $return = $this->getAttr('media');
        $hasIndex = $this->hasIndex();

        if(!is_int($return) || $return <= 0)
        static::throw('invalidAmount');

        if($hasIndex === false && $return !== 1)
        static::throw('mediaAmountCanOnlyBeOne');

        return $return;
    }


    // indexRange
    // retourne le range des index
    final public function indexRange():array
    {
        return range(0,($this->getAmount() - 1));
    }


    // hasVersion
    // retourne vrai si l'image a des versions
    final public function hasVersion():bool
    {
        return Base\Arrs::is($this->getAttr('version'));
    }


    // versions
    // retourne le tableau avec les versions à générer pour le fichier
    // envoie une exception si le tableau est mal formatté
    final public function versions():?array
    {
        return $this->cache(__METHOD__,function() {
            $return = null;

            if($this->hasVersion())
            {
                $return = [];

                foreach ($this->getAttr('version') as $key => $value)
                {
                    if(is_string($key) && !empty($value))
                    {
                        $v = $this->defaultVersion();
                        $keys = array_keys($v);
                        $value = Base\Arr::keysChange($keys,$value);
                        $v = Base\Arr::plus($v,$value);

                        if(is_string($v['convert']))
                        $v['convert'] = strtolower($v['convert']);

                        $this->checkVersion($v);
                        $return[$key] = $v;
                    }

                    else
                    static::throw($key,'versionKeyValue');
                }
            }

            return $return;
        });
    }


    // checkVersion
    // vérifie qu'une version est valide, envoie une exception sinon
    final public function checkVersion(array $value):bool
    {
        $return = true;
        $throw = [];

        foreach (['quality','width','height'] as $z)
        {
            if(!is_int($value[$z]) && $value[$z] !== null)
            $throw[] = $z;
        }

        $action = (is_array($value['action']))? key($value['action']):$value['action'];
        if($action !== null && !in_array($action,$this->getAttr('compressAction'),true))
        $throw[] = 'action';

        if(!is_string($value['convert']) && $value['convert'] !== true && $value['convert'] !== null)
        $throw[] = 'convert';

        if(!is_bool($value['autoRotate']) && $value['autoRotate'] !== null)
        $throw[] = 'autoRotate';

        if(!empty($throw))
        static::throw($this->tableName(),$this->name(),$throw,...array_values($value));

        return $return;
    }


    // version
    // permet de retourner la configuration pour une version
    final public function version($version=null,bool $exception=true):?array
    {
        $return = null;
        $versions = $this->versions();
        $version = $this->versionKey($version,$exception);

        if(array_key_exists($version,$versions))
        $return = $versions[$version];

        return $return;
    }


    // versionKey
    // retourne la clé de version à utiliser
    // -1 retourne la clé la plus petite, alors que 1 retourne la clé la plus grande
    // différentes exceptions peuvent être envoyés si exception est true
    final public function versionKey($version=null,bool $exception=true)
    {
        $return = null;
        $versions = $this->versions();
        $hasVersion = ($versions !== null);

        if($hasVersion === true)
        {
            if($version === null)
            $return = 0;

            elseif($version === -1)
            $return = key($versions);

            elseif($version === 1)
            $return = Base\Arr::keyLast($versions);

            elseif(is_string($version) && array_key_exists($version,$versions))
            $return = $version;

            if($exception && $return === null)
            static::throw('invalidReturn');
        }

        elseif($exception === true && $hasVersion === false && $version !== null)
        static::throw('mediaHasNoVersion');

        return $return;
    }


    // details
    // retourne un tableau de détail en lien avec la colonne, utilise versionDetails
    final public function details(bool $lang=true):array
    {
        $return = [];

        $extension = $this->extensionDetails($lang);
        $fileSize = $this->fileSizeDetails($lang);
        $version = array_values((array) $this->versionDetails());
        $parent = parent::details($lang);
        $return = Base\Arr::merge($extension,$fileSize,$version,$parent);

        return $return;
    }


    // fileSizeDetails
    // retourne le string pour fileSize admis
    final public function fileSizeDetails(bool $lang=true)
    {
        $return = null;
        $maxFilesizeKey = $this->getAttr('validateKeys/maxFilesize');

        if($this->getAttr('maxFilesize') !== false)
        {
            $maxFilesize = $this->maxFilesizeFormat();
            $return = [$maxFilesizeKey=>$maxFilesize];

            if($lang === true)
            {
                $lang = $this->db()->lang();
                $return = $lang->validate($return);
            }
        }

        return $return;
    }


    // extensionDetails
    // retourne la string des extensions admises
    final public function extensionDetails(bool $lang=true)
    {
        $return = null;
        $extension = $this->extension();
        $extensionKey = $this->getAttr('validateKeys/extension');

        if(is_string($extensionKey) && !empty($extension))
        {
            $return = [$extensionKey=>$extension];

            if($lang === true)
            {
                $lang = $this->db()->lang();
                $return = $lang->validate($return);
            }
        }

        return $return;
    }


    // versionDetails
    // génère un tableau avec la description pour chaque version
    // utilise width, height, quality, action et key
    final public function versionDetails():?array
    {
        $return = null;
        $version = $this->versions();

        if(!empty($version))
        {
            $return = [];

            foreach ($version as $key => $value)
            {
                $r = static::versionDetail($key,$value);

                if(strlen($r))
                $return[$key] = $r;
            }
        }

        return $return;
    }


    // rootPath
    // retourne le chemin root pour la colonne, envoie une exception si vide
    final public function rootPath(bool $shortcut=true):string
    {
        $return = null;
        $path = $this->getAttr('path');

        if(is_string($path))
        {
            $return = $path;

            if($shortcut === true)
            $return = Base\Finder::normalize($return);
        }

        if(empty($return))
        static::throw($this);

        return $return;
    }


    // tablePath
    // retourne le chemin root avec la table
    final public function tablePath(bool $shortcut=true):string
    {
        $return = $this->rootPath($shortcut);
        $return = Base\Path::append($return,$this->tableName());

        return $return;
    }


    // checkFilesIndex
    // vérifier que les index du fichier files sont bien valides
    // l'exception est attrapable
    final public function checkFilesIndex(Main\Files $files):self
    {
        $amount = $this->getAmount();

        foreach ($files as $key => $value)
        {
            if(!is_int($key) || $key >= $amount)
            static::catchable(null,'invalidFileIndex',($key + 1),$key);
        }

        return $this;
    }


    // defaultVersion
    // retourne les config par défaut pour une version
    final public function defaultVersion():array
    {
        return $this->getAttr('defaultVersion');
    }


    // defaultVersionExtension
    // retourne l'extension par défaut si non spécifié et qu'il y a une version
    final public function defaultVersionExtension():array
    {
        return $this->getAttr('defaultVersionExtension');
    }


    // defaultConvertExtension
    // retourne l'extension de conversion par défaut pour une version
    final public function defaultConvertExtension():string
    {
        return current($this->defaultVersionExtension());
    }


    // versionDetail
    // génère la string de détail à partir d'un tableau de version
    final public static function versionDetail(string $key,array $value):string
    {
        $return = '';
        $name = ucfirst($key);
        $quality = $value['quality'] ?? null;
        $convert = $value['convert'] ?? null;
        $action = $value['action'] ?? null;
        $width = $value['width'] ?? null;
        $height = $value['height'] ?? null;
        $autoRotate = $value['autoRotate'] ?? null;
        $actionValue = null;

        if(is_array($action) && count($action) === 1)
        {
            $actionValue = current($action);
            $action = key($action);
        }

        $return .= $name.':';

        if(is_int($width) || is_int($height))
        {
            $return .= ' ';
            $return .= (is_int($width))? $width.'px':'';

            $return .= ' x ';

            $return .= (is_int($height))? $height.'px':'';
        }

        if(is_string($action))
        {
            $return .= ' '.$action;

            if(is_scalar($actionValue))
            $return .= ': '.$actionValue;
        }

        if(is_int($quality))
        $return .= ' '.$quality.'%';

        if(is_string($convert) && strlen($convert))
        $return .= ' '.$convert;

        elseif($convert === true || $convert === null)
        $return .= ' =';

        return $return;
    }


    // makeMaxFilesize
    // méthode statique pour retourner la valeur maxFilesize
    final public static function makeMaxFilesize($value):int
    {
        $return = null;

        if(is_string($value))
        $value = Base\Num::fromSizeFormat($value);

        if(is_int($value))
        $return = $value;

        if(empty($return))
        $return = Base\Ini::uploadMaxFilesize(1);

        return $return;
    }
}

// init
Files::__init();
?>