<?php
/*
 * Define user rights for the menus
 */

// defined rights

$arr_forms =    [
						'Store item'           =>['icon'=>'fa fa-fw fa-shopping-cart',
						                     'title'=>'add a new store item',
						                     'intro-text'=>"Add a store item.",
						                     'url'=>'new-store-item'],    
						'Banner'           =>['icon'=>'fa fa-fw fa-picture-o',
						                     'title'=>'add a new banenr',
						                     'intro-text'=>"Add a banner.",
						                     'url'=>'new-banner']
					];


/* manage these fields */
$arr_sales = [
          					'Quotes'=>['icon'=>'fa fa-fw fa-list', 'title'=>'Orders', 'intro-text'=>"Manage quotes",'url'=>'manage-orders'],
          					
          					'Payments'=>['icon'=>'fa fa-fw fa-usd', 'title'=>'Payments','divider-top'=>true, 'intro-text'=>"Manage payments made",'url'=>'manage-payments']      
    ];	
    
$arr_manage =    [
              					'Store Items'=>['icon'=>'fa fa-fw fa-shopping-cart', 'title'=>'Store Items','intro-text'=>"Manage the items in the store.",'url'=>'manage-store-items'],
          					'Banners'=>['icon'=>'fa fa-fw fa-picture-o', 'title'=>'Banners', 'intro-text'=>"Banners for the front.",'url'=>'manage-banners']
					     ];

/* notify */
$arr_notify =    [
					/*'Inbox'              =>['icon'=>'fa fa-fw fa-envelope','title'=>'send message','intro-text'=>"Send internal message",'url'=>'notify-inbox'],*/
					'SMS'                =>['icon'=>'fa fa-fw fa-mobile','title'=>'send sms','intro-text'=>"Send sms.",'url'=>'notify-sms']/*,
					'Push Notifications' =>['icon'=>'fa fa-fw fa-external-link','title'=>'send push notification','intro-text'=>"Send push notification.", 'url'=>'notify-push']*/                              
];

/* profile management */
$arr_profile = [		 
                  /*'messages'=>['icon'=>'fa fa-fw fa-comment-o', 'title'=>'Messages','intro-text'=>"Read and respond to messages from other users. "],	*/
                  'Home'=>['icon'=>'fa fa-fw fa-home', 'url'=>'home', 'title'=>'Home panel','intro-text'=>"Your home panel showing all available options and tasks."],								
                  'My profile'=>['icon'=>'fa fa-fw fa-user', 'title'=>'update your account', 'intro-text'=>"Change your login information and personal details.",'url'=>'profile'],
                  'assistance'=>['icon'=>'fa fa-fw fa-life-ring','divider-top'=>true, 'title'=>'start the system help wizard','intro-text'=>"Start the help wizard to guide you on how to use the system.",'url'=>'help-wizard'],
                  /*'user manual'=>['icon'=>'fa fa-fw fa-book', 'title'=>'User manual', 'intro-text'=>"Open the system user manual.","url"=>'user-manual'],*/
                  'report a bug'=>['icon'=>'fa fa-fw fa-bug', 'title'=>'Report a bug', 'intro-text'=>"Report a system malfunction, suggest a feature.",'url'=>'bug-reporter'],                              
                  'sign out'=>['icon'=>'fa fa-fw fa-sign-out', 'title'=>'log out of your account', 'divider-top'=>true, 'intro-text'=>"Log out of your account and view the login page.",'url'=>'xx']
				  ];

/* dashboard */
$arr_dashboard =    [
          					 'Primary'=>['icon'=>'fa fa-fw fa-dashboard', 
          					 					'title'=>'View your dashboard', 
          					 					'intro-text'=>"View a graphical breakdown of vital insights on system data.",
          					 					'url'=>'dashboard'],
          					 'Item statistics'=>['icon'=>'fa fa-fw fa-dashboard', 
          					 					'title'=>'View your dashboard', 
          					 					'intro-text'=>"View a graphical breakdown of vital insights on system data.",
          					 					'url'=>'dashboard-secondary'],
          					 'Quoted item statistics'=>['icon'=>'fa fa-fw fa-dashboard', 
          					 					'title'=>'View your dashboard', 
          					 					'intro-text'=>"View a graphical breakdown of vital insights on system data.",
          					 					'url'=>'dashboard-quote-items']          					 					
					     ];
					     
$arr_repository =    [
          					 'Discounts'=>['icon'=>'fa fa-fw fa-check', 'title'=>'Discounts I have created', 'intro-text'=>"Discounts I have created.",'url'=>'repository-discounts']
					     ];

					     
/* settings */
$arr_settings = [
                 'query builder'=>['icon'=>"fa fa-fw fa-filter",'title'=>'manage database queries',
                 'intro-text'=>"Add or edit SQL queries against the database.",'url'=>'queries'],
                 'database'=>['icon'=>"fa fa-fw fa-database",'title'=>'manage database', 'intro-text'=>"Manage the database.",'url'=>'database'],
				     'table size'=>['icon'=>"fa fa-fw fa-table",'title'=>'view tables', 'intro-text'=>"View tables.",'url'=>'table-size'],
				     'system audit'=>['icon'=>"fa fa-fw fa-wrench",'divider-top'=>true, 'title'=>'manage system settings', 'intro-text'=>"Manage other aspects of the system.",'url'=>'system'],				     
                 'system'=>['icon'=>"fa fa-fw fa-wrench",'title'=>'manage system settings', 'intro-text'=>"Manage other aspects of the system.",'url'=>'system'],
                 'PHP Info'=>['icon'=>"fa fa-fw fa-cog",'title'=>'PHP Info output', 'intro-text'=>"PHP Info output.",'url'=>'system-php-info'],
                 'Pre-flight'=>['icon'=>"fa fa-fw fa-plane",'title'=>'Pre-flight checks', 'intro-text'=>"Pre-flight checks.",'url'=>'system-test'],
                 
                 'users'=>['icon'=>'fa fa-fw fa-users','divider-top'=>true, 'title'=>'manage users and user groups','intro-text'=>"Add, edit or delete users and user groups.",'url'=>'users']
                ];

/* searching */
$arr_filter = ['search'=>['icon'=>'fa fa-fw fa-search', 'title'=>'search for applications', 'intro-text'=>"Search the database for applications",'url'=>'search'],
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
										/*'add'=>['icon'=>'fa fa-fw fa-plus', 'menu'=>$arr_forms],*/  
										'sales'=>['icon'=>'fa fa-fw fa-usd', 'menu'=>$arr_sales],
										'manage'=>['icon'=>'fa fa-fw fa-edit', 'menu'=>$arr_manage],
										/*'notify'=>['icon'=>'fa fa-fw fa-envelope-o', 'menu'=>$arr_notify],
										'filter'=>['icon'=>'fa fa-fw fa-search', 'menu'=>$arr_filter],*/ 
										/*'CMS'=>['icon'=>'fa fa-fw fa-newspaper-o', 'menu'=>$arr_cms],*/	
										/*'services'=>['icon'=>'fa fa-fw fa-cog', 'menu'=>$arr_services],*/
										'settings'=>['icon'=>'fa fa-fw fa-wrench', 'menu'=>$arr_settings],										
										/*'system bugs'=>['icon'=>'fa fa-fw fa-bug', 'menu'=>$arr_reportbug],*/
										'profile'=>['icon'=>'fa fa-fw fa-user', 'menu'=>$arr_profile]
		                             ],

		         'managers' => [
										'dashboard'=>['icon'=>'fa fa-fw fa-dashboard', 'menu'=>$arr_dashboard],
										'add'=>['icon'=>'fa fa-fw fa-plus', 'menu'=>$arr_forms],
										'sales'=>['icon'=>'fa fa-fw fa-usd', 'menu'=>$arr_sales],
										'manage'=>['icon'=>'fa fa-fw fa-edit', 'menu'=>$arr_manage],
										'profile'=>['icon'=>'fa fa-fw fa-user', 'menu'=>$arr_profile]
		                             ]

              ];
?>
