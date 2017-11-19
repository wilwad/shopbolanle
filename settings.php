<?php
 /*
  * eYouth System
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
	           title   = "Bolanle - Shop genuine African wood tables, chairs, paintings and more",
		   author  = "Bolanle",
		   description = "Shop genuine African wood tables, chairs, paintings and more.",
		   keywords = "bolanle, shop, african, wood tables, wood chairs",
		   copyright= "<a href='?view=privacy'>Privacy policy</a> | Bolanle &copy; 2017",
		   business_address = "<small>
                   Shellcase Investments <BR>
                   Pty Ltd <BR>
                   51-55 Werner List Street<BR>
                   Windhoek, Namibia <BR>
                   orders@shopbolanle.com
                  </small> ",
			   
		   /* theme */
		   theme = "homeaffairs",
		   
		   db_host = "localhost",
		   db_port = 3306,
		   db_db   = "shopbola_shop",
		   db_user = "shopbola_root",
		   db_pwd  = "Admin.2017!",
		   
		   sql_create_db = "CREATE DATABASE IF NOT EXISTS `shopbola_shop`;",
		   sql_select_db = "USE `shopbola_shop`;";						 
}

?>