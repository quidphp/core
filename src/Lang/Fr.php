<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Lang;
use Quid\Orm;

// fr
// french language content used by this namespace
class Fr extends Orm\Lang\Fr
{
    // config
    protected static array $config = [
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
            'accessDenied'=>'Accès interdit',
            'isEmpty'=>'Est vide',
            'isNotEmpty'=>"N'est pas vide"
        ],

        // error
        'error'=>[

            // label
            'label'=>[
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

        // langSwitch
        'langSwitch'=>[
            'fr'=>'Français',
            'en'=>'English'
        ],

        // changePassword
        'changePassword'=>[
            'newPassword'=>'Nouveau mot de passe',
            'newPasswordConfirm'=>'Confirmation du nouveau mot de passe'
        ],

        // exception
        'exception'=>[

            // catchableException
            'catchableException'=>[
                'notUpdateable'=>'La ligne ne peut pas être modifié.',
                'notUpdateableTimestamp'=>'La ligne ne peut pas être modifié car les données sur le serveur sont plus récentes.',
                'notDeleteable'=>'La ligne ne peut pas être effacé.',
                'notDeleteableTimestamp'=>'La ligne ne peut pas être effacé car les données sur le serveur sont plus récentes.',
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

        // label
        'label'=>'QuidPHP',

        // table
        'table'=>[

            // label
            'label'=>[
                'email'=>'Modèle de courriel',
                'lang'=>'Contenu de langue',
                'redirection'=>'Redirection',
                'cacheRoute'=>'Cache - Route',
                'log'=>'Log - Activité',
                'logCron'=>'Log - Cron',
                'logEmail'=>'Log - Courriel',
                'logError'=>'Log - Erreur',
                'logSql'=>'Log - Sql',
                'logHttp'=>'Log - Http',
                'queueEmail'=>'Queue - Courriel',
                'session'=>'Session',
                'user'=>'Utilisateur',
                'system'=>'Système']
        ],

        // col
        'col'=>[

            // label
            'label'=>[

                // *
                '*'=>[
                    'active'=>'Actif',
                    'code'=>'Code',
                    'order'=>'Ordre',
                    'type'=>'Type',
                    'status'=>'Statut',
                    'role'=>'Rôle',
                    'envType'=>'Env et type',
                    'context'=>'Contexte',
                    'route'=>'Route',
                    'data'=>'Données',
                    'count'=>'Compte',
                    'email'=>'Courriel',
                    'ip'=>'Adresse IP',
                    'website'=>'Site Web',
                    'media'=>'Média',
                    'json'=>'Json',
                    'storage'=>'Fichier',
                    'medias'=>'Médias',
                    'storages'=>'Fichiers',
                    'key'=>'Clé',
                    'name'=>'Nom',
                    'content'=>'Contenu',
                    'username'=>"Nom d'utilisateur",
                    'userAdd'=>'Ajouté par',
                    'userModify'=>'Modifié par',
                    'password'=>'Mot de passe',
                    'passwordReset'=>'Réinitialisation du mot de passe',
                    'datetime'=>'Date et heure',
                    'datetimeStart'=>'Date et heure de début',
                    'datetimeEnd'=>'Date et heure de fin',
                    'date'=>'Date',
                    'dateStart'=>'Date de début',
                    'dateEnd'=>'Date de fin',
                    'dateAdd'=>"Date d'ajout",
                    'dateModify'=>'Dernière modification',
                    'dateLogin'=>'Dernière connexion',
                    'pointer'=>'Pointeur',
                    'value'=>'Valeur',
                    'request'=>'Requête',
                    'error'=>'Erreur',
                    'userCommit'=>'Utilisateur session',
                    'timezone'=>'Fuseau horaire',
                    'contentType'=>'Type de contenu'
                ]
            ]
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
            ]
        ],

        // route
        'route'=>[

            // label
            'label'=>[
                'cliCompile'=>'Compiler les ressources',
                'cliPreload'=>'Générer préchargement PHP',
                'cliSessionGc'=>'Nettoyer les sessions',
                'cliVersion'=>'Version',
                'error'=>'Erreur',
                'home'=>'Accueil',
                'robots'=>'Robots',
                'sitemap'=>'Carte du site'
            ],

            // description
            'description'=>[]
        ],

        // validate
        'validate'=>[
            'pointer'=>'Doit être un pointeur valide (table/id)',
            'inRelation'=>'Doit être dans la relation'
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
                    'cannotBeLogin'=>'Cet utilisateur ne peut pas être connecté.',
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
                ]
            ]
        ],

        // relation
        'relation'=>[

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
                6=>'Enregistrement',
                7=>'Formulaire'
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