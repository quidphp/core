<?php 
declare(strict_types=1);
namespace Quid\Core\Lang;
use Quid\Base;

// fr
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
				34=>'Exception de route',
				35=>'Exception de démarrage'
			],
			
			// page
			'page'=>[
				
				// content
				'content'=>[
					400=>'La requête est invalide et ne peut pas être traitée.',
					404=>'Cette URL ne pointe pas vers un contenu valide.'
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
				50=>'Contributeur',
				60=>'Éditeur',
				70=>'Sous-administrateur',
				80=>'Administrateur',
				90=>'Cron'
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
			'contextType'=>[
				'app'=>'Application',
				'cms'=>'Gestionnaire de contenu'
			],
			
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
			
			// logCronType
			'logCronType'=>[
				1=>'Minute',
				2=>'Heure',
				3=>'Jour'
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
				1=>'Non sécuritaire',
				2=>'Redirection',
				3=>'Requête',
				4=>'POST externe',
				200=>'200 - OK',
				301=>'301 - Déplacé en permanence',
				302=>'302 - Trouvé',
				400=>'400 - Mauvaise requête',
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
		],
		
		// cms
		'@cms'=>[
			
			// resetPassword
			'resetPassword'=>[
				'info'=>'Entrer votre courriel pour obtenir un message indiquant la marche à suivre pour regénérer le mot de passe.'
			],
			
			// changePassword
			'changePassword'=>[
				'newPassword'=>'Nouveau mot de passe',
				'newPasswordConfirm'=>'Confirmation du nouveau mot de passe'
			],
			
			// accountChangePassword
			'accountChangePassword'=>[
				'link'=>'Mot de passe',
				'info'=>'Utilisez ce formulaire pour changer le mot de passe du compte courant.',
				'submit'=>'Modifier'
			],
			
			// author
			'author'=>[
				'name'=>'Quid',
				'uri'=>'https://quid5.com',
				'email'=>'emondpph@gmail.com'
			],
			
			// about
			'about'=>[
				'content'=>'Ce gestionnaire de contenu open-source est développé sur le framework Quid 5. La version actuelle est [version].'
			],
			
			// footer
			'footer'=>[
				'copyright'=>'Version [version]'
			],
			
			// home
			'home'=>[
				'searchSubmit'=>'Recherche dans toutes les tables',
				'searchIn'=>'Recherche dans',
				'note'=>'Note',
				'notFound'=>'Rien à afficher',
				'searchNote'=>'Recherche insensible à la case et aux accents.',
				'dbName'=>'Base de données',
				'driver'=>'Connecteur',
				'serverVersion'=>'Version du connecteur',
				'host'=>'Hôte',
				'username'=>"Nom d'utilisateur",
				'charset'=>'Encodage',
				'collation'=>'Collation',
				'connectionStatus'=>'Connexion',
				'classDb'=>'Classe DB',
				'classTables'=>'Classe Tables'
			],
			
			// general
			'general'=>[
				'notFound'=>'Rien à afficher',
				'table'=>'Table',
				'order'=>'Ordre',
				'direction'=>'Direction',
				'search'=>'Recherche',
				'cols'=>'Colonne',
				'in'=>'Dans',
				'notIn'=>'Pas dans',
				'highlight'=>'Surligné',
				'engine'=>'Engin',
				'collation'=>'Collation',
				'autoIncrement'=>'Auto incrément',
				'sql'=>'Requête Sql',
				'searchIn'=>'Recherche dans',
				'reset'=>'Réinitialiser',
				'note'=>'Note',
				'searchNote'=>'Recherche insensible à la case et aux accents.',
				'primary'=>'Clé primaire',
				'classTable'=>'Classe Table',
				'classRow'=>'Classe Ligne',
				'classRows'=>'Classe Lignes',
				'classCols'=>'Classe Colonnes',
				'classCells'=>'Classe Cellules'
			],
			
			// export
			'export'=>[
				'long'=>"Cette exportation peut prendre plus d'une minute.",
				'encoding'=>'Choisir un encodage pour le CSV',
				'utf8'=>'UTF-8',
				'latin1'=>'Latin-1',
				'office'=>'Utilisez Latin-1 pour utilisation dans Microsoft Office sur Windows'
			],
			
			// specific
			'specific'=>[
				'add'=>'Ajout',
				'name'=>'Nom',
				'required'=>'Requis',
				'unique'=>'Unique',
				'editable'=>'Modifiable',
				'pattern'=>'Modèle',
				'preValidate'=>'Pré-validation',
				'validate'=>'Validation',
				'compare'=>'Comparaison',
				'type'=>'Type',
				'length'=>'Longueur',
				'unsigned'=>'Non signé',
				'default'=>'Défaut',
				'acceptsNull'=>'Accepte NULL',
				'collation'=>'Collation',
				'priority'=>'Priorité',
				'orderable'=>'Ordonnable',
				'filterable'=>'Filtrable',
				'searchable'=>'Cherchable',
				'classCol'=>'Classe Colonne',
				'classCell'=>'Classe Cellule',
				'mediaRegenerate'=>'Ce média sera regénéré lors de la prochaine modification.',
				'mediaDelete'=>'Ce média sera effacé lors de la prochaine modification.',
				'relationChilds'=>'[count] enfant%s% direct%s%',
				'modifyTop'=>'Modifier',
				'modifyBottom'=>'Modifier'
			],
			
			// table
			'table'=>[
				
				// label
				'label'=>[],
				
				// description
				'description'=>[
					'email'=>"Contenu relatif aux courriels envoyés par l'application",
					'lang'=>"Tous les autres contenus textes présents dans l'application et le CMS",
					'redirection'=>"Spécifie les redirections d'une URL à une autre",
					'log'=>'Log des activités',
					'logCron'=>"Log des scripts d'arrière-plan",
					'logEmail'=>'Log des envoies courriel',
					'logError'=>'Log des erreurs',
					'logHttp'=>'Log des requêtes HTTP échouées',
					'logSql'=>'Log des requêtes SQL',
					'queueEmail'=>"Email en attente d'envoie",
					'session'=>'Suivi des session actives',
					'user'=>"Utilisateurs pouvant accéder à l'application et/ou au CMS"]
			],
			
			// col
			'col'=>[
				
				// label
				'label'=>[
					
					// *
					'*'=>[],
					
					// lang
					'lang'=>[
						'type'=>'Environnement'
					],
					
					// redirection
					'redirection'=>[
						'key'=>'De',
						'value'=>'Vers',
					],
					
					// session
					'session'=>[
						'sid'=>'Sid'
					],
					
					// logEmail
					'logEmail'=>[
						'json'=>'En-tête'
					]
				],
				
				// description
				'description'=>[
					
					// *
					'*'=>[
						'id'=>'Clé primaire et unique. Obligatoire',
						'context'=>"Défini le contexte de création de l'élément, pour administrateur.",
						'metaKeywords_fr'=>'Mots clefs séparés par des virgules, champ facultatif',
						'metaKeywords_en'=>'Mots clefs séparés par des virgules, champ facultatif',
						'metaDescription_fr'=>'Aucune balise HTML, champ facultatif',
						'metaDescription_en'=>'Aucune balise HTML, champ facultatif',
						'date'=>"Spécifie la date pour représenter l'élément",
						'datetimeStart'=>"Spécifie la date de début de l'élément",
						'datetimeEnd'=>"Spécifie la date de fin de l'élément",
						'phone'=>'Numéro de téléphone avec ou sans extension',
						'fax'=>'Numéro de fax avec ou sans extension',
						'address'=>'Adresse complête',
						'active'=>"Défini si l'élément est actif et doit s'afficher",
						'order'=>"Permet d'ordonner l'élément par rapport aux autres de la même table",
						'media'=>"Image pour représenter l'élément",
						'medias'=>"Image(s) pour représenter l'élément",
						'thumbnail'=>"Image miniature pour représenter l'élément",
						'icon'=>"Icône pour représenter l'élément",
						'background'=>"Lie une image d'arrière-plan à l'élément",
						'video'=>"Lie une vidéo au à l'élément",
						'userAdd'=>"Utilisateur qui a ajouté l'élément",
						'dateAdd'=>"Date et heure de l'ajout de l'élément",
						'userModify'=>"Utilisateur qui a fait la dernière modification sur l'élément",
						'dateModify'=>"Date et heure de la dernière modification sur l'élément",
						'slug'=>"Slug URL pour représenter l'élément. Vider le champ pour le regénérer automatiquement.",
						'slug_fr'=>"Slug URL pour représenter l'élément. Vider le champ pour le regénérer automatiquement.",
						'slug_en'=>"Slug URL pour représenter l'élément. Vider le champ pour le regénérer automatiquement.",
						'slugPath'=>"Slug-chemin URL pour représenter l'élément. Vider le champ pour le regénérer automatiquement.",
						'slugPath_fr'=>"Slug-chemin URL pour représenter l'élément. Vider le champ pour le regénérer automatiquement.",
						'slugPath_en'=>"Slug-chemin URL pour représenter l'élément. Vider le champ pour le regénérer automatiquement.",
						'fragment'=>"Fragment URL pour représenter l'élément. Vider le champ pour le regénérer automatiquement.",
						'fragment_fr'=>"Fragment URL pour représenter l'élément. Vider le champ pour le regénérer automatiquement.",
						'fragment_en'=>"Fragment URL pour représenter l'élément. Vider le champ pour le regénérer automatiquement.",
						'key'=>"Clé pour représenter l'élément.",
						'key_fr'=>"Clé pour représenter l'élément.",
						'key_en'=>"Clé pour représenter l'élément.",
						'name'=>"Nom pour représenter l'élément",
						'name_fr'=>"Nom pour représenter l'élément",
						'name_en'=>"Nom pour représenter l'élément",
						'title_fr'=>"Titre pour représenter l'élément",
						'title_en'=>"Titre pour représenter l'élément",
						'uri_fr'=>"Uri de l'élément. Peut être un lien relatif ou absolut",
						'uri_en'=>"Uri de l'élément. Peut être un lien relatif ou absolut",
						'excerpt_fr'=>"Court résumé de l'élément, environ 2-3 phrases. Si vide se génère automatiquement à partir du contenu",
						'excerpt_en'=>"Court résumé de l'élément, environ 2-3 phrases. Si vide se génère automatiquement à partir du contenu",
						'content_fr'=>"Contenu principal de l'élément. Possible de copier-coller à partir de Microsoft Word. Appuyer shift+enter pour faire un saut de ligne sans créer une nouvelle balise.",
						'content_en'=>"Contenu principal de l'élément. Possible de copier-coller à partir de Microsoft Word. Appuyer shift+enter pour faire un saut de ligne sans créer une nouvelle balise.",
						'metaTitle_fr'=>'Meta titre pour la page française, champ facultatif',
						'metaTitle_en'=>'Meta titre pour la page anglaise, champ facultatif',
						'metaImage_fr'=>'Meta image pour représenter la page française, champ facultatif',
						'metaImage_en'=>'Meta image pour représenter la page anglaise, champ facultatif',
						'metaSearch_fr'=>'Spécifie des termes de recherches français additionnels pour trouver cette ligne. Ce génère automatiquement.',
						'metaSearch_en'=>'Spécifie des termes de recherches anglais additionnels pour trouver cette ligne. Ce génère automatiquement.',
						'user_id'=>"Utilisateur en lien avec l'élément",
						'session_id'=>"Session de l'utilisateur qui a créé l'élément. Pour administrateur",
						'request'=>'Résumé de la requête HTTP',
						'userCommit'=>"Utilisateur de la session de l'utilisateur.",
						'storage'=>"Fichier à lier à l'élément.",
						'storage_fr'=>"Fichier à lier à l'élément.",
						'storage_en'=>"Fichier à lier à l'élément.",
						'storages'=>"Fichiers(s) à lier à l'élément",
						'pointer'=>'Pointeur table -> id. Ne pas modifier, pour administrateur.',
						'email'=>"Adresse courriel de l'élément",
						'color'=>'Spécifier un code couleur (Hex)',
						'menu'=>"Spécifie si l'élément doit s'afficher dans le menu",
						'route'=>'Spécifie la route à utiliser (pour administrateur)',
						'method'=>'Spécifie la méthode à utiliser (pour administrateur)',
						'featured'=>"Spécifie si l'élément doit être placé à la une",
						'website'=>"Site web en lien avec l'élément, mettre l'adresse complête",
						'author'=>"Spécifie l'auteur de l'élément",
						'price'=>"Spécifie le prix de l'élément",
						'total'=>"Spécifie le total de l'élément",
						'timezone'=>"Spécifie le fuseau horaire de l'élément"
					],
					
					// lang
					'lang'=>[
						'key'=>"Clé unique de l'élément texte. Pour administrateur",
						'type'=>"L'élément texte est accessible dans ces environnements. Pour administrateur"
					],
					
					// redirection
					'redirection'=>[
						'key'=>'URL à rediriger',
						'value'=>'Destination de la redirection',
						'type'=>'La redirection est active dans ces environnements'
					],
					
					// user
					'user'=>[
						'role'=>"Rôle de l'utilisateur au sein du site et du CMS",
						'username'=>'Doit être unique et composé de caractère alphanumérique',
						'password'=>"Le mot de passe doit contenir une lettre, un chiffre et avoir une longueur d'au moins 5 caractères",
						'passwordReset'=>'Chaîne pour la réinitialisation du mot de passe',
						'email'=>"Courriel de l'utilisateur",
						'dateLogin'=>'Date de la dernière connexion',
						'firstName'=>"Prénom de l'utilisateur",
						'lastName'=>"Nom de famille de l'utilisateur",
						'fullName'=>"Prénom et nom de famille de l'utilisateur",
						'timezone'=>"Fuseau horaire de l'utilisateur, laisser vide pour utiliser le fuseau horaire du serveur ([timezone])"
					],
					
					// session
					'session'=>[
						'name'=>'Nom de la session et du cookie de la session',
						'sid'=>'Id unique de la session',
						'count'=>'Nombre de requêtes effectués avec cette session',
						'data'=>'Données serializés de la session',
						'ip'=>'Ip de cette session'
					],
					
					// email
					'email'=>[
						'key'=>'Clé unique du modèle de courriel. Pour administrateur',
						'type'=>"Content-Type utilisé lors de l'envoie. Pour administrateur"
					],
					
					// option
					'option'=>[
						'type'=>"Type d'option",
						'key'=>"Clé de l'option, utilisez /",
						'content'=>"Contenu de l'option, peut être du json"
					],
					
					// log
					'log'=>[
						'type'=>'Type du log',
						'json'=>'Données du log'
					],
					
					// logSql
					'logSql'=>[
						'type'=>'Type de la requête',
						'json'=>'Données SQL - pour administrateur'
					],
					
					// logCron
					'logCron'=>[
						'type'=>'Type du cron',
						'json'=>'Données du script CRON  - pour administrateur'
					],
					
					// logError
					'logError'=>[
						'type'=>"Type d'erreur",
						'json'=>"Données et backtrace de l'erreur - pour administrateur"
					],
					
					// logEmail
					'logEmail'=>[
						'status'=>"Statut de l'envoie courriel",
						'email_id'=>'Lien vers le modèle courriel utilisé',
						'json'=>"Données d'en-tête de l'envoie courriel",
						'content'=>'Contenu du courriel'
					]
				]
			],
			
			// panel
			'panel'=>[
				
				// label
				'label'=>[
					'default'=>'Général',
					'fr'=>'Français',
					'en'=>'Anglais',
					'relation'=>'Relation',
					'media'=>'Multimédia',
					'profile'=>'Profil',
					'admin'=>'Administrateur',
					'localization'=>'Localisation',
					'contact'=>'Contact',
					'template'=>'Mise en page',
					'visibility'=>'Visibilité',
					'meta'=>'Meta',
					'param'=>'Paramètres'
				],

				// description
				'description'=>[
					'default'=>"Ce panneau contient les champs par défaut, qui n'ont pas de panneau spécifique attribué.",
					'fr'=>'Ce panneau contient les champs de langue française.',
					'en'=>'Ce panneau contient les champs de langue anglaise.',
					'relation'=>"Ce panneau contient les champs qui entretiennent des relations avec d'autres tables.",
					'media'=>'Ce panneau contient les champs qui contiennent des fichiers et des médias.',
					'admin'=>'Ce panneau contient les champs avancés pour administrateur.',
					'profile'=>"Ce panneau contient les champs en lien avec un profil d'utilisateur.",
					'localization'=>'Ce panneau contient les champs en lien avec la géo-localisation.',
					'contact'=>'Ce panneau contient les champs de contact.',
					'template'=>'Ce panneau contient les champs en lien avec la mise en page.',
					'visibility'=>"Ce panneau contient les champs en lien avec la visibilité de l'élément.",
					'meta'=>'Ce panneau contient des champs en lien avec les méta-données de la ligne',
					'param'=>'Ce panneau contient des champs en lien avec les paramètres de la ligne'
				]
			],
			
			// route
			'route'=>[
				
				// label
				'label'=>[
					'about'=>'À propos',
					'general'=>'Général',
					'generalDelete'=>'Général - Suppression',
					'generalExportDialog'=>'Exportation',
					'generalExport'=>'Général - Exportation',
					'generalRelation'=>'Général - Relation',
					'generalTruncate'=>'Vider la table',
					'homeSearch'=>'Accueil - Recherche',
					'specific'=>'Spécifique',
					'specificAdd'=>'Spécifique - Ajout',
					'specificAddSubmit'=>'Spécifique - Ajout - Soumettre',
					'specificCalendar'=>'Spécifique - Calendrier',
					'specificDelete'=>'Spécifique - Suppression',
					'specificDispatch'=>'Spécifique - Envoi',
					'specificDownload'=>'Spécifique - Téléchargement',
					'specificDuplicate'=>'Dupliquer',
					'specificRelation'=>'Spécifique - Relation',
					'specificSubmit'=>'Spécifique - Soumettre',
					'specificTableRelation'=>'Spécifique - Relation de table',
					'specificUserWelcome'=>'Bienvenue'
				],
				
				// description
				'description'=>[]
			]
		]
	];
}

// config
Fr::__config();
?>