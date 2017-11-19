<style>
 .panel-body {
					display: table-cell; 
					vertical-align: middle;
 }
 
 .panel:hover{
 	 background-color: #C6CCCC;
 	 color: #FFF !important;
 	 
    -webkit-transition: background-color 1000ms linear;
    -moz-transition: background-color 1000ms linear;
    -o-transition: background-color 1000ms linear;
    -ms-transition: background-color 1000ms linear;
    transition: background-color 1000ms linear;
    
    box-shadow: 1px 1px 1px #F3F3F3;
 }
</style>

<?php
 $menu = @ $_GET['menu'];
 
 if (!isset($menu))
 	echo "Menu item not set";
 else {
 	
 	
 }
?>

<div class="row">

 <div class="col-md-4">
  <div class="panel panel-default">
   <div class="panel-body">
   <a href='?view=new-youth'>
    <div style='float:left'><span class="fa fa-fw fa-plus fa-4x"></span> </div>
    <div style='float:right; padding-top: 14px;'>Register a Youth</div>
   </a>
   </div>
  </div>
 </div>

 <div class="col-md-4">
  <div class="panel panel-default">
   <div class="panel-body">
    <a href='?view=search'>
    <div style='float:left'><span class="fa fa-fw fa-search fa-4x"></span></div>
    <div style='float:right; padding-top: 14px;'>Search</div>
    </a> 
   </div>
  </div>
 </div>
 
</div>

<div class="row">

 <div class="col-md-4">
  <div class="panel panel-default">
   <div class="panel-body">
   <a href='?view=manage-youth'>
    <div style='float:left'><span class="fa fa-fw fa-edit fa-4x"></span></div>
    <div style='float:right; padding-top: 14px;'>Administer Youth</div> 
    </a>
   </div>
  </div>
 </div>
 
 <div class="col-md-4">
  <div class="panel panel-default">
   <div class="panel-body">
   <a href='?view=manage-youth-cls'>
    <div style='float:left'><span class="fa fa-fw fa-edit fa-4x"></span></div>
    <div style='float:right; padding-top: 14px;'>Administer CLS</div> 
    </a>
   </div>
  </div>
 </div>
  
</div>

<div class="row">

 <div class="col-md-4">
  <div class="panel panel-default">
   <div class="panel-body">
   <a href='?view=notify-sms'>
    <div style='float:left'><span class="fa fa-fw fa-envelope-o fa-4x"></span></div>
    <div style='float:right; padding-top: 14px;'>Bulk SMS</div> 
   </a>
   </div>
  </div>
 </div>
 
</div>