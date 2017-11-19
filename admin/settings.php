<?php
 /*
  * VarsityCliq System
  * William Sengdara
  * Copyright (c) 2016
  */
const MAX_CHARS = 100;
const DEFAULT_USER = "profiles/user.png";			

$fa_info = "";
class settings {
				/* system info */
	     const version = "<b>Bolanle</b><BR><span class='fa fa-fw fa-info-circle'></span> Internal build",
	           canonical = "http://www.shopbolanle.com",
	           title   = "Bolanle",
	           brandtext = "<img alt='logo' class='center-block img-responsive' src='images/logo-bolanle-800px.jpg'/>",
	           login_title="<span class='fa fa-fw fa-lock'></span> Login is required",
	           warning_logout = "Logout",
		   author  = "Bolanle",
		   description = "Shop genuine African wood tables, chairs, paintings and more.",
		   keywords = "bolanle, shop, african, wood tables, wood chairs",
		   copyright= "Bolanle &copy; 2017",
		   currency = "US $",
		   business_address = "<small>
                   Shellcase Investments <BR>
                   Pty Ltd <BR>
                   51-55 Werner List Street<BR>
                   Windhoek, Namibia <BR>
                   orders@shopbolanle.com
                  </small> ",
                  
		   /* theme */
		   theme = "homeaffairs",
		   
            /* database authentication */
			   		   
		   db_host = "localhost",
		   db_port = 3306,
		   db_db   = "shopbola_shop",
		   db_user = "shopbola_root",
		   db_pwd  = "Admin.2017!",
		   
		   /* database constants */
		   
		   sql_create_db = "CREATE DATABASE IF NOT EXISTS `shopbola_shop`;",
		   sql_select_db = "USE `shopbola_shop`;",

			   /* session constants */
			   session_key = "varsitycliq0::",
			   session_userid = 'varsitycliq0::userid',
			   session_logintime = 'varsitycliq0::logintime',
			   session_loginexpire = 'varsitycliq0::loginexpire',

			   sql_default_roles = "INSERT INTO user_roles(name,isactive) 
			                        VALUES
			                        ('administrators',1),
                                     ('managers',1);",

			   sql_default_admin = "INSERT INTO users(user_name,user_password,roleid)
										        SELECT 'admin' AS user_name, 
										        MD5('Admin.2015!') AS password,
										        r.id AS roleid										        
											FROM 
												  user_roles r
											WHERE 
												  r.name = 'administrators';",

			   sql_table_roles = "CREATE TABLE IF NOT EXISTS user_roles(id int(5) primary key auto_increment, 
																   name varchar(50) not null,
																   isactive tinyint(1) default '1');",
														
			   sql_table_filetypes = "CREATE TABLE IF NOT EXISTS file_types(id int(5) primary key auto_increment, 
																   name varchar(50) not null,
																   isactive tinyint(1) default '1');",
																   
               /* changed from username to user_name and user_password as autocomplete was messing things up */
			   sql_table_users = "CREATE TABLE IF NOT EXISTS users(id int(5) primary key auto_increment, 
																	 user_name varchar(20) not null, 
																	 user_password varchar(255) not null,
																	 profilepic varchar(255) default '',
																	 passwordexpire tinyint(1) default '0',
																	 isactive tinyint(1) default '1',
																	 roleid int(2) not null,
																	 
																	 entrydate TIMESTAMP default CURRENT_TIMESTAMP,
																	 lastlogin TIMESTAMP default '2017-01-01',
																	 lastlogout TIMESTAMP default '2017-01-01',
																	 sessionid LONGTEXT,
																	 
																	 /* foreign keys */
																	 FOREIGN KEY (roleid)
																	 REFERENCES user_roles(id)
																	 ON DELETE CASCADE
																	 );",

			   sql_table_userprofiles = "CREATE TABLE IF NOT EXISTS user_profiles(id int(5) primary key, 
																				   fname varchar(20) not null, 
																				   sname varchar(20) not null,
																				   title varchar(20) not null,
																				   initials varchar(10) not null,
																				   dob date,
																				   address varchar(255) not null,
																				   contactno varchar(20),
																				   email varchar(50),
																				   cellphone varchar(20),
																					FOREIGN KEY (id)
																					REFERENCES users(id)
																					ON DELETE CASCADE);",

			   sql_table_notifications = "CREATE TABLE IF NOT EXISTS user_notifications(id int(5) primary key auto_increment, 
																				 userid_from int(5) not null,
																				 userid_to int(5) not null,
																				 subject varchar(255),
																				 body LONGTEXT,
																				 wasread tinyint(1) default 0,
																				 entrydate TIMESTAMP default CURRENT_TIMESTAMP,																		 
																				FOREIGN KEY (userid_to)
																				REFERENCES users(id)
																				ON DELETE CASCADE,
																				FOREIGN KEY (userid_from)																
																				REFERENCES users(id)
																				ON DELETE CASCADE
																				 );",	

			   sql_table_settings = "CREATE TABLE IF NOT EXISTS system_settings(id int(5) primary key auto_increment, 
																		 name varchar(50) not null,
																		 value varchar(255),
																		 truefalse tinyint(1),
																		 entrydate TIMESTAMP default CURRENT_TIMESTAMP,
                                                                         user_id int(5) not null,
																		 FOREIGN KEY (user_id)																
																		 REFERENCES users(id)
																		 ON DELETE CASCADE);",

			   sql_table_api_log       = "CREATE TABLE IF NOT EXISTS api_log(id int(5) primary key auto_increment, 
																				 action varchar(50) not null default '',
																				 ipaddress varchar(255) default '',
																				 entrydate TIMESTAMP default CURRENT_TIMESTAMP,
																				 description LONGTEXT,
																				 consumer_id int(5) not null,
																				 FOREIGN KEY (consumer_id)
																				 REFERENCES api_consumers(id)
																				 ON DELETE CASCADE);",	

			   sql_table_bug_reports = "CREATE TABLE IF NOT EXISTS system_bugs(id int(5) primary key auto_increment, 
																			 description LONGTEXT,
																			 severity int(5) default '1',
																			 entrydate TIMESTAMP default CURRENT_TIMESTAMP,
																			 user_id int(5) not null,
																			 FOREIGN KEY (user_id)
																			 REFERENCES users(id)
																			 ON DELETE CASCADE);",	

			   sql_table_queries = "CREATE TABLE IF NOT EXISTS system_queries(id int(10) not null auto_increment primary key, 
																	   title varchar(255) not null, 
																	   description varchar(255) not null, 
																	   _sql LONGTEXT,
																	   entrydate datetime,																	   
																	   user_id int(10) not null,  																	   
																	   enabled tinyint(1) default '1',
																	   FOREIGN KEY (user_id)
																	   REFERENCES users(id)
																	   ON DELETE CASCADE );",	
																 
			   sql_table_system_log = "CREATE TABLE IF NOT EXISTS system_log(id int(5) primary key auto_increment, 
																		 action varchar(50) not null,
																		 ipaddress varchar(255),
																		 entrydate TIMESTAMP default CURRENT_TIMESTAMP,
																		 description LONGTEXT);";				 
}
		                     
?>
