<?php
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
		die("<div class='well bg-white'>
			   <div class='alert alert-danger'>
			    <li class='fa fa-fw fa-exclamation-circle'></li>&nbsp;You do not have the right to request that view.
			   </div>
			 </div>");


	/*
	 * show the system log
	 * pull data from system_log table
	 *
	 * SELECT * FROM system_log order by entrydate DESC
	 */

	/*
	 *
	 */
	function is_valid_ip($ip) {
			if (trim($ip) != '::1')
			   return true;
	}

	$scripts = "";
	$head = "";
	$body = "";
	$thead   = "";
	$tbody   = "";

	$sql = "SELECT * FROM system_log ORDER BY entrydate DESC";
	$ret = $database->query($sql);
	if (!$ret || !$ret->num_rows)
	{
		//$body = "<BR><li class='fa fa-fw fa-exclamation-circle'></li>&nbsp;The system log is currently empty. Please check back later.</>";
	}
	else
	{
		$default = "<li class='fa fa-fw fa-exclamation-circle'></li>&nbsp;The system log is currently empty.</>";
		$row = $ret->fetch_array();
		$data = system_row_to_table('system_log',$ret, $default);
		$head = $data['fields'];
		$body = $data['data'];

		$categories_arrays_y = ""; //['Males', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
		$categories_arrays_x = ""; //['Males', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
								 //['Females', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
		$categories_commas = ""; // 'cat1','cat2',...
		$categories_commas2 = "";

		$arr_actions = array();
		$arr_entrydates = array();
		$list_entrydates = "";

		// get array of unique entrydates
		$sql = "SELECT
		        DISTINCT SUBSTR(entrydate,1,10) AS entrydate
				FROM system_log
				ORDER BY entrydate DESC;";
		$ret = $database->query($sql);
		if (!$ret || !$ret->num_rows){
		}
		else
		{
			$i = 1;
			$max = $ret->num_rows;
			while ($row = $ret->fetch_array()){
				$sep = $i < $max ? "," : "";
				$entrydate = $row['entrydate'];
				$arr_entrydates[] = $entrydate;
				$list_entrydates .= "'$entrydate'$sep";
				$i++;
			}
		}

		// get an array of distinct actions and make an array
		$sql = "SELECT DISTINCT action
		        FROM system_log
				ORDER BY action ASC;";
		$actions = $database->query($sql);
		if (!$actions || !$actions->num_rows)
		{}
		else {
			$max = $actions->num_rows;
			$k = 1;

			while ($row = $actions->fetch_array()){
				$action = $row['action'];
				$arr_actions[] = $action;

				$sep = $k < $max ? "," : "";
				$categories_arrays_y .= "'$action'$sep";
				$k++;
			}
		}

		$data = "";

		$max = count($arr_actions);
		$i = 1;
		$j = 1;
		$sep = "";
		$sep2 = "";

		// run through the actions
		$max2 = $ret->num_rows;
		foreach($arr_actions as $action){
			$sep = $j < $max ? "," : "";
			$data .= "['$action',";

			// get the data for this action on this date
			$i = 1;
			foreach($arr_entrydates as $entrydate){
					$total = 0;
					$sep2 = $i < $max ? "," : "";

					$sql = "SELECT COUNT(*) AS total
							FROM system_log
							WHERE action='$action' AND
							SUBSTR(entrydate,1,10)='$entrydate';";
					$ret = $database->query($sql);
					if (!$ret || !$ret->num_rows)
					{}
					else
					{
						while ($row = $ret->fetch_array()){
							$total = $row[0];
							$data .= "$total";
						}
					}
					$data .= "$sep2";
					$i++;
			}

			$data .= "]$sep\n";
			$j++;
		}

		$scripts = "<script>
			  /*
			   * categories_commas2: $categories_commas2
			   */
			  /*
			   * Javascript code specific to this page follows:
			   * Note: follow this practice so the JS library
			   * 	   loaded is only when needed
			   */
			   var data = null;

			   function dlg_ip(ip)
			   {
				  dialogcss = \"<div class='table-responsive'> \
								<table id='table-ip' class='table table-hover table-bordered table-striped table-condensed'> \
								 <tbody> \
								  <tr><th>Status</th><td id='status'></td></tr> \
								  <tr><th>Host</th><td id='host'></td></tr> \
								  <tr><th>Country</th><td id='country'></td></tr> \
								  <tr><th>Country Code</th><td id='countryCode'></td></tr> \
								  <tr><th>Region Name</th><td id='region'></td></tr> \
								  <tr><th>City</th><td id='city'></td></tr> \
								  <tr><th>Zip</th><td id='zip'></td></tr> \
								  <tr><th>Lat</th><td id='lat'></td></tr> \
								  <tr><th>Timezone</th><td id='timezone'></td></tr> \
								  <tr><th>ISP</th><td id='isp'></td></tr> \
								  <tr><th>ORG</th><td id='org'></td></tr> \
								  <tr><th>AS</th><td id='as'></td><tr> \
								  <tr><th>IP</th><td id='query'></td></tr> \
								  <tr><th>Message</th><td id='message'></td></tr> \
								 </tbody> \
								</table> \
                             </div>\";

					BootstrapDialog.show({
											title: 'IP information',
											message: function(dialog) {
												var content = $(dialogcss);
												return content;
												},
											onshow: function(){

											},
										onshown: function(){
												   /* IP stuff */
												   var funcSuccess = function(json) {
																	var json = JSON.parse(json);

																	console.log(json);
																	data = json;
																	 switch (json.status){
																		 case 'success':
																		 case 'fail':
																				for(var prop in json) {
																					$('#'+prop).text(json[prop]);
																				}
																				break;
																	 }
																	};
												   var funcError = function (a,b,c) {
																		alertify.error(b + ' ' + c);
																	  };

												  var payload = {};

												  // show spinners
												  $('#table-ip td').html(\"<li class='fa fa-fw fa-spinner fa-spin'></li>\");

												  /* http://ip-api.com/docs/api:json */
												  ajax('http://ip-api.com/json/'+ip,
													   'post',
													   'text',
													   payload,
													   funcSuccess,
													   funcError);

												  /* get hostbyaddr local api */
												   var funcSuccess2 = function(json) {
																	var json = JSON.parse(json);
																	console.log('ret:',json);
																	data = json;
																	 switch (json.status){
																		 case 'success':
																		 case 'fail':
																				$('#host').text(json['host']);
																				break;
																	 }
																	};
												   var funcError2 = function (a,b,c) {
																		alertify.error(b + ' ' + c);
																	  };

												  var payload = {'ip': ip};

												  // show spinners
												  $('#table-ip td').html(\"<li class='fa fa-fw fa-spinner fa-spin'></li>\");

												  var url = 'api/api-hostbyaddr.php';
												  console.log(url);
												  ajax(url,
													   'get',
													   'text',
													   payload,
													   funcSuccess2,
													   funcError2);

												},
										buttons: [
													{
													label: 'Close',
													action: function(dialogItself){
														dialogItself.close();
													}
													}]
									});
			   }

				function delete_from_table(table, id){
					BootstrapDialog.confirm('Are you sure you would like to delete this item?',function(ans){
						if (ans){
						  var payload = {'view': 'delete-from-table',
										 'userid': $userid,
										 'table': table,
										 'id': id
										 };

						  console.log(payload);

						  ajax('api/api.php',
							   'post',
							   'text',
							   payload,
							   funcSuccess,
							   funcError);
						}
					});
				}

				/* execute */
				$(document).ready(function(){
				    chart_events = null;
					chart_events = c3.generate({
													bindto: '#chart-events',
													data: {
														x:'x',
														columns: [
																['x', $list_entrydates],
																 $data
																]
														,type: 'spline'
													},
													axis: {
														x: {
															type: 'timeseries',
															tick: {
																format: '%Y-%m-%d'
															}
														}
													}
												});

				   $('#table-system').DataTable();

				   // render a chart son

				   // collapse tabs
				   $('#tabs').tabCollapse();
			   });
			  </script>";
	}

	$server = settings::db_host;
	$db     = settings::db_db;
	$user   = settings::db_user;

	$fa_wrench = font_awesome('fa-wrench');
	$fa_icon = font_awesome('fa-database');
	$fa_info = font_awesome('fa-info-circle');

	$fa_icon = font_awesome('fa-wrench');
	$fa_plus = font_awesome('fa-plus');
	$fa_floppy = font_awesome('fa-floppy-o');

	$fa_edit = font_awesome('fa-edit');
	$fa_trash = font_awesome('fa-trash');

	echo "<h5>$fa_icon System <small>System activity view</small></h5>
			 <hr>
			 <ul id='tabs' class='nav nav-tabs' data-tabs='tabs'>
					<li class='active'><a href='#chart' data-toggle='tab'>Chart</a></li>
					<li class=''><a href='#log' data-toggle='tab'>Event log</a></li>
					<li class=''><a href='#dev' data-toggle='tab'>Development</a></li>
			 </ul>
			 <p>&nbsp;</p>
			 <div id='my-tab-content' class='tab-content'>

				<!-- activity log -->
				<div class='tab-pane active' id='chart'>
					<div id='chart-events'>
					  <li class='fa fa-fw fa-spinner fa-spin'></li>&nbsp;Loading data. Please wait...
					</div>
				</div>

				<!-- log -->
				<div class='tab-pane' id='log'>
				  <div class='table-responsive'>
				   <table id='table-system' class='table table-bordered table-striped table-hover table-condensed'>
					<thead>
					 $head
					</thead>
					<tbody>
					 $body
					</tbody>
				   </table>
				  </div>
				</div>

				<!-- dev -->
				<div class='tab-pane' id='dev'>
				  <h5>System development</h5>
				  <h5>Written by William Sengdara <small>Software Developer (SengdaraIT)</small></h5>
				  <h6>william.sengdara@gmail.com</h6>
				</div>
				
			</div>

		 $scripts";


 /*
  * This will return an array of fields formatted as table headers
  * and tbody content
  */
function system_row_to_table($table, $ret, $default) {
	    $i = 0;
		$j = 0;
		$data = $default;
		$fields = "";
		$arr_fields = [];

		if ($ret) {
			while ($fld=mysqli_fetch_field($ret))
			{
				$fld_name = $fld->name;
				$fld_name = ucfirst($fld_name);

				$fields .= "<th>$fld_name</th>";

				// store fieldnames in array so we follow
				// same field order when we spit out data
				$arr_fields[] = $fld->name;
			}

			// reset the recordset or you wont display any data
			mysqli_data_seek($ret,0);

			// spitting out data following field names
			$data = "<i>There is currently no data available.</i>";
			if ($ret->num_rows) $data = "";

			while ($res = $ret->fetch_array())
			{
				$data .= "<tr>";
				foreach ($arr_fields as $fld) {
					switch (strtolower($table)) {
							case 'system_log':
								switch (strtolower($fld)) {
									case 'ipaddress':
										$ip = $res[$fld];

										if (is_valid_ip($ip))
											$ip = "<a href='#' onclick=\"dlg_ip('$ip');\");\" data-toggle='tooltip' title='View location'><li class='fa fa-fw fa-search'></li>&nbsp;$ip</a>";

										$data .= "<td><small>$ip</small></td>";
										break;

									case 'id':
										$id = $res[$fld];
										$id = "<a href='#' onclick=\"delete_from_table('$table',$id);\" data-toggle='tooltip' title='Delete this system log entry'><li class='fa fa-fw fa-trash'></li></a>";
										$data .= "<td>$id</td>";
										break;

									default:
										$data .= "<td>{$res[$fld]}</td>";
										break;
								}
								break;

							default:
								$data .= "<td>{$res[$fld]}</td>";
								break;
					}
				}

				$data .= "</tr>";
			}
		}
		return ['fields'=>$fields, 'data'=>$data];
}

function randomInt(){
	return mt_rand(10,500);
}

// e818251aee367c0ad30c82886b42a109
function randomHash(){
	$rand = mt_rand(0, 32);
	$code = md5($rand . time());
	return $code;
}
?>
