<?php
    /*
	 * User manual for the system
	 *
	 * This file shows the system user manual
	 *
	 * Author: William Sengdara
	 * Created:
	 * Modified:
	 */

	if (!@$users)
		die("FATAL ERROR: this file may not be launched outside the system. It can only be included.");

	// check that we are logged in!
	$user = $users->loggedin();
	if (!$user) {
		echo view( 'dialog-login' );
		exit;
	}

	$userid = $user['userid'];
	$view = @ $_GET['view'];

	$content = "";	
	
	// verify that this user has the right to manage this right
	$right_exists = verify_right($view);			
		
	if (!$right_exists)
		die(alertbuilder("You do not have the right to request that view.","danger"));

		   
	$fa_icon = font_awesome('fa-book');
	$fa_error= font_awesome('fa-exclamation-circle');

	$panels = "";//$fa_error There is no help to show currently.";

	$usergroups = "$fa_error Unable to list user groups.";
	$sql = "SELECT name
	        FROM user_roles 
	        ORDER BY name ASC;";
	$ret = $database->query($sql);
	if (!$ret || !$ret->num_rows)
	{}
	else
	{
		$temp = "";
		while ($row = $ret->fetch_array())
			$temp .= "<tr><td>{$row['name']}</td></tr>";

		$usergroups = "<div class='table-responsive'>
		                <table class='table table-hover table-bordered table-striped table-condensed'>
		                 <thead>
		                  <tr><th>Name</th></tr>
		                 </thead>
		                 <tbody>
		                  $temp
		                 </tbody>
		                </table>
					   </div>";
	}

	// define the user manual entries
	// t means title
	// b means body
	$help[] = ['t'=>'Introduction', 
	           'b'=>'<h5>Welcome</h5>
	           		 <p>to the Youth Portal Management System for the MYNSCC.
	                 The system manages the youth.</p>
	                 <p>The system implements easy navigation, search and user access control, amogst other features designed specifically to 
	                 fast track applications and provide insight for management (statistics) but more importantly to render
	                 better service delivery to the public.</p>'];

	$help[] = ['t'=>'Key concepts', 
	           'b'=>"<h5>Introduction</h5>
	                 <p>The system is built with the idea that management systems must be able to grow naturally,
	           		 as technologies change and new ways of doing things are introduced.</p>
	           		 <p>The system can be easily extended with new features as the need arises.</p>
	           		 <h5>Data integrity</h5>
	           		 <h5>Backup & restore</h5>"];

	$help[] = ['t'=>'Messages', 
	           'b'=>"<h5>Introduction</h5>
	                  <p>Messaging was added to the system to allow users to send and receive messages while they use the system.
	                    This options was added in order to directly interact with individual users within the system.</p>"];

	$help[] = ['t'=>'Users', 
	           'b'=>"<h5>Introduction</h5>
		                <p>Each staff member will have a user account on the system. The user must login using their username and password.
		                Upon successful login, the user is presented with options that allow them to perform the duties designated to them.</p>
						<p>In order to facilitate this process, every user is categorised into a user group. Currently, the following user groups exist</p>
		                 $usergroups
		                 <h5></h5>
		                 "];
	$help[] = ['t'=>'Database', 
	           'b'=>"<h5>Introduction</h5>
	                <p>The system runs on top of the popular MySQL Server database. This option was selected not only for its
	                  above-standard performance, dependability and stability but even more for its flexibility in terms of further future internal development.</p>
	                 <h5>Security</h5>
	                 <p>Application data is stored in a password-protected database.</p>
	                 <h5>Queries</h5>
	                 <p>The system administrator may create scripts (SQL queries) that pulls data directly from the database,
	                    bypassing the data view presented by the CRS.</p>	                 
	                 <h5>Backup & Restore</h5>
	                 <p>The administrator may backup the current database schema as a protective measure and in case of 
	                    data issues may restore the last database backup.</p>
	                 "];

	$help[] = ['t'=>'System events', 
	           'b'=>"<h5>Introduction</h5>
	           		<p>In order to monitor the system, the administrator is able to monitor all events on the system
	                  from the System panel.</p>"];


	$help[] = ['t'=>'The help wizard', 
	           'b'=>"<h5>Introduction</h5>
	                <p>Traditionally, users are taught how to use a particular system through courses or manuals. 
	                   The help wizard aims to quickly provide information on a particular feature of the system
	                   any time the user requires.</p>
	                 <p>The wizard is able to give a complete explanation of the system by clicking <strong>Help Wizard</strong>
	                    on the user profile panel or it can be invoked in order to explain just one particular feature or option by
	                    clicking the black question mark next to the option desired.</p>
	                 "];

	$help[] = ['t'=>'Bug reporting', 
	           'b'=>"<h5>Introduction</h5>
	           		<p>Should a system feature not work as expected, the CRS provides the option to report these 
	                    (bugs as they are known in IT)</p>
	                 <p>The developers analyse these error reports and work diligently to fix them.</p>"];
					 
	$i = 0;
	foreach($help as $t)
	{
		$i++;
		// the default behavior is to have only one item open at once
		// but if we remove data-parent attribute, they can all be open at same time
		$panels .= "<div class='panel panel-default'>
			        <div class='panel-heading'>
			            <h4 class='panel-title'>
			                <a data-toggle='collapse'  data-toggle='collapse' data-parent='_#accordion' href='#collapse$i'>$i. {$t['t']}</a>
			            </h4>
			        </div>
			        <div id='collapse$i' class='panel-collapse collapse'>
			            <div class='panel-body'>
			                {$t['b']}
			            </div>
			        </div>
			    </div>";
	}


	echo "
	       <h4 style=''>$fa_icon User Manual <small style=''>Learn how to use the system.</small></h4>
		   <hr>
			<div id='accordion' class='panel-group'>
			  $panels
			</div>";	
?>