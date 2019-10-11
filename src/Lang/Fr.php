<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Lang;
use Quid\Base;

// fr
// french language content used by this namespace
class Fr extends Base\Lang\Fr
{
    // trait
    use _overload;


    // config
    public static $config = [
        // common
        'common'=>[
            'and'=>'Et',
            'on'=>'Sur',
            'here'=>'Ici',
            'page'=>'Page',
            'limit'=>'Par page',
            'table'=>'Table',
            'row'=>'Ligne',
            'col'=>'Colonne',
            'add'=>'Ajouter',
            'submit'=>'Soumettre',
            'modify'=>'Modifier',
            'remove'=>'Supprimer',
            'confirm'=>'Confirmer ?',
            'unload'=>'Voulez-vous vraiment quitter cette page ?',
            'first'=>'Premier',
            'prev'=>'Précédent',
            'next'=>'Suivant',
            'last'=>'Dernier',
            'back'=>'Retour',
            'nothing'=>'Rien à afficher',
            'notFound'=>'Introuvable',
            'search'=>'Recherche',
            'reset'=>'Réinitialiser',
            'filter'=>'Filtrer',
            'download'=>'Téléchargement',
            'backToTop'=>'Retour en haut',
            'print'=>'Imprimer',
            'readMore'=>'Lire la suite',
            'filtered'=>'Filtré',
            'total'=>'Total',
            'view'=>'Voir',
            'export'=>'Exportation',
            'insert'=>'Insérer',
            'copyright'=>'Tous droits réservés. &copy; [name] [year]',
            'ago'=>'Il y a [value]',
            'loadMore'=>'Charger plus...',
            'captcha'=>"Entrer les caractères dans l'image",
            'accessDenied'=>'Accès interdit'
        ],

        // error
        'error'=>[

            // label
            'label'=>[
                1=>'Erreur',
                2=>'Notification',
                3=>'Déconseillé',
                11=>'Assertion',
                21=>'Erreur silencieuse',
                22=>'Avertissement',
                23=>'Erreur fatale',
                31=>'Exception',
                32=>'Exception attrapable',
                33=>'Exception base de données',
                34=>'Exception attrapable de base de données',
                35=>'Exception de route',
                36=>'Exception fatale de route'
            ],

            // page
            'page'=>[

                // content
                'content'=>[
                    400=>'La requête est invalide et ne peut pas être traitée.',
                    403=>"La requête est invalide car l'accès est interdit.",
                    404=>'Cette URL ne pointe pas vers un contenu valide.',
                    500=>"La requête ne peut être traitée en raison d'une erreur de serveur."
                ],

                'back'=>'Cliquez [link] pour revenir.'
            ]
        ],

        // lang
        'lang'=>[
            'fr'=>'Français',
            'en'=>'Anglais'
        ],

        // langSwitch
        'langSwitch'=>[
            'fr'=>'Français',
            'en'=>'English'
        ],

        // relationOrder
        'relationOrder'=>[
            'key'=>[
                1=>'Plus ancien en premier',
                2=>'Plus récent en premier',
            ],

            'value'=>[
                3=>'Ordre alphabétique',
                4=>'Ordre alphabétique inversé'
            ]
        ],

        // login
        'login'=>[
            'usernameEmail'=>"Nom d'utilisateur ou courriel",
            'remember'=>'Se souvenir de moi?'
        ],

        // browscap
        'browscap'=>[
            'noscript'=>'Impossible de se connecter car JavaScript est désactivé sur votre navigateur.',
            'cookie'=>"Impossible de se connecter car votre navigateur n'accepte pas les cookies."
        ],

        // resetPassword
        'resetPassword'=>[
            'forgot'=>'Mot de passe oublié ?',
            'submit'=>'Soumettre'
        ],

        // register
        'register'=>[
            'confirmPassword'=>'Confirmation du mot de passe'
        ],

        // changePassword
        'changePassword'=>[
            'newPassword'=>'Nouveau mot de passe',
            'newPasswordConfirm'=>'Confirmation du nouveau mot de passe'
        ],

        // accountChangePassword
        'accountChangePassword'=>[
            'oldPassword'=>'Mot de passe actuel',
            'newPassword'=>'Nouveau mot de passe',
            'newPasswordConfirm'=>'Confirmation du nouveau mot de passe',
            'submit'=>'Modifier mon mot de passe'
        ],

        // exception
        'exception'=>[

            // catchableException
            'catchableException'=>[
                'notDeleteable'=>'La ligne ne peut pas être effacé.',
                'notUpdatable'=>'La ligne ne peut pas être modifié.',
                'hostUnreachable'=>"Erreur lors de la connexion: l'hôte [1] n'est pas joignable sur le port [2].",
                'responseCodeShouldBe'=>'Erreur dans la requête: le code de la réponse devait être [1] et non [3].',
                'invalidResponseFormat'=>'Erreur dans la réponse: format invalide',
                'userRoleNobody'=>"Il n'est pas possible d'attribuer le rôle nobody.",
                'userRoleSelf'=>"Il n'est pas possible de modifier le rôle de l'utilisateur courant.",
                'userRoleUpperEqual'=>"Il n'est pas possible d'affecter un rôle égal ou plus grand que le rôle de l'utilisateur courant.",
                'userRoleUpper'=>"Il n'est pas possible d'affecter un rôle plus grand que le rôle de l'utilisateur courant.",
                'userActiveSelf'=>"Il n'est pas possible de changer le statut actif de l'utilisateur courant.",
                'changePasswordInvalidPassword'=>'Mot de passe invalide. [1]',
                'changePasswordInvalidOldPassword'=>'Ancien mot de passe invalide. [1]',
                'changePasswordNewPasswordMismatch'=>'La confirmation du nouveau mot de passe est incorrect',
                'changePasswordOldPasswordMismatch'=>"L'ancien mot de passe est incorrect",
                'changePasswordNoChange'=>"Le nouveau mot de passe est le même que l'ancien",
                'changePasswordInvalidValues'=>'Valeurs fournies invalides',
                'invalidFileIndex'=>"Impossible de charger un fichier dans l'index #[1]",
                'pathNotWritable'=>"Le chemin [1] n'est pas accessible en écriture"]
        ],

        // direction
        'direction'=>[
            'asc'=>'Ascendant',
            'desc'=>'Descendant',
        ],

        // label
        'label'=>'Quid',

        // db
        'db'=>[

            // label
            'label'=>[],

            // description
            'description'=>[]
        ],

        // table
        'table'=>[

            // label
            'label'=>[
                'email'=>'Modèle de courriel',
                'lang'=>'Contenu de langue',
                'redirection'=>'Redirection',
                'log'=>'Log - Activité',
                'logCron'=>'Log - Cron',
                'logEmail'=>'Log - Courriel',
                'logError'=>'Log - Erreur',
                'logSql'=>'Log - Sql',
                'logHttp'=>'Log - Http',
                'queueEmail'=>'Queue - Courriel',
                'session'=>'Session',
                'user'=>'Utilisateur',
                'admin'=>'Administration',
                'system'=>'Système'],

            // description
            'description'=>[]
        ],

        // col
        'col'=>[

            // label
            'label'=>[

                // *
                '*'=>[
                    'id'=>'Id',
                    'active'=>'Actif',
                    'featured'=>'En vedette',
                    'code'=>'Code',
                    'category'=>'Catégorie',
                    'order'=>'Ordre',
                    'lang'=>'Langue',
                    'type'=>'Type',
                    'status'=>'Statut',
                    'index'=>'index',
                    'role'=>'Rôle',
                    'context'=>'Contexte',
                    'subject'=>'Sujet',
                    'message'=>'Message',
                    'route'=>'Route',
                    'method'=>'Méthode',
                    'option'=>'Option',
                    'menu'=>'Dans le menu',
                    'priority'=>'Priorité',
                    'data'=>'Données',
                    'body'=>'Corps',
                    'header'=>'En-tête',
                    'phone'=>'Téléphone',
                    'company'=>'Compagnie',
                    'amount'=>'Montant',
                    'count'=>'Compte',
                    'year'=>'Année',
                    'month'=>'Mois',
                    'day'=>'Jour',
                    'email'=>'Courriel',
                    'ip'=>'Adresse IP',
                    'url'=>'Url',
                    'uri'=>'Uri',
                    'website'=>'Site Web',
                    'media'=>'Média',
                    'media_fr'=>'Média français',
                    'media_en'=>'Média anglais',
                    'medias'=>'Médias',
                    'video'=>'Vidéo',
                    'json'=>'Json',
                    'thumbnail'=>'Miniature',
                    'icon'=>'Icône',
                    'icons'=>'Icônes',
                    'storage'=>'Fichier',
                    'storage_fr'=>'Fichier français',
                    'storage_en'=>'Fichier anglais',
                    'storages'=>'Fichiers',
                    'background'=>'Arrière-plan',
                    'key'=>'Clé',
                    'name'=>'Nom',
                    'title'=>'Titre',
                    'content'=>'Contenu',
                    'firstName'=>'Prénom',
                    'lastName'=>'Nom de famille',
                    'fullName'=>'Nom complet',
                    'username'=>"Nom d'utilisateur",
                    'userAdd'=>'Ajouté par',
                    'userModify'=>'Modifié par',
                    'password'=>'Mot de passe',
                    'passwordReset'=>'Réinitialisation du mot de passe',
                    'country'=>'Pays',
                    'state'=>'État',
                    'province'=>'Province',
                    'city'=>'Ville',
                    'zipCode'=>'Zip code',
                    'postalCode'=>'Code postal',
                    'key_fr'=>'Clé française',
                    'key_en'=>'Clé anglaise',
                    'slug'=>'Slug',
                    'slug_fr'=>'Slug français',
                    'slug_en'=>'Slug anglais',
                    'slugPath'=>'Slug-chemin',
                    'slugPath_fr'=>'Slug-chemin français',
                    'slugPath_en'=>'Slug-chemin anglais',
                    'fragment'=>'Fragment',
                    'fragment_fr'=>'Fragment français',
                    'fragment_en'=>'Fragment anglais',
                    'name_fr'=>'Nom français',
                    'name_en'=>'Nom anglais',
                    'title_fr'=>'Titre français',
                    'title_en'=>'Titre anglais',
                    'content_fr'=>'Contenu français',
                    'content_en'=>'Contenu anglais',
                    'uri_fr'=>'Uri française',
                    'uri_en'=>'Uri anglaise',
                    'metaTitle_fr'=>'Meta titre français',
                    'metaTitle_en'=>'Meta titre anglais',
                    'metaDescription_fr'=>'Meta description française',
                    'metaDescription_en'=>'Meta description anglaise',
                    'metaKeywords_fr'=>'Meta mots clefs français',
                    'metaKeywords_en'=>'Meta mots clefs anglais',
                    'metaImage_fr'=>'Meta image français',
                    'metaImage_en'=>'Meta image anglais',
                    'metaSearch_fr'=>'Meta recherche français',
                    'metaSearch_en'=>'Meta recherche anglais',
                    'media_fr'=>'Média français',
                    'media_en'=>'Média anglais',
                    'video_fr'=>'Vidéo français',
                    'video_en'=>'Vidéo anglais',
                    'json_fr'=>'Json français',
                    'json_en'=>'Json anglais',
                    'timestamp'=>'Code temps',
                    'datetime'=>'Date et heure',
                    'datetimeStart'=>'Date et heure de début',
                    'datetimeEnd'=>'Date et heure de fin',
                    'date'=>'Date',
                    'dateStart'=>'Date de début',
                    'dateEnd'=>'Date de fin',
                    'time'=>'Heure',
                    'timeStart'=>'Heure de début',
                    'timeEnd'=>'Heure de fin',
                    'dateAdd'=>"Date d'ajout",
                    'dateModify'=>'Dernière modification',
                    'dateExpire'=>"Date d'expiration",
                    'dateLogin'=>'Dernière connexion',
                    'dateBirth'=>'Date de naissance',
                    'dateSent'=>"Date d'envoie",
                    'pointer'=>'Pointeur',
                    'value'=>'Valeur',
                    'excerpt_fr'=>'Résumé français',
                    'excerpt_en'=>'Résumé anglais',
                    'info_fr'=>'Information française',
                    'info_en'=>'Information anglaise',
                    'role_fr'=>'Rôle français',
                    'role_en'=>'Rôle anglais',
                    'fax'=>'Fax',
                    'address'=>'Adresse',
                    'request'=>'Requête',
                    'error'=>'Erreur',
                    'host'=>'Hôte',
                    'userCommit'=>'Utilisateur session',
                    'color'=>'Code couleur',
                    'attr'=>'Attribut',
                    'visible'=>'Visible',
                    'author'=>'Auteur',
                    'price'=>'Prix',
                    'total'=>'Total',
                    'timezone'=>'Fuseau horaire'
                ],

                // page
                'page'=>[
                    'page_id'=>'Page parent'
                ]
            ],

            // description
            'description'=>[]
        ],

        // row
        'row'=>[

            // label
            'label'=>[
                '*'=>'[table] #[primary]',
            ],

            // description
            'description'=>[]
        ],

        // role
        'role'=>[

            // label
            'label'=>[
                1=>'Nobody',
                10=>'Compte public',
                20=>'Utilisateur',
                80=>'Administrateur',
                90=>'Cli'
            ],

            // description
            'description'=>[]
        ],

        // route
        'route'=>[

            // label
            'label'=>[
                'account'=>'Mon compte',
                'accountSubmit'=>'Mon compte - Soumettre',
                'accountChangePassword'=>'Changer mon mot de passe',
                'accountChangePasswordSubmit'=>'Changer mon mot de passe - Soumettre',
                'activatePassword'=>'Activer le mot de passe',
                'error'=>'Erreur',
                'home'=>'Accueil',
                'login'=>'Connexion',
                'loginSubmit'=>'Connexion - Soumettre',
                'logout'=>'Déconnexion',
                'register'=>'Enregistrement',
                'registerSubmit'=>'Enregistrement - Soumettre',
                'resetPassword'=>'Régénérer un nouveau mot de passe',
                'resetPasswordSubmit'=>'Régénérer un nouveau mot de passe - Soumettre',
                'robots'=>'Robots',
                'sitemap'=>'Carte du site'
            ],

            // description
            'description'=>[]
        ],

        // validate
        'validate'=>[
            'pointer'=>'Doit être un pointeur valide (table/id)',
            'inRelation'=>'Doit être dans la relation',

            // tables
            'tables'=>[]
        ],

        // required
        'required'=>[
            'tables'=>[
                'formSubmit'=>[
                    'json'=>'Le formulaire est invalide.'
                ]
            ]
        ],

        // unique
        'unique'=>[
            'tables'=>[]
        ],

        // editable
        'editable'=>[
            'tables'=>[]
        ],

        // compare
        'compare'=>[
            'tables'=>[]
        ],

        // com
        'com'=>[

            // neg
            'neg'=>[

                // captcha
                'captcha'=>"Les caractères entrés ne correspondent pas à l'image.",

                // csrf
                'csrf'=>[
                    'retry'=>'Svp réessayer cette opération une autre fois.',
                ],

                // genuine
                'genuine'=>[
                    'retry'=>'Svp réessayer cette opération une autre fois.',
                ],

                // fileUpload
                'fileUpload'=>[
                    'maxFilesize'=>'La taille du fichier téléversé ne doit pas dépasser [maxFilesize].',
                    'dataLost'=>"Les données du dernier formulaire soumis n'ont pas été sauvegardées."
                ],

                // timeout
                'timeout'=>[
                    'retry'=>'Merci de réessayer cette opération plus tard.',
                    'login'=>'La session est verouillée: trop de tentative de connexion',
                ],

                // login
                'login'=>[
                    'alreadyConnected'=>'Déjà connecté',
                    'invalidUsername'=>"Nom d'utilisateur invalide",
                    'invalidPassword'=>'Mot de passe invalide',
                    'userCantLogin'=>"L'utilisateur ne peut pas se connecter",
                    'userInactive'=>"L'utilisateur est inactif",
                    'wrongPassword'=>'Le mot de passe ne concorde pas',
                    'cantFindUser'=>"L'utilisateur n'existe pas",
                    'invalidValues'=>'Valeurs de connexion invalides'
                ],

                // userSession
                'userSession'=>[
                    'userInactive'=>'Cet utilisateur est maintenant inactif.',
                    'loginLifetime'=>'Svp vous reconnecter. La durée de vie de la session a été dépassée.',
                    'mostRecentStorage'=>'Svp vous reconnecter. Une session plus récente a été détectée pour cet utilisateur.'
                ],

                // logout
                'logout'=>[
                    'notConnected'=>'Pas connecté'
                ],

                // resetPassword
                'resetPassword'=>[
                    'alreadyConnected'=>"Impossible de regénérer un mot de passe à partir d'un utilisateur connecté",
                    'invalidEmail'=>'Le courriel est invalide',
                    'userInactive'=>"L'utilisateur est inactif",
                    'userCantLogin'=>"L'utilisateur ne peut pas se connecter",
                    'error'=>'Impossible de regénérer le mot de passe',
                    'userNotFound'=>"L'utilisateur n'existe pas",
                    'email'=>"Erreur lors de l'envoie courriel",
                    'invalidValue'=>'Valeur fournie invalide'
                ],

                // activatePassword
                'activatePassword'=>[
                    'alreadyConnected'=>"Impossible d'activer un mot de passe à partir d'un utilisateur connecté",
                    'userInactive'=>"L'utilisateur est inactif",
                    'userCantLogin'=>"L'utilisateur ne peut pas se connecter",
                    'invalidHash'=>"Impossible d'activer le mot de passe",
                    'error'=>"Impossible d'activer le mot de passe",
                    'userNotFound'=>"L'utilisateur n'existe pas",
                    'invalidValue'=>'Valeurs fournies invalides'
                ],

                // register
                'register'=>[
                    'alreadyConnected'=>"Impossible de faire un enregistrement à partir d'un utilisateur connecté",
                    'passwordConfirm'=>'La confirmation du mot de passe est incorrect',
                    'invalidValues'=>'Valeurs fournies invalides'
                ],

                // user
                'user'=>[

                    // welcome
                    'welcome'=>[
                        'failure'=>"Le courriel de bienvenue n'a pas pu être envoyé."
                    ]
                ],

                // form
                'form'=>[
                    'invalid'=>'Le formulaire est invalide'
                ],

                // insert
                'insert'=>[
                    '*'=>[
                        'exception'=>'[message]',
                        'failure'=>'Ajout non effectué'
                    ]
                ],

                // update
                'update'=>[
                    '*'=>[
                        'tooMany'=>'Erreur: plusieurs lignes modifiés',
                        'exception'=>'[message]',
                        'system'=>'Erreur système'
                    ]
                ],

                // delete
                'delete'=>[
                    '*'=>[
                        'notFound'=>'Erreur: aucune ligne effacée',
                        'tooMany'=>'Erreur: plusieurs lignes effacées',
                        'exception'=>'[message]',
                        'system'=>'Erreur système'
                    ]
                ],

                // truncate
                'truncate'=>[
                    '*'=>[
                        'exception'=>'[message]',
                        'system'=>'Erreur système'
                    ]
                ],

                // duplicate
                'duplicate'=>[
                    'failure'=>'La duplication a échouée'
                ]
            ],

            // pos
            'pos'=>[

                // login
                'login'=>[
                    'success'=>'Connexion réussie'
                ],

                // logout
                'logout'=>[
                    'success'=>'Déconnexion réussie'
                ],

                // changePassword
                'changePassword'=>[
                    'success'=>'Changement de mot de passe réussi'
                ],

                // resetPassword
                'resetPassword'=>[
                    'success'=>'Le mot de passe a été regénéré et envoyé à votre courriel'
                ],

                // activatePassword
                'activatePassword'=>[
                    'success'=>'Le mot de passe regénéré a été activé'
                ],

                // media
                'media'=>[
                    'delete'=>'[count] fichier%s% effacé%s%',
                    'regenerate'=>'[count] fichier%s% regénéré%s%'
                ],

                // slug
                'slug'=>[
                    'updated'=>'[count] autre%s% ligne%s% mise%s% à jour'
                ],

                // user
                'user'=>[

                    // welcome
                    'welcome'=>[
                        'success'=>'Le courriel de bienvenue a été envoyé.'
                    ],
                ],

                // insert
                'insert'=>[
                    '*'=>[
                        'success'=>'Ajout effectué'
                    ]
                ],

                // update
                'update'=>[
                    '*'=>[
                        'success'=>'Modification effectuée',
                        'partial'=>'Modification partielle effectuée',
                        'noChange'=>'Aucun changement'
                    ]
                ],

                // delete
                'delete'=>[
                    '*'=>[
                        'success'=>'Suppression effectuée'
                    ]
                ],

                // truncate
                'truncate'=>[
                    '*'=>[
                        'success'=>'La table a été vidée'
                    ]
                ],

                // duplicate
                'duplicate'=>[
                    'success'=>'La duplication a réussie'
                ]
            ]
        ],

        // relation
        'relation'=>[

            // bool
            'bool'=>[
                0=>'Non',
                1=>'Oui'
            ],

            // yes
            'yes'=>[
                1=>'Oui'
            ],

            // contextType
            'contextType'=>[],

            // contextEnv
            'contextEnv'=>[
                'dev'=>'Développement',
                'staging'=>'Test',
                'prod'=>'Production'
            ],

            // emailType
            'emailType'=>[
                1=>'Texte',
                2=>'Html',
            ],

            // logType
            'logType'=>[
                1=>'Connexion',
                2=>'Déconnexion',
                3=>'Réinitialisation du mot de passe',
                4=>'Activation du mot de passe',
                5=>'Changement du mot de passe',
                6=>'Enregistrement'
            ],

            // queueEmailStatus
            'queueEmailStatus'=>[
                1=>'À envoyer',
                2=>'Envoie en cours',
                3=>'Erreur',
                4=>'Envoyé'
            ],

            // logHttpType
            'logHttpType'=>[
                1=>'Requête non sécuritaire',
                2=>'Requête invalide',
                3=>'POST externe',
                200=>'200 - OK',
                301=>'301 - Déplacé en permanence',
                302=>'302 - Trouvé',
                400=>'400 - Mauvaise requête',
                403=>'403 - Interdit',
                404=>'404 - Pas trouvé',
                500=>'500 - Erreur interne du serveur'
            ],

            // logSqlType
            'logSqlType'=>[
                1=>'Select',
                2=>'Show',
                3=>'Insert',
                4=>'Update',
                5=>'Delete',
                6=>'Create',
                7=>'Alter',
                8=>'Truncate',
                9=>'Drop'
            ]
        ]
    ];
}

// init
Fr::__init();
?>