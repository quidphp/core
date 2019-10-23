<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Base;
use Quid\Base\Html;
use Quid\Core;
use Quid\Main;

// files
// abstract class extended by the media and medias cols
abstract class Files extends Core\ColAlias
{
    // config
    public static $config = [
        'tag'=>'inputText',
        'complex'=>'inputFile',
        'export'=>false,
        'order'=>false,
        'filter'=>false,
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
        'fileUpload'=>true, // permet le chargement par fichier (input file)
        'route'=>['download'=>null] // route à ajouter
    ];


    // onMakeAttr
    // gère onMakeAttr pour media et medias
    // note pour medias: si required est true, alors le nombre de media devient le validate/fileCount sauf si un fileCount est deja set
    protected function onMakeAttr(array $return):array
    {
        $maxFilesize = $return['maxFilesize'] ?? null;
        if($maxFilesize !== false)
        {
            $maxFilesize = static::makeMaxFilesize($maxFilesize);
            $iniMaxFilesize = Base\Ini::uploadMaxFilesize(1);
            if($maxFilesize > $iniMaxFilesize)
            static::throw('maxFilesizeCannotBeLargetThanIni');
            $maxFilesizeKey = static::$config['validateKeys']['maxFilesize'];
            $return['validate'][$maxFilesizeKey] = $this->maxFilesizeClosure();
        }

        $extensionKey = static::$config['validateKeys']['extension'];
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
    public function allowFileUpload():bool
    {
        return ($this->attr('fileUpload') === true)? true:false;
    }


    // showDetailsMaxLength
    // n'affiche pas le détail sur le maxLength de la colonne
    public function showDetailsMaxLength():bool
    {
        return false;
    }


    // extensionClosure
    // méthode anonyme pour valider si l'extension du ou des fichiers est conforme à extension
    protected function extensionClosure():\Closure
    {
        return function(string $context,$value=null) {
            $return = null;
            $extension = $this->extension();

            if($context === 'lang')
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
                            $basename = $file->mimeBasename($file->getOption('uploadBasename'));

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
    protected function maxFilesizeClosure():\Closure
    {
        return function(string $context,$value=null) {
            $return = null;
            $format = $this->maxFilesizeFormat();

            if($context === 'lang')
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
    public function checkWritable():self
    {
        $tablePath = $this->tablePath();

        if(!Base\Dir::isWritableOrCreatable($tablePath))
        static::catchable(null,'pathNotWritable',$tablePath);

        return $this;
    }


    // getValidateKey
    // retourne une clé de validation dans les attributs
    public function getValidateKey(string $key):string
    {
        return $this->attr(['validateKeys',$key]);
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
    public function extension():array
    {
        $return = $this->attr('extension');

        if(empty($return) && $this->hasVersion())
        $return = static::defaultVersionExtension();

        if(!is_array($return))
        $return = (array) $return;

        return $return;
    }


    // hasDistinctMaxFilesize
    // retourne vrai s'il y a une limite de taille de fichier distincte à la colonne, donc plus petite que php ini
    public function hasDistinctMaxFilesize():bool
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
    public function maxFilesize():int
    {
        return static::makeMaxFilesize($this->attr('maxFilesize'));
    }


    // maxFilesizeFormat
    // retourne le max filesize formaté pour la colonne
    public function maxFilesizeFormat():string
    {
        return Base\Number::sizeFormat($this->maxFilesize());
    }


    // getAmount
    // retourne le nombre de fichiers dans le champ médias
    // peut envoyer une exception
    public function getAmount():int
    {
        $return = $this->attr('media');
        $hasIndex = $this->hasIndex();

        if(!is_int($return) || $return <= 0)
        static::throw('invalidAmount');

        if($hasIndex === false && $return !== 1)
        static::throw('mediaAmountCanOnlyBeOne');

        return $return;
    }


    // indexRange
    // retourne le range des index
    public function indexRange():array
    {
        return range(0,($this->getAmount() - 1));
    }


    // onGet
    // logique onGet pour un champ files
    public function onGet($return,array $option)
    {
        return ($return instanceof Core\Cell\Files)? parent::onGet($return,$option):null;
    }


    // commonFormComplexUpdate
    // génère l'élément de formulaire complexe média lors d'une mise à jour
    protected function commonFormComplexUpdate(?int $index=null,Core\Cell $value,array $attr,array $option):string
    {
        $return = '';
        $hasIndex = $this->hasIndex();
        $table = $this->table();
        $tag = $this->complexTag($attr);
        $allowFileUpload = ($this->allowFileUpload() && Html::isFormTag($tag,true))? true:false;
        $attr['tag'] = $this->attr('complex');
        $lang = $this->db()->lang();
        $i = null;

        if($hasIndex === true)
        {
            $i = ($index + 1);
            $attr['name'] = $this->name()."[$index]";
            $get = $value->get();
            $isEmpty = (is_array($get) && array_key_exists($index,$get))? false:true;
        }

        else
        $isEmpty = $value->isEmpty();

        if($allowFileUpload === true || $isEmpty === false)
        {
            $class = ($isEmpty === true)? 'empty':'not-empty';
            $return .= Html::divOp(['block',$class]);

            if(is_int($i))
            $return .= Html::div(Html::divtable($i),'count');

            if($isEmpty === false)
            {
                if($allowFileUpload === true)
                {
                    $action = '';
                    $isDeleteable = $value->canBeDeleted($index);
                    $isRegenerateable = $value->canBeRegenerated($index);

                    if($isRegenerateable === true)
                    {
                        $data = ['action'=>'regenerate','confirm'=>$lang->text('common/confirm'),'text'=>$lang->text('specific/mediaRegenerate')];
                        $action .= Html::div(null,['icon','solo','action','regenerate','data'=>$data]);
                    }

                    if($isDeleteable === true)
                    {
                        $data = ['action'=>'delete','confirm'=>$lang->text('common/confirm'),'text'=>$lang->text('specific/mediaDelete')];
                        $action .= Html::div(null,['icon','solo','action','remove','data'=>$data]);
                    }

                    $return .= Html::divCond($action,'actions');
                }

                $return .= Html::divCond($this->commonFormComplexUpdateInfo($index,$value,$attr,$option),'info');
                $return .= Html::divCond($this->commonFormComplexUpdateVersion($index,$value,$attr,$option),'versions');
            }

            if($allowFileUpload === true)
            {
                $return .= Html::divOp('form');
                $return .= $this->form(null,$attr,$option);
                $path = ($hasIndex === true)? $value->cellPathBasename($index):$value->cellPathBasename($index);

                $hidden = Base\Json::encode(['action'=>null,'path'=>$path]);
                $return .= $this->formHidden($hidden,Base\Arr::plus($attr,['disabled'=>true]),$option);

                $return .= Html::divOp('message');
                $return .= Html::div(null,'actionText');
                $return .= Html::div(null,['icon','solo','close']);
                $return .= Html::divCl();

                $return .= Html::divCl();
            }

            $return .= Html::divCl();
        }

        return $return;
    }


    // commonFormComplexUpdateInfo
    // génère la partie info du formulaire complexe media
    protected function commonFormComplexUpdateInfo(?int $index=null,Core\Cell $value,array $attr,array $option):string
    {
        $return = '';
        $hasIndex = $this->hasIndex();
        $hasVersion = $this->hasVersion();
        $table = $this->table();
        $file = ($hasIndex === true)? $value->file($index):$value->file();
        $exists = (!empty($file))? $file->isReadable():false;
        $isImage = ($exists === true && $file instanceof Main\File\Image)? true:false;
        $basename = ($exists === true)? $file->basename():false;
        $download = $table->hasPermission('mediaDownload');

        if(is_string($basename))
        $basename = Base\Str::excerpt(50,$basename);
        $html = '';

        if($exists === true)
        {
            $html .= Html::span($basename,'filename');

            if($file->isFilePathToUri())
            $html = Base\Html::a($file,$basename,'filename');

            if($download === true)
            {
                $route = $value->downloadRoute($index);
                $return .= $route->aOpen();
            }

            if($isImage === true)
            {
                if($hasVersion === true && $hasIndex === true)
                $thumbnail = $value->file($index,1);

                elseif($hasVersion === true && $hasIndex === false)
                $thumbnail = $value->file(1);

                else
                $thumbnail = $file;

                $return .= Base\Html::img($thumbnail);
            }
            else
            $return .= Html::div(null,'media-placeholder');

            if($download === true)
            $return .= Html::aCl();

            $html .= Html::span($file->size(true),'filesize');
        }

        else
        {
            $lang = $this->db()->lang();
            $html .= Html::span($lang->text('common/notFound'),'notFound');
            $html .= Html::spanCond($basename,'filename');
        }

        $return .= Html::div($html,'line');

        return $return;
    }


    // commonFormComplexUpdateVersion
    // génère la partie versions du formulaire complexe media
    protected function commonFormComplexUpdateVersion(?int $index=null,Core\Cell $value,array $attr,array $option):string
    {
        $return = '';

        if($this->hasVersion())
        {
            $versions = $this->versions();
            $cellVersions = $value->version($index);
            $lang = $this->db()->lang();

            if(!empty($versions))
            {
                $return .= Html::ulOp();

                foreach ($versions as $key => $array)
                {
                    $file = $cellVersions->get($key);
                    $key = ucfirst($key);

                    $return .= Html::liOp();
                    $return .= Html::span($key.':');

                    if(!empty($file))
                    {
                        if($file->isFilePathToUri())
                        {
                            $uri = Base\Str::excerpt(50,$file->pathToUri());
                            $return .= Base\Html::a($file,$uri);
                        }

                        $return .= Html::span($file->size(true),'filesize');
                    }

                    else
                    $return .= Html::span($lang->text('common/notFound'),'notFound');

                    $return .= Html::liCl();
                }

                $return .= Html::ulCl();
            }
        }

        return $return;
    }


    // hasVersion
    // retourne vrai si l'image a des versions
    public function hasVersion():bool
    {
        return Base\Arrs::is($this->attr('version'));
    }


    // versions
    // retourne le tableau avec les versions à générer pour le fichier
    // envoie une exception si le tableau est mal formatté
    public function versions():?array
    {
        return $this->cache(__METHOD__,function() {
            $return = null;

            if($this->hasVersion())
            {
                $return = [];

                foreach ($this->attr('version') as $key => $value)
                {
                    if(is_string($key) && !empty($value))
                    {
                        $v = static::defaultVersion();
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
    public function checkVersion(array $value):bool
    {
        $return = true;
        $throw = [];

        foreach (['quality','width','height'] as $z)
        {
            if(!is_int($value[$z]) && $value[$z] !== null)
            $throw[] = $z;
        }

        $action = (is_array($value['action']))? current($value['action']):$value['action'];
        if($action !== null && !in_array($action,static::$config['compressAction'],true))
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
    public function version($version=null,bool $exception=true):?array
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
    public function versionKey($version=null,bool $exception=true)
    {
        $return = null;
        $hasVersion = $this->hasVersion();

        if($hasVersion === true)
        {
            $versions = $this->versions();

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
    public function details(bool $lang=true):array
    {
        $return = [];

        $extension = $this->extensionDetails($lang);
        $fileSize = $this->fileSizeDetails($lang);
        $version = array_values((array) $this->versionDetails());
        $parent = parent::details($lang);
        $return = Base\Arr::append($extension,$fileSize,$version,$parent);

        return $return;
    }


    // fileSizeDetails
    // retourne le string pour fileSize admis
    public function fileSizeDetails(bool $lang=true)
    {
        $return = null;
        $maxFilesizeKey = $this->attr('validateKeys/maxFilesize');

        if($this->attr('maxFilesize') !== false)
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
    public function extensionDetails(bool $lang=true)
    {
        $return = null;
        $extension = $this->extension();
        $extensionKey = $this->attr('validateKeys/extension');

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
    public function versionDetails():?array
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
    public function rootPath(bool $shortcut=true):string
    {
        $return = null;
        $path = $this->attr('path');

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
    public function tablePath(bool $shortcut=true):string
    {
        $return = $this->rootPath($shortcut);
        $return = Base\Path::append($return,$this->tableName());

        return $return;
    }


    // formComplex
    // génère un élément de formulaire complexe pour les medias
    public function formComplex($value=true,?array $attr=null,?array $option=null):string
    {
        $return = '';
        $name = $this->name();
        $attr = (array) $attr;
        $option = (array) $option;
        $hasMultiple = $this->hasIndex();
        $this->checkWritable();

        if($value instanceof Core\Cell)
        $return .= $this->formComplexUpdate($value,$attr,$option);

        else
        $return .= $this->formComplexInsert($attr,$option);

        return $return;
    }


    // formComplexInsert
    // génère l'élément de formulaire complexe média lors d'une insertion
    protected function formComplexInsert(array $attr,array $option):string
    {
        $return = '';

        if($this->allowFileUpload())
        {
            $hasMultiple = $this->hasIndex();
            $attr['tag'] = $this->attr('complex');
            $option['multi'] = $hasMultiple;

            foreach($this->indexRange() as $i)
            {
                $int = $i + 1;
                $return .= Html::divOp(['block','empty']);

                if($hasMultiple === true)
                $return .= Html::div(Html::divtable($int),'count');

                $return .= Html::divOp('form');
                $return .= $this->form(null,$attr,$option);
                $return .= Html::divCl();
                $return .= Html::divCl();
            }
        }

        else
        $return = $this->formComplexNothing();

        return $return;
    }


    // checkFilesIndex
    // vérifier que les index du fichier files sont bien valides
    // l'exception est attrapable
    public function checkFilesIndex(Main\Files $files):self
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
    public static function defaultVersion():array
    {
        return static::$config['defaultVersion'];
    }


    // versionDetail
    // génère la string de détail à partir d'un tableau de version
    public static function versionDetail(string $key,array $value):string
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
    public static function makeMaxFilesize($value):int
    {
        $return = null;

        if(is_string($value))
        $value = Base\Number::fromSizeFormat($value);

        if(is_int($value))
        $return = $value;

        if(empty($return))
        $return = Base\Ini::uploadMaxFilesize(1);

        return $return;
    }


    // defaultVersionExtension
    // retourne l'extension par défaut si non spécifié et qu'il y a une version
    public static function defaultVersionExtension():array
    {
        return static::$config['defaultVersionExtension'];
    }


    // defaultConvertExtension
    // retourne l'extension de conversion par défaut pour une version
    public static function defaultConvertExtension():string
    {
        return current(static::defaultVersionExtension());
    }
}

// init
Files::__init();
?>