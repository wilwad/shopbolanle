<?php
    /*
	 * Dashboard for boardcommittee & top_level
	 *
	 * This file shows applications the following applications
	 *   board_committee: under_review
	 *   top_level: recommended to be approved/rejected/deferred
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
    $right_exists = verify_right($view);
		
	if (!$right_exists)
		die("<div class='well bg-white'>
			   <div class='alert alert-danger'>
			    <li class='fa fa-fw fa-exclamation-circle'></li>&nbsp;You do not have the right to request that view.
			   </div>
			 </div>");
	
	$history = "<div class='table-responsive'>
				<table id='table-history' class='table table-hover table-striped table-bordered'>
				 <thead>
				  <tr><th>#</th><th>Date</th><th>Event</th><th>Description</th></tr>
				 </thead>
				 <tbody>
				  <tr><td colspan='5'><i>There are no events at this time.</i></td></tr>			 
				 <tbody>
				</table>
			   </div>";
		   
	$fa_icon = font_awesome('fa-database');
	
	echo "
	       <h4>$fa_icon Repository <small>For selected OMAs</small></h4>
		   <hr>	
		   
		   <!--
		   <div class='row'>
			  <div class='col-md-6'>
			   <div class='panel panel-default'>
				<div class='panel-heading' data-target='#pbody1' >Citizenship registrations</div>
				<div class='panel-body' id='pbody1'>   
					<div id='chart-registrations'></div>
				</div>
			   </div>
			  </div>

			  <div class='col-md-6'>
			   <div class='panel panel-default' data-target='#pbody2' >
				<div class='panel-heading'>Staff performance</div>
				<div class='panel-body' id='pbody2'>   
					<div id='chart-performance'></div>
				</div>
			   </div>
			  </div>
  			  
	      </div>

		  
		  <div class='row'>
			<div class='col-md-12'>
			  $history
			  
			  <h4>I want to see applications under review</h4>
			  <h4>Recently added</h4>
			  <h4>Approved</h4>
			  <h4>Rejected</h4>
			  <h4>I want to see in chart format</h4>
			</div>
		   </div>		  -->
		 ";	
?>
<div class='table-responsive'>

<table class='table table-bordered '>

<tbody>
  <tr><th>OMAs</th><td>Republic of Namibia</td></tr>
  <tr><th>Subject</th><td>Contracts and Agreemeents entered into with the Republic of Namibia</td></tr>
  <tr><th>Added by</th><td><span class='fa fa-fw fa-user'></span> Admin </td></tr>
  <tr><th>Date created</th><td>21 March 1990</td></tr>

</tbody>
</table>
</div>
<hr>

<div class="row">    
	<div class="col-md-6">
	 <div class='pull-right'>
		<div class="input-group">
			<input type="hidden" name="search_param" value="all" id="search_param">         
			<input type="text" class="form-control" name="x" placeholder="Free search. e.g. file number, names">
			<span class="input-group-btn">
				<button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
			</span>
		</div>
	  </div>
	</div>
	<div class="col-md-6">
	 <button class='btn btn-success'><span class='fa fa-fw fa-plus'></span> Add a document</button>
	</div>
</div>
<p>&nbsp;</p>			


<div class='table-responsive'>

<table class='table table-striped'>
<thead>
 <tr>
  <th>#</th>
  <th>Title</th>
  <th>Subject</th>
  <th>Date entered into</th>
  <th>Type</th>
  <th>Ref. No.</th>
  <th>Status</th>
  <th>Action</th>
 </tr>
</thead>
<tbody>
<tr><td>1</td><td><a href='http://www.international.gc.ca/trade-agreements-accords-commerciaux/assets/pdfs/cusfta-e.pdf' target='_blank'>Trade Agreement</a></td><td>Agreement to etc</td><td>21 March 1991</td><td>Contract/Agreement</td><td>EDMS0001</td><td><span class='label label-success'>Completed</span> </td><td><span title='View' data-toggle='tooltip' class='fa fa-fw fa-eye'></span> <span title='View' data-toggle='tooltip' class='fa fa-fw fa-lock'></span> <span title='Edit' data-toggle='tooltip' class='fa fa-fw fa-edit'></span> <span title='Delete' data-toggle='tooltip' class='fa fa-fw fa-trash'></span>  </td></tr>
<tr><td>2</td><td><a href='https://www.wto.org/english/docs_e/legal_e/17-tbt.pdf' target='_blank'>Ministers agreements</a></td><td>Agreement to etc</td><td>21 March 1991</td><td>MOU</td><td>EDMS0002</td><td><span class='label label-danger'>Expired</span> </td><td><span title='View' data-toggle='tooltip' class='fa fa-fw fa-eye'></span> <span title='Unlock this document' data-toggle='tooltip' class='fa fa-fw fa-lock'></span> <span title='Edit' data-toggle='tooltip' class='fa fa-fw fa-edit'></span> <span title='Delete' data-toggle='tooltip' class='fa fa-fw fa-trash'></span>  </td></tr>
<tr><td>3</td><td><a href='http://www.aric.adb.org/pdf/fta_manual.pdf' target='_blank'>Sample</a></td><td>Agreement to etc</td><td>21 March 1991</td><td>Legal opinion/advise</td><td>EDMS0003</td><td><span class='label label-warning'>Overdue</span> </td><td><span title='View' data-toggle='tooltip' class='fa fa-fw fa-eye'></span> <span title='Lock this document' data-toggle='tooltip' class='fa fa-fw fa-unlock'></span> <span title='Edit' data-toggle='tooltip' class='fa fa-fw fa-edit'></span> <span title='Delete' data-toggle='tooltip' class='fa fa-fw fa-trash'></span>  </td></tr>
</tbody>
</table>

</div>