<?php
session_start();
    error_reporting(0);
    ob_start();
    require_once('functions/db.php');

    // If session variable is not set it will redirect to login page
    
    if(!isset($_SESSION['empid']) || empty($_SESSION['empid'])){

      header("location: login.php");

      exit;
    }
    else
    {
        $luser = $_SESSION['empid'];
        $glus = "SELECT * FROM `admin` WHERE `empid`='$luser'";
        $getluser = mysqli_query($connection, $glus);
        while ($ftuser = mysqli_fetch_array($getluser)) 
        {
            $lfname = $ftuser['fname'];
            $llname = $ftuser['lname'];
            $lrole = $ftuser['role'];
            $lemploc = $ftuser['location'];
            $lemail = $ftuser['email'];
        }
    }

    $empid = $_SESSION['empid'];

    if ($lrole =="QA") 
    {
        $quryfix = " AND `inc_location`='$lemploc'";
    }
    else
    {
        $quryfix = " ";
    }
    
if (isset($_GET['dtype']) && $_GET['dtype'] == "Completed") 
{
   $sql = "SELECT * FROM `inc_review` WHERE `curr_status`='Completed'".$quryfix." ORDER BY `datestamp` DESC";
   $query = mysqli_query($connection, $sql);
   $query2 = mysqli_query($connection, $sql);
}
else if (isset($_GET['dtype']) && $_GET['dtype'] == "Reported") 
{
   $sql = "SELECT * FROM `inc_review` WHERE `curr_status`='Reported'".$quryfix." ORDER BY `datestamp` DESC";
   $query = mysqli_query($connection, $sql);
   $query2 = mysqli_query($connection, $sql);
}
else if (isset($_GET['dtype']) && $_GET['dtype'] == "Review") 
{
   $sql = "SELECT * FROM `inc_review` WHERE `curr_status`='Review'".$quryfix." ORDER BY `datestamp` DESC";
   $query = mysqli_query($connection, $sql);
   $query2 = mysqli_query($connection, $sql);
}
else if (isset($_GET['dtype']) && $_GET['dtype'] == "Hold") 
{
   $sql = "SELECT * FROM `inc_review` WHERE `curr_status`='Hold'".$quryfix." ORDER BY `datestamp` DESC";
   $query = mysqli_query($connection, $sql);
   $query2 = mysqli_query($connection, $sql);
}
else if (isset($_GET['dtype']) && $_GET['dtype'] == "Reject") 
{
   $sql = "SELECT * FROM `inc_review` WHERE `curr_status`='Reject'".$quryfix." ORDER BY `datestamp` DESC";
   $query = mysqli_query($connection, $sql);
   $query2 = mysqli_query($connection, $sql);
}
else if (isset($_GET['dtype']) && $_GET['dtype'] == "Reject") 
{
   $sql = "SELECT * FROM `inc_review` WHERE `curr_status`='Reject'".$quryfix." ORDER BY `datestamp` DESC";
   $query = mysqli_query($connection, $sql);
   $query2 = mysqli_query($connection, $sql);
}
else if (isset($_GET['se'])) 
{
    $sql = "SELECT * FROM `inc_review`";
    
    $filtered_get = array_filter($_GET); 
    if (count($filtered_get)) 
    { 
        $sql .= " WHERE";
    }

    if (isset($_GET['words']) && $_GET['words'] !="") 
    {
        $getwords = $_GET['words'];
        $sql .= " (`fname`='$getwords' OR `lname`='$getwords' OR `paitient_emp_id`='$getwords' OR `inc_summery`='$getwords')";
    }

    if (isset($_GET['fromdate']) && isset($_GET['todate']) && $_GET['fromdate'] !="" && $_GET['todate'] !="") 
    {
        if ($_GET['words'] !="") 
        {
           $sql .= " AND";
        }
        $getfromdate = $_GET['fromdate'];
        $gettodate = $_GET['todate'];

        $sql .= " (`date` BETWEEN '$getfromdate' AND '$gettodate')";
    }
    
    if (isset($_GET['status']) && $_GET['status']!="")
    {
        if ($_GET['fromdate'] !="" || $_GET['todate'] !="" || $_GET['words'] !="") 
        {
           $sql .= " AND";
        }
        $i=1; 
        $status = $_GET['status'];
        foreach ($status as $kstatus)
        { 
            $sql .= " `curr_status`='$kstatus'";
            if ($i!==count($status)) {
               $sql .= " OR";
            }
            $i++;
        }

    }
    if (isset($_GET['inctype']) && $_GET['inctype'] !="") 
    {
        if ($_GET['status']!="" || $_GET['words'] !="" || $_GET['fromdate'] !="") 
        {
           $sql .= " AND";
        }
        $j=1; 
        $inctype = $_GET['inctype'];
        foreach ($inctype as $kinctype)
        { 
            $sql .= " `inc_type`='$kinctype'";
            if ($j!==count($inctype)) {
               $sql .= " OR";
            }
            $j++;
        }

    }
    if (isset($_GET['incperson']) && $_GET['incperson'] !="") 
    {
        if ($_GET['inctype'] !="" || $_GET['words'] !="" || $_GET['fromdate'] !="") 
        {
           $sql .= " AND";
        }
        $k=1; 
        $incperson = $_GET['incperson'];
        foreach ($incperson as $kincperson)
        { 
            $sql .= " `inc_person`='$kincperson'";
            if ($k!==count($incperson)) {
               $sql .= " OR";
            }
            $k++;
        }

    }
    if (isset($_GET['routing']) && $_GET['routing'] !="") 
    {
        if ($_GET['incperson'] !="" || $_GET['words'] !="" || $_GET['fromdate'] !="") 
        {
           $sql .= " AND";
        }
        $l=1; 
        $routing = $_GET['routing'];
        foreach ($routing as $krouting)
        { 
            $sql .= " `inc_rating`='$krouting'";
            if ($l!==count($routing)) {
               $sql .= " OR";
            }
            $l++;
        }

    }
    if (isset($_GET['suser']) && $_GET['suser'] !="")
    {
        if ($_GET['status']!="" || $_GET['inctype']!="" || $_GET['incperson']!="" || $_GET['routing']!="" || $_GET['words'] !="" || $_GET['fromdate'] !="") 
        {
           $sql .= " AND";
        }
        $getuser = $_GET['suser'];
        $sql .= " `user`='$getuser'";
    }
    if (isset($_GET['sdepart']) && $_GET['sdepart'] !="")
    {
        if ($_GET['status']!="" || $_GET['inctype']!="" || $_GET['incperson']!="" || $_GET['routing']!="" || $_GET['words'] !="" || $_GET['fromdate'] !="") 
        {
           $sql .= " AND";
        }
        $getdpt = $_GET['sdepart'];
        $sql .= " `department`='$getdpt'";
    }
    if (isset($_GET['slocation']) && $_GET['slocation'] !="")
    {
        if ($_GET['status']!="" || $_GET['inctype']!="" || $_GET['incperson']!="" || $_GET['routing']!=""|| $_GET['words'] !="" || $_GET['fromdate'] !="") 
        {
           $sql .= " AND";
        }
        $getlocation = $_GET['slocation'];
        $sql .= " `inc_location`='$getlocation'";
    }
    if (isset($_GET['srptlocation']) && $_GET['srptlocation'] !="")
    {
        if ($_GET['status']!="" || $_GET['inctype']!="" || $_GET['incperson']!="" || $_GET['routing']!=""|| $_GET['words'] !="" || $_GET['fromdate'] !="") 
        {
           $sql .= " AND";
        }
        $getfacility = $_GET['srptlocation'];
        $sql .= " `rpt_location`='$getfacility'";
    }
$sql .= $quryfix.";";
$query = mysqli_query($connection, $sql);
$query2 = mysqli_query($connection, $sql);
//echo $sql;
}
else
{
    if ($lrole =="QA") 
    {
        $sql = "SELECT * FROM `inc_review` WHERE `inc_location`='$lemploc' ORDER BY `datestamp` DESC";
    }
    else
    {
        $sql = "SELECT * FROM `inc_review` ORDER BY `datestamp` DESC";
    }
    
    $query = mysqli_query($connection, $sql);
    $query2 = mysqli_query($connection, $sql);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="../plugins/images/icon.png">
    <title>Incidents | Incident Management System</title>
    <!-- Bootstrap Core CSS -->
   <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet">
    <link href="../plugins/bower_components/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <!-- Menu CSS -->
    <link href="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="css/colors/blue.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<style type="text/css">
    .sediv{display: none;}
    .opendiv
    {
        display: block;
        opacity: 0;
    animation: fadeIn 1s ease-in both;
    }
    .tm {display: none;}
    #example233_filter{display: none;}
    #example233_info{display: none;}
    #example233_paginate{display: none;}
    #example23_wrapper .dt-buttons{display: none;}
</style>
</head>
<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header"> <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="ti-menu"></i></a>
                <div class="top-left-part"><a class="logo" href="#"><b><img src="../plugins/images/icon.png" style="width: 90px; " alt="home" /></b></a></div>
                <ul class="nav navbar-top-links navbar-left hidden-xs">
                    <li><a href="javascript:void(0)" class="open-close hidden-xs waves-effect waves-light"><i class="icon-arrow-left-circle ti-menu"></i></a></li>
                    <li>
                        <form role="search" class="app-search hidden-xs">
                            <input type="text" id="se" placeholder="Search..." class="form-control"> <a href=""><i class="fa fa-search"></i></a> </form>
                    </li>
                </ul>
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <!-- /.dropdown -->
                    <!-- /.dropdown -->
                </ul>
            </div>
            <!-- /.navbar-header -->
            <!-- /.navbar-top-links -->
            <!-- /.navbar-static-side -->
        </nav>
        <!-- Left navbar-header -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse slimscrollsidebar">
                <ul class="nav" id="side-menu">
                    <li class="sidebar-search hidden-sm hidden-md hidden-lg">
                        <!-- input-group -->
                        <div class="input-group custom-search-form">
                            <input type="text" id="se" class="form-control" placeholder="Search..."> <span class="input-group-btn">
            <button class="btn btn-default" type="button"> <i class="fa fa-search"></i> </button>
            </span> </div>
                        <!-- /input-group -->
                    </li>
                    <li class="user-pro">
                        <a href="#" class="waves-effect"><img src="../plugins/images/user.jpg" alt="user-img" class="img-circle"> <span class="hide-menu"> Account<span class="fa arrow"></span></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li><a href="settings.php"><i class="ti-settings"></i> Account Setting</a></li>
                            <li><a href="functions/logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
                        </ul>
                    </li>
                    <li class="nav-small-cap m-t-10"> Main Menu</li>
                    <?php
                    if ($lrole=="QA" || $lrole =="Supervisor") 
                    {
                    echo '<li> <a href="index.php" class="waves-effect"><i class="linea-icon linea-basic fa-fw" data-icon="v"></i> <span class="hide-menu"> Dashboard </a>
                    </li>';
                    }
                    if ($lrole=="Admin") 
                    {
                        echo'<li> <a href="admin-dash.php" class="waves-effect"><i class="linea-icon linea-basic fa-fw" data-icon="v"></i> <span class="hide-menu"> Dashboard </a>
                    </li>';
                    }
                    ?>
                    <li> <a href="#" class="waves-effect"><i data-icon="&#xe00b;" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">Incident<span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <?php
                            if ($lrole =="Admin" || $lrole=="QA") 
                            {
                                echo '<li><a href="new-incident.php">New Incident</a></li>';
                            }
                            ?>
                            <li><a href="inc-list.php">All Incident</a></li>
                        </ul>
                    </li>
                   <?php
                    if ($lrole=="Admin") 
                    {
                      echo '<li class="nav-small-cap"> Administrator </li>
                    <li> <a href="#" class="waves-effect"><i data-icon="H" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">Access<span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="users.php">Users</a></li>
                            <li><a href="locations.php">Locations</a></li>
                            <li><a href="dept.php">Departments</a></li>
                            <li><a href="units.php">Units</a></li>
                            <li><a href="types.php">Incident Types</a></li>
                            <li><a href="rating.php">Incident Ratings</a></li>
                            <li><a href="syslogs.php">System Logs</a></li>
                            <li><a href="email-config.php">Email Configuration</a></li>
                        </ul>
                    </li>';
                    }
                    ?>
                    <li><a href="functions/logout.php" class="waves-effect"><i class="icon-logout fa-fw"></i> <span class="hide-menu">Log out</span></a></li>
                </ul>
            </div>
        </div>
        <!-- Left navbar-header end -->
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title"><?php echo $lfname." ".$llname;?></h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
                        <ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li><a href="#">Incidents</a></li>
                            <li class="active">All Incidents</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!--------------- search ------------------------>
                <div id="adse" class="sediv container">
                    <form action="" method="get">
                            <div class="row">
                            <div class="col-sm-12" style="margin-top: 30px;">
                                <h3>Advanced Search</h3>
                                 <input type="text" name="words" class="form-control" placeholder="Type here to search" style="width: 70%; float: left;">
                                 <button type="submit" name="se" class="form-control btn btn-info" style="width: 20%">Search</button>
                                 </div>
                            </div>
                            <div class="row" style="margin-top: 30px;">
                                <div class="col-sm-3">
                                <label>Period From</label>
                                <input type="date" class="form-control" name="fromdate">
                                </div>
                                <div class="col-sm-3">
                                <label>To</label>
                                <input type="date" class="form-control" name="todate">
                                </div>
                                <div class="col-sm-3">
                                </div>
                                <div class="col-sm-3">
                                </div>
                        </div>
                            <div class="row" style="margin-top: 30px;">
                                <div class="col-sm-3">
                                    <label>Status</label> <br>
                                    <input type="checkbox" name="status[]" value=""> All <br>
                                    <input type="checkbox" name="status[]" value="Reported"> Reported <br>
                                    <input type="checkbox" name="status[]" value="Review"> Reviewing <br>
                                    <input type="checkbox" name="status[]" value="Hold"> Hold <br>
                                    <input type="checkbox" name="status[]" value="Reject"> Reject <br>
                                    <input type="checkbox" name="status[]" value="Completed"> Completed <br>  
                                </div>
                                <div class="col-sm-3">
                                    <label>Type</label> <br>
                                    <input type="checkbox" name="inctype[]" value=""> All <br>
                                    <?php
                                                      $gettyp = "SELECT * FROM `inc_types` ";
                                                      $get_tp = mysqli_query($connection, $gettyp);
                                                      while ($lst_tp = mysqli_fetch_array($get_tp)) {
                                                        echo '
                                    <input type="checkbox" name="inctype[]" value="'.$lst_tp['typ_name'].'"> '.$lst_tp['typ_name'].' <br>
                                    ';
                                    }
                                    ?>
                                </div>
                                <div class="col-sm-3">
                                    <label>Person Involved</label> <br>
                                    <input type="checkbox" name="incperson[]" value=""> All <br>
                                    <input type="checkbox" name="incperson[]" value="In Patient"> In Patient <br>
                                    <input type="checkbox" name="incperson[]" value="Out Patient"> Out Patient <br>
                                    <input type="checkbox" name="incperson[]" value="Visitor"> Visitor <br>
                                    <input type="checkbox" name="incperson[]" value="Staff Member"> Consultant/Staff Member <br>
                                    <input type="checkbox" name="incperson[]" value="Property/Process"> Property/Process <br>
                                </div>
                                <div class="col-sm-3">
                                    <label>Rating</label> <br>
                                    <input type="checkbox" name="routing[]" value=""> All <br>
                                    <?php
                                    $getrat = "SELECT * FROM `inc_rating`";
                                    $get_rat = mysqli_query($connection, $getrat);
                                    while ($lst_rat = mysqli_fetch_array($get_rat)) 
                                    {
                                        echo '<input type="checkbox" name="routing[]" value="'.$lst_rat["rat_name"].'"> '.$lst_rat["rat_name"].' <br>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 30px; margin-bottom: 60px;">
                                <div class="col-sm-3">
                                    <label>User</label>
                                                <select class="form-control" name="suser">
                                                      <option value="">-- Select User--</option>
                                                      <?php
                                                      $getuser = "SELECT * FROM `admin` ";
                                                      $get_user = mysqli_query($connection, $getuser);
                                                      while ($lst_user = mysqli_fetch_array($get_user)) {
                                                      echo '<option value="'.$lst_user["empid"].'">'.$lst_user["fname"]." ".$lst_user["lname"].'('.$lst_user["empid"].')</option>';
                                                        }
                                                      ?>
                                                  </select>
                                </div>
                                <div class="col-sm-3">
                                    <label>Department</label>
                                                <select class="form-control" name="sdepart">
                                                      <option value="">-- Select Department--</option>
                                                      <?php
                                                      $getdpt = "SELECT * FROM `inc_depart` ";
                                                      $get_dpt = mysqli_query($connection, $getdpt);
                                                      while ($lst_dept = mysqli_fetch_array($get_dpt)) {
                                                      echo '<option value="'.$lst_dept["dpt_name"].'">'.$lst_dept["dpt_name"].'</option>';
                                                        }
                                                      ?>
                                                  </select>
                                </div>
                                <div class="col-sm-3">
                                    <label>Location Occurred</label>
                                                <select class="form-control" name="slocation">
                                                      <option value="">-- Select Location--</option>
                                                      <?php
                                                      if ($lrole =="QA")
                                                      {
                                                            echo '<option value="'.$lemploc.'">'.$lemploc.'</option>';
                                                      }
                                                      else
                                                      {
                                                        $getoclc = "SELECT * FROM `inc_locations`";
                                                        $get_oclc = mysqli_query($connection, $getoclc);
                                                        while ($lst_oclc = mysqli_fetch_array($get_oclc)) {
                                                            echo '<option value="'.$lst_oclc["loc_name"].'">'.$lst_oclc["loc_name"].'</option>';
                                                            }
                                                        }
                                                      ?>
                                                  </select>
                                </div>
                                <div class="col-sm-3">
                                    <label>Location Reported</label>
                                    <select class="form-control" name="srptlocation">
                                                      <option value="">-- Select Location--</option>
                                                      <?php
                                                      $getrplc = "SELECT * FROM `inc_locations`";
                                                      $get_rplc = mysqli_query($connection, $getrplc);
                                                      while ($lst_rplc = mysqli_fetch_array($get_rplc)) {
                                                      echo '<option value="'.$lst_rplc["loc_name"].'">'.$lst_rplc["loc_name"].'</option>';
                                                        }
                                                      ?>
                                                  </select>
                                </div>
                            </div>
                            </form>
                </div>
                <!-----------------end search ---------------->
                <!-- /row -->
                <div class="row">
                   <div class="col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title m-b-0">Incidents ( <x style="color: orange;"><?php echo mysqli_num_rows($query);?></x> )</h3>
                            <p class="text-muted m-b-30">Export data to CSV, Excel, PDF & Print</p>
                            <!--------------------------------------------------------------->
<div class="table-responsive">
                                <table id="example233" class="display table-responsive tm" cellspacing="0" width="100%">
                                <?php 

                                    if (mysqli_num_rows($query2)==0) {
                                                    echo "";
                                                }
                                                else{

                                                    echo '
                                                    <thead>
                                                    <tr>
                                                        <th> Inc. ID</th>
                                                        <th> Inc. Date</th>
                                                        <th> Person Involved</th>
                                                        <th> Paitient/Emp ID</th>
                                                        <th> Name/Property</th>
                                                        <th> Status</th>
                                                        <th> Inc. Type</th>
                                                        <th> Description</th>
                                                        <th> Unit/Department</th>
                                                        <th> Location Reported</th>
                                                        <th> Occurred location</th>
                                                        <th> Summery</th>
                                                        <th> Inc Rating</th>
                                                        <th> Remark</th>
                                                        <th> Root Cause</th>
                                                        <th> Reported Date</th>
                                                        <th> User Involved</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th> Inc. ID</th>
                                                        <th> Inc. Date</th>
                                                        <th> Person Involved</th>
                                                        <th> Paitient/Emp ID</th>
                                                        <th> Name/Property</th>
                                                        <th> Status</th>
                                                        <th> Inc. Type</th>
                                                        <th> Description</th>
                                                        <th> Unit/Department</th>
                                                        <th> Location Reported</th>
                                                        <th> Occurred location</th>
                                                        <th> Summery</th>
                                                        <th> Inc Rating</th>
                                                        <th> Remark</th>
                                                        <th> Root Cause</th>
                                                        <th> Reported Date</th>
                                                        <th> User Involved</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    ';
                                                }

                                        while ($row2 = mysqli_fetch_array($query2)) {
                                            // $id = $row["id"]
                                            if ($row2["date"]=="" && $row2["nodate"]==1) 
                                                    {
                                                       $getdate = "No Date";
                                                    }
                                                    else
                                                    {
                                                        $getdate = $row2["date"];
                                                    }
                                            $lbcl = "label label-default";
                                                    if ($row2["inc_person"]=="In Patient") 
                                                    {
                                                       $lbcl = "label label-success";
                                                    }
                                                    if ($row2["inc_person"]=="Out Patient") 
                                                    {
                                                       $lbcl = "label label-primary";
                                                    }
                                                    if ($row2["inc_person"]=="Property/Process") 
                                                    {
                                                       $lbcl = "label label-info";
                                                    }
                                                    if ($row2["inc_person"]=="Visitor") 
                                                    {
                                                       $lbcl = "label label-warning";
                                                    }
                                                    if ($row2["inc_person"]=="Staff Member") 
                                                    {
                                                       $lbcl = "label label-danger";
                                                    }

                                                    
                                    echo '
                                    

                                        <tr>
                                            <td>'.$row2["review_id"].'</td>
                                            <td>'.$getdate.'</td>
                                            <td class="max-texts"><span class="'.$lbcl.'">'.$row2["inc_person"].'</span></td>
                                            <td>'.$row2["paitient_emp_id"].'</td>
                                            <td>'.$row2["fname"]." ".$row2["lname"].'</td>
                                            <td><b>'.$row2["curr_status"].'</b></td>
                                            <td>'.$row2["inc_type"].'</td>
                                            <td>'.$row2["inc_details"].'</td>
                                            <td>'.$row2["location"].' / '.$row2["department"].'</td>
                                            <td>'.$row2["rpt_location"].'</td>
                                            <td>'.$row2["inc_location"].'</td>
                                            <td>'.$row2["inc_summery"].'</td>
                                            <td>'.$row2["inc_rating"].'</td>
                                            <td>'.$row2["remark"].'</td>
                                            <td>'.$row2["root_cause"].'</td>
                                            <td>'.$row2["datestamp"].'</td>
                                            <td>'.$row2["user"].'</td>
                                         </tr>
                                    ';

                                    }

                                    ?>
                                    </tbody>
                                </table>
                            </div>
<!------------------------------------------------->
                            <div class="table-responsive">
                                <table id="example23" class="display " cellspacing="0" width="100%">
                                <?php 

                                    if (mysqli_num_rows($query)==0) {
                                                    echo "<i style='color:brown;'>No Incidents Here :( </i> ";
                                                }
                                                else{

                                                    echo '
                                                    <thead>
                                                    <tr>
                                                        <th> Inc. ID</th>
                                                        <th> Incident Date</th>
                                                        <th> Name/Property</th>
                                                        <th> Person Involved</th>
                                                        <th> Status</th>
                                                        <!--<th> Unit/Dept.</th>-->
                                                        <th> Reported Location</th>
                                                        <th> Occurred location</th>
                                                        <th> User Involved</th>
                                                        <th> Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th> Inc. ID</th>
                                                        <th> Incident Date</th>
                                                        <th> Name/Property</th>
                                                        <th> Person Involved</th>
                                                        <th> Status</th>
                                                        <!--<th> Unit/Dept.</th>-->
                                                        <th> Reported Location</th>
                                                        <th> Occurred location</th>
                                                        <th> User Involved</th>
                                                        <th> Action</th>
                                                         
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    ';
                                                }

                                        while ($row = mysqli_fetch_array($query)) {
                                            // $id = $row["id"]
                                            if ($row["date"]=="" && $row["nodate"]==1) 
                                                    {
                                                       $getdate = "No Date";
                                                    }
                                                    else
                                                    {
                                                        $getdate = $row["date"];
                                                    }
                                            $lbcl = "label label-default";
                                                    if ($row["inc_person"]=="In Patient") 
                                                    {
                                                       $lbcl = "label label-success";
                                                    }
                                                    if ($row["inc_person"]=="Out Patient") 
                                                    {
                                                       $lbcl = "label label-primary";
                                                    }
                                                    if ($row["inc_person"]=="Property/Process") 
                                                    {
                                                       $lbcl = "label label-info";
                                                    }
                                                    if ($row["inc_person"]=="Visitor") 
                                                    {
                                                       $lbcl = "label label-warning";
                                                    }
                                                    if ($row["inc_person"]=="Staff Member") 
                                                    {
                                                       $lbcl = "label label-danger";
                                                    }

                                                    if ($lrole == "Supervisor") 
                                                    {
                                                            $link = "inc-rpdetails.php?id=".$row["review_id"];
                                                    }
                                                    else
                                                    {
                                                            $link = "inc-details.php?id=".$row["review_id"];
                                                    }
                                    echo '
                                    

                                        <tr>
                                            <td>'.$row["review_id"].'</td>
                                            <td>'.$getdate.'</td>
                                            <td>'.$row["fname"]." ".$row["lname"].'</td>
                                            <td class="max-texts"><span class="'.$lbcl.'">'.$row["inc_person"].'</span></td>
                                            <td><b>'.$row["curr_status"].'</b></td>
                                            <!--<td>'.$row["location"].' / '.$row["department"].'</td>-->
                                            <td>'.$row["rpt_location"].'</td>
                                            <td>'.$row["inc_location"].'</td>
                                            <td>'.$row["user"].'</td>
                                            <td><a href="'.$link.'" class="btn btn-success" style="color:white">View</a></td>
                                       
                                         </tr>
                                    ';

                                    }

                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
            <footer class="footer text-center"> <?php echo date('Y'); ?> &copy; Hemas Hospitals. All Rights Reserved.</footer>
        </div>
        <!-- /#page-wrapper -->
    </div>
    <?php
    //mysqli_close($connection);
    ?>
    <!-- /#wrapper -->
    <!-- jQuery -->
    <script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/dist/js/tether.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../plugins/bower_components/bootstrap-extension/js/bootstrap-extension.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/custom.min.js"></script>
    <script src="../plugins/bower_components/datatables/jquery.dataTables.min.js"></script>
    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <!-- end - This is for export functionality only -->
    <script>
    $(document).ready(function() {
        $('#myTable').DataTable();
        $(document).ready(function() {
            var table = $('#example').DataTable({
                "columnDefs": [{
                    "visible": false,
                    "targets": 2
                }],
                "order": [
                    [2, 'asc']
                ],
                "displayLength": 25,
                "drawCallback": function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;
                    api.column(2, {
                        page: 'current'
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                            last = group;
                        }
                    });
                }
            });
            // Order by the grouping
            $('#example tbody').on('click', 'tr.group', function() {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });
        });
    });
    $('#example23').DataTable({
        dom: 'Bfrtip',
        "order": [[ 1, "desc" ]],
        buttons: [
            'csv','excel',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4' 
            }
        ]
    });
    $('#example233').DataTable({
        dom: 'Bfrtip',
        "order": [[ 1, "desc" ]],
        buttons: [
            'csv','excel',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4' 
            }
        ]
    });
    </script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
    <script type="text/javascript">
$(document).ready(function(){
    $('#se').click(function() {
    $('#adse').toggleClass('opendiv');
    });
    });
</script>
</body>
</html>