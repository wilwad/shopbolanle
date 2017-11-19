<?php
/*
 * Define user rights for the menus
 */

// defined rights

/* entry forms */
$arr_forms =    [
					'employee'           =>['icon'=>'fa fa-fw fa-user','title'=>'add application','intro-text'=>"Add a new citizenship registration application to the system.",'url'=>'new-citizenship'],
					'OMAs'             =>['icon'=>'fa fa-fw fa-bank','title'=>'add application','intro-text'=>"Reapply for a citizenship document.",                          'url'=>'new-duplicate']                              
];

/* manage these fields */
$arr_manage =    [
					'Asset types'  =>['icon'=>'fa fa-fw fa-wrench','title'=>'add application','intro-text'=>"Add a new citizenship registration application to the system.",'url'=>'new-citizenship'],
					'Sources of finance'  =>['icon'=>'fa fa-fw fa-usd','title'=>'add application','intro-text'=>"Add a new citizenship registration application to the system.",'url'=>'new-citizenship'],
					'OMAs'      =>['icon'=>'fa fa-fw fa-bank','title'=>'add application','intro-text'=>"Reapply for a citizenship document.",                          'url'=>'new-duplicate']                              
];

/* profile management */
$arr_profile = [		 
                  /*'messages'=>['icon'=>'fa fa-fw fa-comment-o', 'title'=>'Messages','intro-text'=>"Read and respond to messages from other users. "],	*/
                  'Home'=>['icon'=>'fa fa-fw fa-home', 'url'=>'home', 'title'=>'Home panel','intro-text'=>"Your home panel showing all available options and tasks."],								
                  'My profile'=>['icon'=>'fa fa-fw fa-user', 'title'=>'update your account', 'intro-text'=>"Change your login information and personal details.",'url'=>'profile'],
                  'Added by me'=>['icon'=>'fa fa-fw fa-plus', 'title'=>'documents added by you', 'intro-text'=>"Show documents added to the system by you only.",'url'=>'my-documents'],
                  'Locked by me'=>['icon'=>'fa fa-fw fa-lock', 'title'=>'documents locked by you', 'intro-text'=>"Show documents locked by me.",'url'=>'my-locked-documents'],
                  'help wizard'=>['icon'=>'fa fa-fw fa-life-ring', 'title'=>'start the system help wizard','intro-text'=>"Start the help wizard to guide you on how to use the system.",'url'=>'ignored'],
                  'user manual'=>['icon'=>'fa fa-fw fa-book', 'title'=>'User manual', 'intro-text'=>"Open the system user manual.","url"=>'user-manual'],                              
                  'sign out'=>['icon'=>'fa fa-fw fa-sign-out', 'title'=>'log out of your account', 'intro-text'=>"Log out of your account and view the login page.",'url'=>'xx']
				  ];

/* tasks */
$arr_dashboard =    [
          					 'dashboard'=>['icon'=>'fa fa-fw fa-list-alt', 'title'=>'View your dashboard', 'intro-text'=>"View a graphical breakdown of vital insights on system data.",'url'=>'dashboard']
					           ];

/* tasks */
$arr_declarations =    [
		        				 'By Employee'=>['icon'=>'fa fa-fw fa-file', 'title'=>'signatories to agreements', 'intro-text'=>"agreements", 'url'=>'agreements'],	
		        				 'By OMAs'=>['icon'=>'fa fa-fw fa-file', 'title'=>'mous','url'=>'mou', 'intro-text'=>"Memorandums of understandings.",'url'=>'mou'],
		        				 'By Year'=>['icon'=>'fa fa-fw fa-file', 'title'=>'legal opinions and advice', 'intro-text'=>"legal opinions-advise",'url'=>'legal-opinions-advice']                                        
		                ];

/* settings */
$arr_settings = [
                 'query builder'=>['icon'=>"fa fa-fw fa-filter",'title'=>'manage database queries',
                 'intro-text'=>"Add or edit SQL queries against the database.",'url'=>'queries'],
                 'database'=>['icon'=>"fa fa-fw fa-database",'title'=>'manage database', 'intro-text'=>"Manage the database.",'url'=>'database'],
				     'table size'=>['icon'=>"fa fa-fw fa-table",'title'=>'view tables', 'intro-text'=>"View tables.",'url'=>'table-size'],
                 'system'=>['icon'=>"fa fa-fw fa-wrench",'title'=>'manage system settings', 'intro-text'=>"Manage other aspects of the system.",'url'=>'system'],
                 'users'=>['icon'=>'fa fa-fw fa-users', 'title'=>'manage users and user groups','intro-text'=>"Add, edit or delete users and user groups.",'url'=>'users']
                ];

/* searching */
$arr_filter = ['All employees'=>['icon'=>"fa fa-fw fa-file", 'title'=>'view all documents on the system','intro-text'=>"View all the documents on the system.",'url'=>'documents-all'],
               'search'=>['icon'=>'fa fa-fw fa-search', 'title'=>'search for applications', 'intro-text'=>"Search the database for applications",'url'=>'search'],
               'queries view'=>['icon'=>"fa fa-fw fa-filter",'title'=>'run database queries','intro-text'=>"Run saved SQL queries against the database.",'url'=>'queries-view']									   
              ];

/* reporting */
$arr_reports = [
                 /*'statistics'=>['icon'=>'fa fa-fw fa-bar-chart', 'title'=>'view application statistics',
                                 'intro-text'=>"View applications statistics"],*/
                 'queries-view'=>['icon'=>"fa fa-fw fa-filter",'title'=>'run database queries', 'intro-text'=>"Run saved SQL queries against the database.",'url'=>'queries-view']
                ];

/* development */
$arr_reportbug =    [
					 'report a bug'=>['icon'=>'fa fa-fw fa-edit', 'title'=>'Report a bug', 'intro-text'=>"Report a system malfunction, suggest a feature.",'url'=>'bug-reporter'],					 
					 /*'dump-schema'=>['icon'=>'fa fa-fw fa-database', 'title'=>'Dump database schema',
									 'intro-text'=>"Dump the database schema"],
					 'db-backup'=>['icon'=>'fa fa-fw fa-database', 'title'=>'Backup database',
									 'intro-text'=>"Backup the database"]	*/								 
					];			

/* backup ops */
$arr_tasks_backup = [
                 'database'=>['icon'=>"fa fa-fw fa-database",'title'=>'backup database', 'intro-text'=>"Backup the database.",'url'=>'database', 'url'=>'database']
                ];
					
// rights for users
$role_rights = [
		         'administrators' => [
										'dashboard'=>['icon'=>'fa fa-fw fa-dashboard', 'menu'=>$arr_dashboard],
									   'declarations'=>['icon'=>'fa fa-fw fa-list', 'menu'=>$arr_declarations],										
										'add a new'=>['icon'=>'fa fa-fw fa-plus', 'menu'=>$arr_forms],  
										'manage'=>['icon'=>'fa fa-fw fa-wrench', 'menu'=>$arr_manage],              
										'filter'=>['icon'=>'fa fa-fw fa-search', 'menu'=>$arr_filter],
										/*'reports'=>['icon'=>'fa fa-fw fa-bar-chart', 'menu'=>$arr_reports],*/
										
										'settings'=>['icon'=>'fa fa-fw fa-wrench', 'menu'=>$arr_settings],
										/*'system bugs'=>['icon'=>'fa fa-fw fa-bug', 'menu'=>$arr_reportbug],*/
										'profile'=>['icon'=>'fa fa-fw fa-user', 'menu'=>$arr_profile]
		                             ],
		
					'top_levels' =>[
										  'dashboard'=>['icon'=>'fa fa-fw fa-dashboard', 'menu'=>$arr_dashboard],
										  'declarations'=>['icon'=>'fa fa-fw fa-list', 'menu'=>$arr_declarations],
										  'add a new'=>['icon'=>'fa fa-fw fa-plus', 'menu'=>$arr_forms],
										  'manage'=>['icon'=>'fa fa-fw fa-wrench', 'menu'=>$arr_manage],
										  'filter'=>['icon'=>'fa fa-fw fa-search', 'menu'=>$arr_filter],
										  /*'reports'=>['icon'=>'fa fa-fw fa-bar-chart', 'menu'=>$arr_reports],*/
										  'profile'=>['icon'=>'fa fa-fw fa-user', 'menu'=>$arr_profile]
		                                ],
		         'receptionists' => [
									  'dashboard'=>['icon'=>'fa fa-fw fa-dashboard', 'menu'=>$arr_dashboard],
									  'declarations'=>['icon'=>'fa fa-fw fa-tasks', 'menu'=>$arr_declarations],
									  'add a new'=>['icon'=>'fa fa-fw fa-plus', 'menu'=>$arr_forms],
									  'filter'=>['icon'=>'fa fa-fw fa-search', 'menu'=>$arr_filter],
									  /*'system bugs'=>['icon'=>'fa fa-fw fa-bug', 'menu'=>$arr_reportbug],*/
									  'profile'=>['icon'=>'fa fa-fw fa-user', 'menu'=>$arr_profile]
		                        ],
		
					'backup_operators' => [
		                              'Perform'=>['icon'=>'fa fa-fw fa-tasks', 'menu'=>$arr_tasks_backup],
									  			/*'system bugs'=>['icon'=>'fa fa-fw fa-bug', 'menu'=>$arr_reportbug],*/
									 			'profile'=>['icon'=>'fa fa-fw fa-user', 'menu'=>$arr_profile]
		                             ]

              ];

?>
