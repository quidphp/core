<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Lang;
use Quid\Base;

// en
class En extends Base\Lang\En
{
	// trait
	use _overload;


	// config
	public static $config = [
		// common
		'common'=>[
			'and'=>'And',
			'on'=>'On',
			'here'=>'here',
			'page'=>'Page',
			'limit'=>'Per page',
			'table'=>'Table',
			'row'=>'Row',
			'col'=>'Col',
			'add'=>'Add',
			'submit'=>'Submit',
			'modify'=>'Modify',
			'remove'=>'Remove',
			'confirm'=>'Confirm ?',
			'unload'=>'Are you sure you want to leave this page?',
			'first'=>'First',
			'prev'=>'Previous',
			'next'=>'Next',
			'last'=>'Last',
			'back'=>'Back',
			'nothing'=>'Nothing',
			'notFound'=>'Not Found',
			'search'=>'Search',
			'reset'=>'Reset',
			'filter'=>'Filter',
			'download'=>'Download',
			'backToTop'=>'Back to top',
			'print'=>'Print',
			'readMore'=>'Read more',
			'filtered'=>'Filtered',
			'total'=>'Total',
			'view'=>'View',
			'export'=>'Export',
			'insert'=>'Insert',
			'copyright'=>'All rights reserved. &copy; [name] [year]',
			'ago'=>'[value] ago',
			'loadMore'=>'Load more...',
			'captcha'=>'Enter the characters in the image',
			'accessDenied'=>'Access Denied'
		],

		// error
		'error'=>[

			// label
			'label'=>[
				1=>'Error',
				2=>'Notice',
				3=>'Deprecated',
				11=>'Assertion',
				21=>'Silent',
				22=>'Warning',
				23=>'Fatal',
				31=>'Exception',
				32=>'Catchable exception',
				33=>'Database exception',
				34=>'Route exception',
				35=>'Boot exception'
			],

			// page
			'page'=>[

				// content
				'content'=>[
					400=>'The request is invalid and cannot be processed.',
					404=>'This URL does not point to any valid content.'
				],

				'back'=>'Click [link] to go back.'
			]
		],

		// lang
		'lang'=>[
			'fr'=>'French',
			'en'=>'English'
		],

		// langSwitch
		'langSwitch'=>[
			'fr'=>'Français',
			'en'=>'English'
		],

		// relationOrder
		'relationOrder'=>[
			'key'=>[
				1=>'Oldest first',
				2=>'Newest first',
			],

			'value'=>[
				3=>'Alphabetical order',
				4=>'Inverted alphabetical order'
			]
		],

		// login
		'login'=>[
			'usernameEmail'=>'Username or email',
			'remember'=>'Remember me ?',
		],

		// browscap
		'browscap'=>[
			'noscript'=>'Cannot login because JavaScript is disabled on your browser.',
			'cookie'=>'Cannot login because your browser does not accept cookies.'
		],

		// resetPassword
		'resetPassword'=>[
			'forgot'=>'Forgot your password ?',
			'submit'=>'Submit'
		],

		// register
		'register'=>[
			'confirmPassword'=>'Password confirmation'
		],

		// accountChangePassword
		'accountChangePassword'=>[
			'oldPassword'=>'Current password',
			'newPassword'=>'New password',
			'newPasswordConfirm'=>'New password confirmation',
			'submit'=>'Change my password'
		],

		// exception
		'exception'=>[

			// catchableException
			'catchableException'=>[
				'notDeleteable'=>'Row cannot be deleted.',
				'notUpdatable'=>'Row cannot be updated.',
				'hostUnreachable'=>'Connexion error: host [1] is not reachable on port [2].',
				'responseCodeShouldBe'=>'Request error: response code should be [1] not [3].',
				'invalidResponseFormat'=>'Response error: invalid format',
				'userRoleNobody'=>'It is not possible to change the role to nobody.',
				'userRoleSelf'=>'It is not possible to change the role of the current user.',
				'userRoleUpperEqual'=>'It is not possible to assign a role equal or bigger than the role of the current user.',
				'userRoleUpper'=>'It is not possible to assign a role bigger than the role of the current user.',
				'userActiveSelf'=>'It is not possible to change the active status of the current user.',
				'changePasswordInvalidPassword'=>'Invalid password. [1]',
				'changePasswordInvalidOldPassword'=>'Invalid old password. [1]',
				'changePasswordNewPasswordMismatch'=>'The new password confirmation is incorrect',
				'changePasswordOldPasswordMismatch'=>'The old password is incorrect',
				'changePasswordNoChange'=>'The new password is the same as the old one',
				'changePasswordInvalidValues'=>'Invalid values',
				'invalidFileIndex'=>'Impossible to upload a file in index #[1]',
				'pathNotWritable'=>'Path [1] is not writable']
		],

		// direction
		'direction'=>[
			'asc'=>'Asc',
			'desc'=>'Desc',
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
				'email'=>'Email',
				'lang'=>'Language content',
				'redirection'=>'Redirection',
				'log'=>'Log - Event',
				'logCron'=>'Log - Cron',
				'logEmail'=>'Log - Email',
				'logError'=>'Log - Error',
				'logSql'=>'Log - Sql',
				'logHttp'=>'Log - Http',
				'queueEmail'=>'Queue - Email',
				'session'=>'Session',
				'user'=>'User',
				'admin'=>'Admin',
				'system'=>'System'],

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
					'active'=>'Active',
					'featured'=>'Featured',
					'code'=>'Code',
					'category'=>'Category',
					'order'=>'Order',
					'lang'=>'Language',
					'type'=>'Type',
					'status'=>'Status',
					'index'=>'index',
					'role'=>'Role',
					'context'=>'Context',
					'subject'=>'Subject',
					'message'=>'Message',
					'route'=>'Route',
					'method'=>'Method',
					'option'=>'Option',
					'menu'=>'In menu',
					'priority'=>'Priority',
					'data'=>'Data',
					'body'=>'Body',
					'header'=>'Header',
					'phone'=>'Phone number',
					'company'=>'Company',
					'amount'=>'Amount',
					'count'=>'Count',
					'year'=>'Year',
					'month'=>'Month',
					'day'=>'Day',
					'email'=>'Email',
					'ip'=>'IP address',
					'url'=>'Url',
					'uri'=>'Uri',
					'website'=>'Website',
					'media'=>'Media',
					'media_fr'=>'French media',
					'media_en'=>'English media',
					'medias'=>'Medias',
					'video'=>'Video',
					'json'=>'Json',
					'thumbnail'=>'Thumbnail',
					'icon'=>'Icon',
					'icons'=>'Icons',
					'storage'=>'File',
					'storage_fr'=>'French file',
					'storage_en'=>'English file',
					'storages'=>'Files',
					'background'=>'Background',
					'key'=>'Key',
					'name'=>'Name',
					'title'=>'Title',
					'content'=>'Content',
					'firstName'=>'First Name',
					'lastName'=>'Last Name',
					'fullName'=>'Full name',
					'username'=>'Username',
					'userAdd'=>'Added by',
					'userModify'=>'Modified by',
					'password'=>'Password',
					'passwordReset'=>'Password - Reset',
					'country'=>'Country',
					'state'=>'State',
					'province'=>'Province',
					'city'=>'City',
					'zipCode'=>'Zip code',
					'postalCode'=>'Postal code',
					'key_fr'=>'French key',
					'key_en'=>'English key',
					'slug'=>'Slug',
					'slug_fr'=>'French slug',
					'slug_en'=>'English slug',
					'slugPath'=>'Slug path',
					'slugPath_fr'=>'French slug path',
					'slugPath_en'=>'English slug path',
					'fragment'=>'Fragment',
					'fragment_fr'=>'French fragment',
					'fragment_en'=>'English fragment',
					'name_fr'=>'French name',
					'name_en'=>'English name',
					'title_fr'=>'French title',
					'title_en'=>'English title',
					'content_fr'=>'French content',
					'content_en'=>'English content',
					'uri_fr'=>'French Uri',
					'uri_en'=>'English Uri',
					'metaTitle_fr'=>'French meta title',
					'metaTitle_en'=>'English meta title',
					'metaDescription_fr'=>'French meta description',
					'metaDescription_en'=>'English meta description',
					'metaKeywords_fr'=>'French meta keywords',
					'metaKeywords_en'=>'English meta keywords',
					'metaImage_fr'=>'French meta image',
					'metaImage_en'=>'English meta image',
					'metaSearch_fr'=>'French meta search',
					'metaSearch_en'=>'English meta search',
					'media_fr'=>'French media',
					'media_en'=>'English media',
					'video_fr'=>'French video',
					'video_en'=>'English video',
					'json_fr'=>'French json',
					'json_en'=>'English json',
					'timestamp'=>'Timestamp',
					'datetime'=>'Date and time',
					'datetimeStart'=>'Start date and time',
					'datetimeEnd'=>'End date and time',
					'date'=>'Date',
					'dateStart'=>'Start date',
					'dateEnd'=>'End date',
					'time'=>'Time',
					'timeStart'=>'Start time',
					'timeEnd'=>'End time',
					'dateAdd'=>'Date added',
					'dateModify'=>'Last modification',
					'dateExpire'=>'Expiration date',
					'dateLogin'=>'Last login',
					'dateBirth'=>'Birth date',
					'dateSent'=>'Sent date',
					'pointer'=>'Pointer',
					'value'=>'Value',
					'excerpt_fr'=>'French excerpt',
					'excerpt_en'=>'English excerpt',
					'info_fr'=>'French info',
					'info_en'=>'English info',
					'role_fr'=>'French role',
					'role_en'=>'English role',
					'fax'=>'Fax',
					'address'=>'Address',
					'request'=>'Request',
					'error'=>'Error',
					'host'=>'Host',
					'userCommit'=>'Session user',
					'color'=>'Color code',
					'attr'=>'Attribute',
					'visible'=>'Visible',
					'author'=>'Author',
					'price'=>'Price',
					'total'=>'Total',
					'timezone'=>'Timezone'
				],

				// page
				'page'=>[
					'page_id'=>'Parent page'
				]
			],

			// description
			'description'=>[]
		],

		// row
		'row'=>[

			// label
			'label'=>[
				'*'=>'[table] #[primary]'
			],

			// description
			'description'=>[]
		],

		// role
		'role'=>[

			// label
			'label'=>[
				1=>'Nobody',
				10=>'Shared',
				20=>'User',
				50=>'Contributor',
				60=>'Editor',
				70=>'Sub-Admin',
				80=>'Admin',
				90=>'Cron'
			],

			// description
			'description'=>[]
		],

		// route
		'route'=>[

			// label
			'label'=>[
				'account'=>'My account',
				'accountSubmit'=>'My account - Submit',
				'accountChangePassword'=>'Change my password',
				'accountChangePasswordSubmit'=>'Change my password - Submit',
				'activatePassword'=>'Activate password',
				'error'=>'Error',
				'home'=>'Home',
				'login'=>'Login',
				'loginSubmit'=>'Login - Submit',
				'logout'=>'Logout',
				'register'=>'Register',
				'registerSubmit'=>'Register - Submit',
				'resetPassword'=>'Reset password',
				'resetPasswordSubmit'=>'Reset password - Submit',
				'robots'=>'Robots',
				'sitemap'=>'Sitemap'
			],

			// description
			'description'=>[]
		],

		// validate
		'validate'=>[
			'pointer'=>'Must be a valid pointer (table/id)',
			'inRelation'=>'Must be in the relation',

			// tables
			'tables'=>[]
		],

		// required
		'required'=>[
			'tables'=>[
				'formSubmit'=>[
					'json'=>'The form is invalid.'
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
				'captcha'=>'The characters entered do not match the ones in the image.',

				// csrf
				'csrf'=>[
					'retry'=>'Please try this operation again.',
				],

				// genuine
				'genuine'=>[
					'retry'=>'Please try this operation again.',
				],

				// fileUpload
				'fileUpload'=>[
					'maxFilesize'=>'The size of the uploaded file must not be larger than [maxFilesize]',
					'dataLost'=>'The data from the last submitted form has not been saved.'
				],

				// timeout
				'timeout'=>[
					'retry'=>'Please retry this operation later.',
					'login'=>'Session is locked: too many failed login attemps',
				],

				// login
				'login'=>[
					'alreadyConnected'=>'Already logged in',
					'invalidUsername'=>'Invalid username',
					'invalidPassword'=>'Invalid password',
					'userCantLogin'=>'This user cannot login',
					'userInactive'=>'This user is inactive',
					'wrongPassword'=>"The password doesn't match",
					'cantFindUser'=>'This user does not exist',
					'invalidValues'=>'Invalid connection values'
				],

				// userSession
				'userSession'=>[
					'userInactive'=>'This user is now inactive.',
					'loginLifetime'=>'Please reconnect. The session has expired.',
					'mostRecentStorage'=>'Please reconnect. A newer session has been detected for this user.'
				],

				// logout
				'logout'=>[
					'notConnected'=>'Not logged in'
				],

				// resetPassword
				'resetPassword'=>[
					'alreadyConnected'=>'Cannot reset a password from a connected user account',
					'invalidEmail'=>'Invalid email',
					'userInactive'=>'This user is inactive',
					'userCantLogin'=>'This user cannot login',
					'error'=>'Error while doing a password reset',
					'userNotFound'=>'This user does not exist',
					'email'=>'Error while sending the email',
					'invalidValue'=>'Invalid value'
				],

				// activatePassword
				'activatePassword'=>[
					'alreadyConnected'=>'Cannot activate a password from a connected user account',
					'userInactive'=>'This user is inactive',
					'userCantLogin'=>'This user cannot login',
					'invalidHash'=>'Cannot activate this password',
					'error'=>'Error while activating the password',
					'userNotFound'=>'This user does not exist',
					'invalidValue'=>'Invalid values'
				],

				// register
				'register'=>[
					'alreadyConnected'=>'Cannot register from a connected user account',
					'passwordConfirm'=>'The password confirmation is incorrect',
					'invalidValues'=>'Invalid values'
				],

				// user
				'user'=>[

					// welcome
					'welcome'=>[
						'failure'=>'The welcome email was not sent.'
					]
				],

				// form
				'form'=>[
					'invalid'=>'The form is invalid'
				],

				// insert
				'insert'=>[
					'*'=>[
						'exception'=>'[message]',
						'failure'=>'Add failed'
					]
				],

				// update
				'update'=>[
					'*'=>[
						'tooMany'=>'Error: many rows updated',
						'exception'=>'[message]',
						'system'=>'Error system'
					]
				],

				// delete
				'delete'=>[
					'*'=>[
						'notFound'=>'Error: no rows deleted',
						'tooMany'=>'Error: many rows deleted',
						'exception'=>'[message]',
						'system'=>'Error system'
					]
				],

				// truncate
				'truncate'=>[
					'*'=>[
						'exception'=>'[message]',
						'system'=>'Error system'
					]
				],

				// duplicate
				'duplicate'=>[
					'failure'=>'Duplicate has failed'
				]
			],

			// pos
			'pos'=>[

				// login
				'login'=>[
					'success'=>'Logged in'
				],

				// logout
				'logout'=>[
					'success'=>'Logged out'
				],

				// changePassword
				'changePassword'=>[
					'success'=>'Password changed'
				],

				// resetPassword
				'resetPassword'=>[
					'success'=>'The password has been reset and sent to your email'
				],

				// activatePassword
				'activatePassword'=>[
					'success'=>'The password has been activated'
				],

				// media
				'media'=>[
					'delete'=>'[count] file%s% deleted',
					'regenerate'=>'[count] file%s% regenerated'
				],

				// slug
				'slug'=>[
					'updated'=>'[count] other%s% line%s% updated'
				],

				// user
				'user'=>[

					// welcome
					'welcome'=>[
						'success'=>'The welcome email was sent.'
					],
				],

				// insert
				'insert'=>[
					'*'=>[
						'success'=>'Add success'
					]
				],

				// update
				'update'=>[
					'*'=>[
						'success'=>'Modify success',
						'partial'=>'Modify partial success',
						'noChange'=>'No change'
					]
				],

				// delete
				'delete'=>[
					'*'=>[
						'success'=>'Delete success'
					]
				],

				// truncate
				'truncate'=>[
					'*'=>[
						'success'=>'Table has been truncated'
					]
				],

				// duplicate
				'duplicate'=>[
					'success'=>'Duplicate success'
				]
			]
		],

		// relation
		'relation'=>[

			// bool
			'bool'=>[
				0=>'No',
				1=>'Yes'
			],

			// yes
			'yes'=>[
				1=>'Yes'
			],

			// contextType
			'contextType'=>[
				'app'=>'Application',
				'cms'=>'Content management system'
			],

			// contextEnv
			'contextEnv'=>[
				'dev'=>'Development',
				'staging'=>'Staging',
				'prod'=>'Production'
			],

			// emailType
			'emailType'=>[
				1=>'Text',
				2=>'Html',
			],

			// logType
			'logType'=>[
				1=>'Login',
				2=>'Logout',
				3=>'Password reset',
				4=>'Activate password',
				5=>'Change password',
				6=>'Register'
			],

			// logCronType
			'logCronType'=>[
				1=>'Minute',
				2=>'Hour',
				3=>'Day'
			],

			// queueEmailStatus
			'queueEmailStatus'=>[
				1=>'To send',
				2=>'In progress',
				3=>'Error',
				4=>'Sent'
			],

			// logHttpType
			'logHttpType'=>[
				1=>'Unsafe',
				2=>'Redirection',
				3=>'Request',
				4=>'External POST',
				200=>'200 - OK',
				301=>'301 - Moved Permanently',
				302=>'302 - Found',
				400=>'400 - Bad Request',
				404=>'404 - Not Found',
				500=>'500 - Internal Server Error'
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
				'info'=>'Enter your email to get a message explaining how to regenerate the password.'
			],

			// changePassword
			'changePassword'=>[
				'newPassword'=>'New password',
				'newPasswordConfirm'=>'New password confirmation'
			],

			// accountChangePassword
			'accountChangePassword'=>[
				'link'=>'My password',
				'info'=>'Use this form to change the password for the current account.',
				'submit'=>'Modify'
			],

			// author
			'author'=>[
				'name'=>'Quid',
				'uri'=>'https://quid5.com',
				'email'=>'emondpph@gmail.com'
			],

			// about
			'about'=>[
				'content'=>'This open-source Content Management System is based on the QuidPHP framework. The current version is: [version].'
			],

			// footer
			'footer'=>[
				'copyright'=>'Version [version]'
			],

			// home
			'home'=>[
				'searchSubmit'=>'Search in all tables',
				'searchIn'=>'Search in',
				'note'=>'Note',
				'notFound'=>'Nothing',
				'searchNote'=>'Search insensitive to case and accents.',
				'dbName'=>'Database',
				'driver'=>'Driver',
				'serverVersion'=>'Driver version',
				'host'=>'Host',
				'username'=>'Username',
				'charset'=>'Charset',
				'collation'=>'Collation',
				'connectionStatus'=>'Connection status',
				'classDb'=>'Class DB',
				'classTables'=>'Class Tables'
			],

			// general
			'general'=>[
				'notFound'=>'Nothing',
				'table'=>'Table',
				'order'=>'Order',
				'direction'=>'Direction',
				'search'=>'Search',
				'cols'=>'Column',
				'in'=>'In',
				'notIn'=>'Not in',
				'highlight'=>'Highlight',
				'engine'=>'Engine',
				'collation'=>'Collation',
				'autoIncrement'=>'Auto increment',
				'sql'=>'Sql query',
				'searchIn'=>'Search in',
				'reset'=>'Reset',
				'note'=>'Note',
				'searchNote'=>'Search insensitive to case and accents.',
				'primary'=>'Primary key',
				'classTable'=>'Class Table',
				'classRow'=>'Class Row',
				'classRows'=>'Class Rows',
				'classCols'=>'Class Columns',
				'classCells'=>'Class Cells'
			],

			// export
			'export'=>[
				'long'=>'This export may take more than one minute.',
				'encoding'=>'Choose an encoding for the CSV',
				'utf8'=>'UTF-8',
				'latin1'=>'Latin-1',
				'office'=>'Use Latin-1 for use in Microsoft Office on Windows'
			],

			// specific
			'specific'=>[
				'add'=>'Add',
				'name'=>'Name',
				'required'=>'Required',
				'unique'=>'Unique',
				'editable'=>'Editable',
				'pattern'=>'Pattern',
				'preValidate'=>'Pre-validate',
				'validate'=>'Validate',
				'compare'=>'Compare',
				'type'=>'Type',
				'length'=>'Length',
				'unsigned'=>'Unsigned',
				'default'=>'Default',
				'acceptsNull'=>'Accepts NULL',
				'collation'=>'Collation',
				'priority'=>'Priority',
				'orderable'=>'Orderable',
				'filterable'=>'Filterable',
				'searchable'=>'Searchable',
				'classCol'=>'Class Column',
				'classCell'=>'Class Cell',
				'mediaRegenerate'=>'This media will be regenerated on next modification.',
				'mediaDelete'=>'This media will be deleted on next modification.',
				'relationChilds'=>'[count] direct child%s%',
				'modifyTop'=>'Modify',
				'modifyBottom'=>'Modify'
			],

			// table
			'table'=>[

				// label
				'label'=>[],

				// description
				'description'=>[
					'email'=>'Email content sent by the app',
					'lang'=>'All other text content in the application and CMS',
					'redirection'=>'Specifies the redirection from one URL to another',
					'log'=>'Log of activities',
					'logCron'=>'Log of background scripts',
					'logEmail'=>'Log of emails',
					'logError'=>'Log of errors',
					'logHttp'=>'Log of failed HTTP requests',
					'logSql'=>'Log of SQL queries',
					'queueEmail'=>'Email waiting to be sent',
					'session'=>'Active session tracking',
					'user'=>'Users who can access the application and/or CMS']
			],

			// col
			'col'=>[

				// label
				'label'=>[

					// *
					'*'=>[],

					// lang
					'lang'=>[
						'type'=>'Environment'
					],

					// redirection
					'redirection'=>[
						'key'=>'From',
						'value'=>'Tos',
					],

					// session
					'session'=>[
						'sid'=>'Sid'
					],

					// logEmail
					'logEmail'=>[
						'json'=>'Header'
					]
				],

				// description
				'description'=>[

					// *
					'*'=>[
						'id'=>'Primary and unique key. Required',
						'context'=>'Defines the creation context of the element, for administrator.',
						'metaKeywords_fr'=>'Keywords separated by commas, optional field',
						'metaKeywords_en'=>'Keywords separated by commas, optional field',
						'metaDescription_fr'=>'No HTML tags, optional field',
						'metaDescription_en'=>'No HTML tags, optional field',
						'date'=>'Specifies the date to represent the entry',
						'datetimeStart'=>'Specifies the start date of the entry',
						'datetimeEnd'=>'Specifies the end date of the entry',
						'phone'=>'Phone number with or without extension',
						'fax'=>'Fax number with or without extension',
						'address'=>'Complete address',
						'active'=>'An inactive element is not displayed on the site',
						'order'=>'Order of the entry relative to others in the same table',
						'media'=>'Image to represent the entry',
						'medias'=>'Image(s) to represent the entry',
						'thumbnail'=>'Image thumbnail to represent the entry',
						'icon'=>'Icon to represent the entry',
						'background'=>'Links a background image to the entry',
						'video'=>'Links a video to the entry',
						'userAdd'=>'User who added the entry',
						'dateAdd'=>'Date and time when the entry was added',
						'userModify'=>'User who made the last change on the entry',
						'dateModify'=>'Date and time when the last change was made on the entry',
						'slug'=>'URL slug to represent the entry. Empty the field to regenerate it automaticaly.',
						'slug_fr'=>'URL slug to represent the entry. Empty the field to regenerate it automaticaly.',
						'slug_en'=>'URL slug to represent the entry. Empty the field to regenerate it automaticaly.',
						'slugPath'=>'URL slug path to represent the entry. Empty the field to regenerate it automaticaly.',
						'slugPath_fr'=>'URL slug path to represent the entry. Empty the field to regenerate it automaticaly.',
						'slugPath_en'=>'URL slug path to represent the entry. Empty the field to regenerate it automaticaly.',
						'fragment'=>'URL fragment to represent the entry. Empty the field to regenerate it automaticaly.',
						'fragment_fr'=>'URL fragment to represent the entry. Empty the field to regenerate it automaticaly.',
						'fragment_en'=>'URL fragment to represent the entry. Empty the field to regenerate it automaticaly.',
						'key'=>'Key to represent the entry.',
						'key_fr'=>'Key to represent the entry.',
						'key_en'=>'Key to represent the entry.',
						'name'=>'Name to represent the element',
						'name_fr'=>'Name to represent the element',
						'name_en'=>'Name to represent the element',
						'title_fr'=>'Title to represent the element',
						'title_en'=>'Title to represent the element',
						'uri_fr'=>'Uri of the element. Can be a relative or absolute link',
						'uri_en'=>'Uri of the element. Can be a relative or absolute link',
						'excerpt_fr'=>'Short summary of the element, about 2-3 sentences, if empty is generated automatically from the content',
						'excerpt_en'=>'Short summary of the element, about 2-3 sentences, if empty is generated automatically from the content',
						'content_fr'=>'Main content field of the element. Possible to copy and paste from Microsoft Word. Press shift+enter to create a new line within the same tag.',
						'content_en'=>'Main content field of the element. Possible to copy and paste from Microsoft Word. Press shift+enter to create a new line within the same tag.',
						'metaTitle_fr'=>'Meta title for the French page, optional field',
						'metaTitle_en'=>'Meta title for the English page, optional field',
						'metaImage_fr'=>'Meta image to represent the French page, optional field',
						'metaImage_en'=>'Meta image to represent the English page, optional field',
						'metaSearch_fr'=>'Specifies additional French search terms to find this line. This automatically generates.',
						'metaSearch_en'=>'Specifies additional English search terms to find this line. This automatically generates.',
						'user_id'=>'User who created this entry',
						'session_id'=>'Session of the user who created this entry',
						'request'=>'Summary of the HTTP request',
						'userCommit'=>'User of the session',
						'storage'=>'File to link to the entry.',
						'storage_fr'=>'File to link to the entry.',
						'storage_en'=>'File to link to the entry.',
						'storages'=>'File(s) to link to the entry.',
						'pointer'=>'Pointer table -> id. Do not modify, for administrator.',
						'email'=>'Email address of the entry',
						'color'=>'Specify a color code (Hex)',
						'menu'=>'Specifies whether the item should appear in the menu',
						'route'=>'Specify the route to use (for administrator)',
						'method'=>'Specifies the method to use (for administrator)',
						'featured'=>'Specifies whether the item should be featured',
						'website'=>'Web site linked to the element, put the full address',
						'author'=>'Specifies the author of the element',
						'price'=>'Specifies the price of the element',
						'total'=>'Specifies the total of the element',
						'timezone'=>'Specifies the timezone of the element'
					],

					// lang
					'lang'=>[
						'key'=>'Unique key of the text element',
						'type'=>'The text element is accessible within these environments'
					],

					// redirection
					'redirection'=>[
						'key'=>'URL to redirect',
						'value'=>'Destination of the redirection',
						'type'=>'The redirection is active within these environments'
					],

					// user
					'user'=>[
						'role'=>'Role of the user within the site and CMS',
						'username'=>'Must be unique and composed of alphanumeric characters',
						'password'=>'The password must contain a letter, a number and have a length of at least 5 characters',
						'passwordReset'=>'String for the password reset',
						'email'=>'Email for the user',
						'dateLogin'=>'Last login date',
						'firstName'=>'First name of the user',
						'lastName'=>'Last name of the user',
						'fullName'=>'First and last name of the user',
						'timezone'=>'Timezone of the user, leave empty to use the timezone of the server ([timezone])'
					],

					// session
					'session'=>[
						'name'=>'Session and cookie name of the session',
						'sid'=>'Unique id of the session',
						'count'=>'Number of requests made with this session',
						'data'=>'Serialized data of the session',
						'ip'=>'Ip of the session'
					],

					// email
					'email'=>[
						'key'=>'Unique key of the email template',
						'type'=>'Content-Type to be used, for administrator'
					],

					// option
					'option'=>[
						'type'=>'Option type',
						'key'=>'Option key, use /',
						'content'=>'Content of the option, can be json'
					],

					// log
					'log'=>[
						'type'=>'Log type',
						'json'=>'Log data'
					],

					// logSql
					'logSql'=>[
						'type'=>'Type of sql query',
						'json'=>'SQL data - for administrator'
					],

					// logCron
					'logCron'=>[
						'type'=>'Type of cron',
						'json'=>'CRON script data - for administrator'
					],

					// logError
					'logError'=>[
						'type'=>'Type of error',
						'json'=>'Data and backtrace of the error - for administrator'
					],

					// logEmail
					'logEmail'=>[
						'status'=>'Email sending status',
						'email_id'=>'Link to the email template used',
						'json'=>'Email header data',
						'content'=>'Content of the email'
					]
				]
			],

			// panel
			'panel'=>[

				// label
				'label'=>[
					'default'=>'Default',
					'fr'=>'French',
					'en'=>'English',
					'relation'=>'Relation',
					'media'=>'Media',
					'profile'=>'Profile',
					'admin'=>'Administrator',
					'localization'=>'Localisation',
					'contact'=>'Contact',
					'template'=>'Template',
					'visibility'=>'Visibility',
					'meta'=>'Meta',
					'param'=>'Parameters'
				],

				// description
				'description'=>[
					'default'=>'This panel contains the default fields, which do not have a specific panel assigned.',
					'fr'=>'This panel contains the French language fields.',
					'en'=>'This panel contains the English language fields.',
					'relation'=>'This panel contains the fields that have relationships with other tables.',
					'media'=>'This panel contains fields that contain files and media.',
					'admin'=>'This panel contains advanced fields for administrator.',
					'profile'=>'This panel contains the fields related to a user profile.',
					'localization'=>'This panel contains fields related to geolocation.',
					'contact'=>'This panel contains contact fields.',
					'template'=>'This panel contains the fields related to the layout.',
					'visibility'=>'This panel contains the fields related to the visibility of the element.',
					'meta'=>'This panel contains fields related to the metadata of the line',
					'param'=>'This panel contains fields related to the parameters of the line'
				]
			],

			// route
			'route'=>[

				// label
				'label'=>[
					'about'=>'About',
					'general'=>'General',
					'generalDelete'=>'General - Delete',
					'generalExportDialog'=>'Export',
					'generalExport'=>'General - Export',
					'generalRelation'=>'General - Relation',
					'generalTruncate'=>'Empty the table',
					'homeSearch'=>'Home - Search',
					'specific'=>'Specific',
					'specificAdd'=>'Specific - Add',
					'specificAddSubmit'=>'Specific - Add - Submit',
					'specificCalendar'=>'Specific - Calendar',
					'specificDelete'=>'Specific - Delete',
					'specificDispatch'=>'Specific - Dispatch',
					'specificDownload'=>'Specific - Download',
					'specificDuplicate'=>'Dupliquer',
					'specificRelation'=>'Specific - Relation',
					'specificSubmit'=>'Specific - Submit',
					'specificTableRelation'=>'Specific - Table Relation',
					'specificUserWelcome'=>'Welcome'
				],

				// description
				'description'=>[]
			]
		]
	];
}

// config
En::__config();
?>