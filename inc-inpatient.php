<?php 
    ob_start();
    require_once('../admin/functions/db.php');
    // Initialize the session
    session_start();


// insert incident
if (isset($_POST['sub'])) {
    // Add task to DB
$loguser = "Public User";
$nodate="0";
$subscribe = "0";
$inc_person = $_POST['incperson'];
$inc_id=time();
$pid = $_POST['pid'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$reason = $_POST['adreason'];
$admit_date = $_POST['addate'];
$location = $_POST['slocation'];
$ocunit = $_POST['sunit'];
$inc_details = $_POST['description'];
if (isset($_POST['nodate'])) {
    $nodate = "1";
}
$date = $_POST['incdate'];
$cemail = $_POST['subemail'];
if (isset($_POST['emailsub'])) {
    $subscribe = "1";
}
//$inc_type = $_POST['inctype'];

$getdep = "SELECT `dpt_id`, `department` FROM `inc_deplocation` WHERE `location`='$ocunit'";
$qgetdep = mysqli_query($connection, $getdep);
while ($gdpt = mysqli_fetch_array($qgetdep)) 
{
    $inc_dpt_id = $gdpt['dpt_id'];
    $inc_dpt = $gdpt['department'];
}

$rpt_location = "Thalawathugoda Hospital";

if ($subscribe =="1" && $cemail !="") 
{
    $to = $cemail;
    $subject = "Incident Update";
    $txt = "Your incident has been recorded.";
    $headers = "From: admin@example.com" . "\r\n";

    mail($to,$subject,$txt,$headers);

}
////////////////////////////////////////////////////
$emconfig = "SELECT * FROM `inc_email` WHERE `role`='Admin' OR (`role`='QA' AND `location`='$location')";
$getconfig = mysqli_query($connection, $emconfig);
while ($sendad = mysqli_fetch_array($getconfig)) 
{
$adto = $sendad['email'];
$adsubject = "New In Patient Incident Reported. (EN)";

$admessage = "
<html>
<head>
<title>New In Patient Incident (EN)</title>
</head>
<body>
<h2 align=center>New In Patient Incident! (EN)</h2>
<table>
<tr>
<td>Involved Person : </td>
<td>".$inc_person."</td>
</tr>
<tr>
<td>Patient ID : </td>
<td>".$pid."</td>
</tr>
<tr>
<td>Patient Name : </td>
<td>".$fname." ".$lname."</td>
</tr>
<tr>
<td>Age : </td>
<td>".$age."</td>
</tr>
<tr>
<td>Gender : </td>
<td>".$gender."</td>
</tr>
<tr>
<td>Email : </td>
<td>".$cemail."</td>
</tr>
<tr>
<td>Admit Reason : </td>
<td>".$reason."</td>
</tr>
<tr>
<td>Admit Date : </td>
<td>".$admit_date."</td>
</tr>
<tr>
<td>Occurred Location</td>
<td>".$location."</td>
</tr>
<tr>
<td>Department : </td>
<td>".$inc_dpt."</td>
</tr>
<tr>
<td>Unit : </td>
<td>".$ocunit."</td>
</tr>
<tr>
<td>Incident Details : </td>
<td>".$inc_details."</td>
</tr>
<tr>
<td>Incident Date : </td>
<td>".$date."</td>
</tr>
<tr>
<td>Reported Location</td>
<td>".$rpt_location."</td>
</tr>
</table>
</body>
</html>
";

// Always set content-type when sending HTML email
$adheaders = "MIME-Version: 1.0" . "\r\n";
$adheaders .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$adheaders .= 'From: admin@example.com' . "\r\n";


mail($adto,$adsubject,$admessage,$adheaders);
}
////////////////////////////////////////////////////

$sql = "INSERT INTO `inc_inpatient`(`inc_inpat_id`, `pid`, `fname`, `lname`, `age`, `gender`, `reason`, `admit_date`, `inc_details`, `nodate`, `date`, `department`, `location`, `rpt_location`, `in_location`, `email`, `subscribe`) VALUES ('$inc_id','$pid', '$fname', '$lname', '$age', '$gender', '$reason', '$admit_date', '$inc_details', '$nodate', '$date', '$inc_dpt', '$ocunit', '$rpt_location', '$location', '$cemail', '$subscribe')";

$sql2 ="INSERT INTO `inc_review`(`review_id`,`inc_id`, `paitient_emp_id`, `fname`, `lname`, `inc_person`,`inc_type`, `inc_details`, `nodate`, `date`, `department`, `location`, `rpt_location`, `inc_location`, `email`, `subscribe`, `curr_status`,`inc_summery`, `inc_rating`, `remark`, `root_cause`, `datestamp`,`user`, `status`) VALUES ('','$inc_id','$pid','$fname','$lname','$inc_person','','$inc_details','$nodate','$date','$inc_dpt','$ocunit','$rpt_location','$location','$cemail','$subscribe','Reported','','','','',CURRENT_TIMESTAMP(),'$loguser','1')";

$sql3 ="INSERT INTO `inc_logs`(`inc_id`, `remark`, `curr_status`, `user`) VALUES ('$inc_id','Reported','Reported','$loguser')";

    try {
      mysqli_query($connection, $sql);
      mysqli_query($connection, $sql2);
      mysqli_query($connection, $sql3);

      $logsql = "INSERT INTO `inc_syslogs`(`logdes`, `user`) VALUES ('In Patient Incident Added by Public User (EN)','$loguser')";
      mysqli_query($connection, $logsql);

      header('Location:index.php?posted');

      }

     catch (Exception $e) {
        $e->getMessage();
        echo "Error";
    }
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
    <title>New in-patient Incident| Incident Management System (EN)</title>
    <!-- Bootstrap Core CSS -->
   <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet">
    <!-- Wizard CSS -->
    <link href="../plugins/bower_components/jquery-wizard-master/css/wizard.css" rel="stylesheet">
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
</head>
<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
  <div id="wrapper">
        
        <!-- Page Content -->
        <div id="">
            <div class="container">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Create New Incident</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
                        <ol class="breadcrumb">
                            <li><a href="#">Public User</a></li>
                            <li><a href="#">Incident</a></li>
                            <li class="active">New</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                    
                </div>
                
               
                       <!-- .row -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title m-b-0">Create New In Paitent Incident</h3>
                            <p class="text-muted m-b-30 font-13"> Provide necessary details.</p>
                            <div id="" class="wizard">
                                <ul class="wizard-steps" role="tablist">
                                    
                                    <li role="tab">
                                        <h4><span><i class="ti-info"></i></span>Incident Details</h4> </li>

                                    
                                </ul>
                                <form id="" class="form-horizontal" action="" method="post">
                                    <div class="wizard-content">
                                        <div class="wizard-pane active" role="tabpanel">
                                            
                                                <div class="form-group">
                                                    
                                                    <div class="col-md-12">
                                                        <h4>Personal Information</h4>
                                                    <hr>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="col-xs-3 control-label">Person Involved</label>
                                                        <input type="text" class="form-control" name="incperson" required="" value="In Patient" readonly="">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="col-xs-3 control-label">PID</label>
                                                        <input type="text" class="form-control" name="pid" required="">
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <label class="col-xs-3 control-label">First Name</label>
                                                        <input type="text" class="form-control" name="fname" required="">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="col-xs-3 control-label">Last Name</label>
                                                        <input type="text" class="form-control" name="lname" required="">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="col-xs-3 control-label">Age</label>
                                                        <input type="number" class="form-control" name="age" required="">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="col-xs-3 control-label">Gender</label>
                                                        <select class="form-control" name="gender">
                                                            <option value="">-- Select --</option>
                                                            <option value="Male">Male</option>
                                                            <option value="Female">Female</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="col-xs-3 control-label">Email</label>
                                                        <input type="checkbox" name="emailsub"> To receive update
                                                        <input type="text" name="subemail" class="form-control">
                                                    </div>
                                                    <div class="col-md-12" style="margin-top: 40px">
                                                        <h4>Visit Information</h4>
                                                    <hr>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="col-xs-3 control-label">Admit Reason</label>
                                                        <input type="text" class="form-control" name="adreason" required="">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="col-xs-3 control-label">Admit Date</label>
                                                        <input type="date" class="form-control" name="addate" required="">
                                                    </div>
                                                    
                                                    <div class="col-md-12" style="margin-top: 40px">
                                                        <h4>How this incident occur ?</h4>
                                                    <hr>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="col-xs-3 control-label">Incident Details</label>
                                                        <textarea class="form-control" name="description"></textarea>
                                                    </div>
                                                     
                                                    <div class="col-md-6">
                                                        <label class="col-xs-3 control-label">Date the incident  </label>
                                                            <input type="checkbox" name="nodate"> Not Known
                                                            <input type="date" class="form-control" name="incdate" >
                                                        
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="col-xs-3 control-label">Department/Unit Occurred</label>
                                                <select class="form-control" name="sunit">
                                                      <option value="">-- Select Location--</option>
                                                      <?php
                                                      $getdptlc = "SELECT * FROM `inc_deplocation`";
                                                      $get_dptlc = mysqli_query($connection, $getdptlc);
                                                      while ($lst_deptlc = mysqli_fetch_array($get_dptlc)) {
                                                      echo '<option value="'.$lst_deptlc["location"].'">'.$lst_deptlc["displytxt"].'</option>';
                                                        }
                                                      ?>
                                                  </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="col-xs-3 control-label">Location Occurred</label>
                                                <select class="form-control" name="slocation">
                                                      <option value="">-- Select Location--</option>
                                                      <?php
                                                      $getlc = "SELECT * FROM `inc_locations`";
                                                      $get_loc = mysqli_query($connection, $getlc);
                                                      while ($lst_loc = mysqli_fetch_array($get_loc)) {
                                                      echo '<option value="'.$lst_loc["loc_name"].'">'.$lst_loc["loc_name"].'</option>';
                                                        }
                                                      ?>
                                                  </select>
                                                    </div>
                                                    <!--<div class="col-md-6">
                                                    <label class="col-xs-3 control-label">Incident Type</label>
                                                        <select class="form-control" name="inctype">
                                                            <option value="">-- Select Incident Type--</option>
                                                            <option value="Client Insident">Client Insident</option>
                                                            <option value="Asset">Asset</option>
                                                            <option value="Assoult/Violation/Abouse">Assoult/Violation/Abouse</option>
                                                            <option value="Environmental">Environmental</option>
                                                            <option value="Reputation">Reputation</option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                    </div>-->
                                                    <div class="col-md-12">
                                                    <div class="col-md-4" style="margin-top: 20px; margin-left: -8px">
                                                    <button type="submit" class="form-control btn btn-success" name="sub">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                             </form>
                                            
                                            
                                        </div>
                                        
                                        
                                    </div>
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
                
            </div>
            <!-- /.container-fluid -->
            <footer class="footer text-center"> <?php echo date('Y'); ?> &copy; Hemas Hospitals. All Rights Reserved. </footer>
        </div>
        <!-- /#page-wrapper -->
    </div>
    <?php 
    mysqli_close($connection);
    ?>
    <!-- /#wrapper -->
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
    <!-- Form Wizard JavaScript -->
    <script src="../plugins/bower_components/jquery-wizard-master/dist/jquery-wizard.min.js"></script>
    <!-- FormValidation -->
    <link rel="stylesheet" href="../plugins/bower_components/jquery-wizard-master/libs/formvalidation/formValidation.min.css">
    <!-- FormValidation plugin and the class supports validating Bootstrap form -->
    <script src="../plugins/bower_components/jquery-wizard-master/libs/formvalidation/formValidation.min.js"></script>
    <script src="../plugins/bower_components/jquery-wizard-master/libs/formvalidation/bootstrap.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/custom.min.js"></script>
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
</body>
</html>