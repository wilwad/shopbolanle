<?php
 /*
  * This file builds the menu for the current logged in user.
  * user rights are tied to roles
  *
  * rights[role] -> options
  */
  
	if (!@$users)
		die("FATAL ERROR: this file may not be launched outside the system. It can only be included.");

	$view = @ $_GET['view'];
	
	// is anyone logged in?
	$key_userid = settings::session_userid;	

	// check that we are logged in!
	$user = $users->loggedin();
	
	if (!$user) {
		// we need to login.
	}
	else
	{
		$userid     = $user['userid'];
		$username   = $users->user($userid)->get('user_name');
		$firstname  = $users->user($userid)->get('fname');
		$profilepic = $users->user($userid)->get('profilepic');
		$lastname   = $users->user($userid)->get('sname');
		$role       = $users->user($userid)->get('rolename');
		$role       = strtolower($role);

		// start of introJS
		$introJsIndex = 1;
		
		/*
		 * if we do not have a view, 
		 * set a default view based on the user?
		 */
		if (strlen(trim($view)) == 0){
			switch ($role) {
				 case 'administrators':
					  $view = 'system';
					  break;
																	
				 case 'backup_operators':
					  $view = 'database';
					  break;

				 case 'secretariats':
				 case 'top_levels':
					  $view = 'dashboard';
					  break;
					  
				default:
					  $view = 'home';
					  break;
			}
			
			// set the view to the allowed view
			$_GET['view'] = $view;
		}
		
		// options based on the role of logged in user
		$myrights = $role_rights[$role];
		unset($myrights['profile']);

		echo "<div class='row' id='landingpanel'>";
		
		// build the options for this user
		foreach($myrights as $key=>$arr) {
			/* get the title of this panel and icon */
			$icon = $arr['icon'];
			$key = ucfirst($key);
			$initial_key = $key;
			$panelbg = "panel-default";

			switch (strtolower($key)) {
				case 'tasks':
				    $panelbg = "panel-primary";
					break;
					
				case 'filter':
				    $panelbg = "panel-success";
					break;					
			}
			
			switch (strtolower($key)) {
				case 'profile':
				    $key = "$username <span class='badge pull-right'>$role</span>";
					break;
			}
			
			echo "<div class='col-md-3'>
					<div class='panel $panelbg'>
			        <div class='panel-heading'><li class='$icon'></li>&nbsp;<b>$key</b></div>";
				   
			/* get options */
			$options = "";
			$text = '??';
			
			foreach($arr['menu'] as $key=>$menu) {
					$ico       = $menu['icon'];
					$tooltip   = $menu['title'];
					$text      = ucfirst($key);
					$introtext = $menu['intro-text'];
					$url       = $menu['url'];
					$active    = strtolower($view) == strtolower($key) ? "class='active-option'" : "";
					$disabled  = strtolower($view) == strtolower($text) ? 'disabled' : '';
					
					$img = "";
					
					switch ($key) {
						case 'sign out':
								$options .= "<a href='#' onclick=\"confirmlogout();\" title=\"$tooltip\" 
								data-toggle='tooltip' data-placement='right' data-step='$introJsIndex' 
								data-position='right' data-intro=\"<li class='$ico'></li>&nbsp;<b>$text</b>
								             <BR><BR><small>$introtext</small>\"><li class=\"$ico\"></li>&nbsp;$text</a>
											 <p></p>";
							$introJsIndex++;									 
							break;
							
							
						case 'help wizard':
							$options .= "<a href='#' id=\"link$key\" title=\"$tooltip\" data-placement='right' data-toggle='tooltip'  data-original-value=\"$key\" onclick=\"introJs().onchange(function(targetelement){ var msg = $('#'+targetelement.id).attr('data-intro'); msg = msg.match(/<small>(.*?)<\/small>/g)[0]; msg = msg.replace(/<small>/g,''); msg = msg.replace(/<\/small>/g,''); console.log(msg); var u = new SpeechSynthesisUtterance(msg);speechSynthesis.speak(u); }).start();\"><li class=\"$ico\"></li>&nbsp;Help Wizard</a><p></p>";						
							break;
							
						default:
								$span_notifications = "<span class='pull-right' 
                                                              style='cursor:pointer' 
                                                              data-toggle='tooltip' 
                                                              title='Start help wizard'
                                                              onclick=\"introJs().goToStep($introJsIndex).start();\"
                                                              data-placement='right'><li class='fa fa-fw fa-question-circle'></li>
                                                        </span>   ";
                        
								switch ($key) {
									case 'messages':
									    // do we have new messages?
										$total = 0;
										$sql = "SELECT COUNT(*)
												FROM user_notifications 
												WHERE userid_to=$userid AND wasread=0;";
										$ret = $database->query($sql);
										if (!$ret || !$ret->num_rows)
										{}
										else
										{
											$total = $ret->fetch_array()[0];
										}
										$alert = $total ? 'pm-new-messages' : '';
										
										$profilepic = $users->user($userid)->get('profilepic');
										$img = file_exists($profilepic) ? $profilepic : DEFAULT_USER;
										$img = "<a href='?view=profile'><img src='$img' class='img-responsive' style='border-radius:100px;'></a><hr>";
									    // we auto-update the new messages counter
										$span_notifications = "<span class='badge $alert' 
                                                                     data-toggle='tooltip' 
                                                                      title='Start help wizard'
                                                                      onclick=\"introJs().goToStep($introJsIndex).start();\"
                                                                     data-placement='right' 
                                                                     title=\"How many new notifications you have.\" id='notifications-counter'>$total</span>
                                                                     
																	<span class='pull-right' 
																		  style='cursor:pointer' 
																		  data-toggle='tooltip' 
																		  title='Start help wizard'
																		  onclick=\"introJs().goToStep($introJsIndex).start();\"                 
																		  data-placement='right'><li class='fa fa-fw fa-question-circle'></li>
																	</span>";
										//$i++;
										break;
								}
								
								$options .= "$img
								             <a href=\"?view=$url\" id=\"$key\" $active title=\"$tooltip\" data-toggle='tooltip' data-placement='right' data-step='$introJsIndex' data-position='right' data-intro=\"<li class='$ico'></li>&nbsp;<b>$text</b>
								             <BR><BR><small>$introtext</small><BR><BR><a href='?view=$url' $disabled class='btn btn-sm btn-primary'><li class='fa fa-fw fa-play'></li>&nbsp;Open this view</a>\"><li class=\"$ico\"></li>&nbsp;$text</a>
											 &nbsp;$span_notifications
											 <p></p>";
								$introJsIndex++;
								break;
					}
			}
			
			echo "	<div class='panel-body'>$options</div>
				   </div>
				   </div>";
				  

		}

		echo "<script>
			   $(document).ready(function(){
					var heightTallest = Math.max.apply(null, $('.landingpanel').map(function ()
					{
					return $(this).outerHeight();
					}).get());
					$('.landingpanel').css({ height: heightTallest + 'px' });				   
			   });
			  </script>";	

		echo "</div>";
	}	
?>