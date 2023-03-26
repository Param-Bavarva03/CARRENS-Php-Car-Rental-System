<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['login'])==0)
{ 
header('location:index.php');
}
else
{
  if(isset($_POST['updateprofile']))
  {
  $name=$_POST['fullname'];
  $mobileno=$_POST['mobilenumber'];
  $dob=$_POST['dob'];
  $adress=$_POST['address'];
  $city=$_POST['city'];
  $country=$_POST['country'];
  $email=$_SESSION['login'];
  $sql="UPDATE tblusers set FullName=:name,ContactNo=:mobileno,dob=:dob,Address=:adress,City=:city,Country=:country where EmailId=:email";
  $query = $dbh->prepare($sql);
  $query->bindParam(':name',$name,PDO::PARAM_STR);
  $query->bindParam(':mobileno',$mobileno,PDO::PARAM_STR);
  $query->bindParam(':dob',$dob,PDO::PARAM_STR);
  $query->bindParam(':adress',$adress,PDO::PARAM_STR);
  $query->bindParam(':city',$city,PDO::PARAM_STR);
  $query->bindParam(':country',$country,PDO::PARAM_STR);
  $query->bindParam(':email',$email,PDO::PARAM_STR);
  $query->execute();
  $msg="Profile Updated Successfully";
  }


if(isset($_POST['verifyemail']))
{
  
  $useremail=$_POST['emailid'];
  
    $otp = rand(1000,9999);
    $date_otp= date("Y-m-d H:i:s");
    $def_value= 0;
    require './PHPMailerAutoload.php';
    $mail = new PHPMailer;                   
                  $mail->isSMTP();                                      
                  $mail->Host = 'smtp.gmail.com';  
                  $mail->SMTPAuth = true;                              
                  $mail->Username = 'car.rental831@gmail.com';                 
                  $mail->Password = 'PBjaRB&138';                          
                  $mail->SMTPSecure = 'ssl';                           
                  $mail->Port = 465;                                   
                  //$mail->SMTPDebug = 3;
                  $mail->setFrom('car.rental831@gmail.com', 'RENTSCAR');
                  $mail->addAddress($useremail,$useremail);     
                  $mail->isHTML(true);                                  
                  $mail->Subject = 'OTP for verification';
                  $mail->Body    = "Hello user thanks for registering into carrental here is your otp  : " . $otp;
                if(!$mail->send()) 
                {
                  echo "<script>alert('Email not sent');</script>";
                }
                else 
                {
                echo "<script>alert('Email sent on your Email ID please check it out');</script>";
                }

    header('location:profile.php');
    if($mail== 1) 
    {
      $sql2="INSERT INTO otp(otp,is_expired,create_at) VALUES (:otp,:def_value,:date_otp)";
      $query2= $dbh->prepare($sql2);
      $query2->bindParam(':otp',$otp,PDO::PARAM_STR);
      $query2->bindParam(':def_value',$def_value,PDO::PARAM_STR);
      $query2->bindParam(':date_otp',$date_otp,PDO::PARAM_STR);
      $query2->execute();
      $cu_id=mysql_insert_id($query2);
      if(!empty($current_id)) 
      {
        $success=1;
      }
    }
    else
    {
      echo "<script>alert('Email Not Exists');</script>";
    }
}
if(isset($_POST["sub_otp"])) 
{
  $useremail=$_POST['emailid'];
  $otp= $_POST['otp'];
  $sql3= "SELECT * from otp where otp= :otp";
  $query3 = $dbh->prepare($sql3);
  $query3->bindParam(':otp',$otp,PDO::PARAM_STR);
  $query3->execute();
    $sql4= "UPDATE otp SET is_expired = 1 WHERE otp=:otp";
    $query4 = $dbh->prepare($sql4);
    $query4->bindParam(':otp',$otp,PDO::PARAM_STR);
    $query4->execute();
    if($query4)
    {
      require './PHPMailerAutoload.php';
      $mail = new PHPMailer;                   
                  $mail->isSMTP();                                      
                  $mail->Host = 'smtp.gmail.com';  
                  $mail->SMTPAuth = true;                              
                  $mail->Username = 'car.rental831@gmail.com';                 
                  $mail->Password = 'PBjaRB&138';                          
                  $mail->SMTPSecure = 'ssl';                           
                  $mail->Port = 465;                                   
                  //$mail->SMTPDebug = 3;
                  $mail->setFrom('car.rental831@gmail.com', 'RENTSCAR');
                  $mail->addAddress($useremail,$useremail);     
                  $mail->isHTML(true);                                  
                  $mail->Subject = 'Verification done';
                  $mail->Body    = "User you are verified";
                if(!$mail->send()) 
                {
                  echo "<script>alert('Email not sent');</script>";
                }
                else 
                {
                echo "<script>alert('OTP sent on your Email ID please check it out');</script>";
                }

                header('location:profile.php');
                $sql4= "UPDATE tblusers SET is_verified = 1 WHERE EmailId=:useremail";
                $query4 = $dbh->prepare($sql4);
                $query4->bindParam(':useremail',$useremail,PDO::PARAM_STR);
                $query4->execute();
    }
    else
    {
      echo "<script>alert('Wrong OTP');</script>";
    }
}

?>
  <!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="keywords" content="">
<meta name="description" content="">
<title>RENTSCAR</title>
<!--Bootstrap -->
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
<!--Custome Style -->
<link rel="stylesheet" href="assets/css/style.css" type="text/css">
<!--OWL Carousel slider-->
<link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
<link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css">
<!--slick-slider -->
<link href="assets/css/slick.css" rel="stylesheet">
<!--bootstrap-slider -->
<link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
<!--FontAwesome Font Style -->
<link href="assets/css/font-awesome.min.css" rel="stylesheet">

<!-- SWITCHER -->
		<link rel="stylesheet" id="switcher-css" type="text/css" href="assets/switcher/css/switcher.css" media="all" />
		<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/red.css" title="red" media="all" data-default-color="true" />
		<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/orange.css" title="orange" media="all" />
		<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/blue.css" title="blue" media="all" />
		<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/pink.css" title="pink" media="all" />
		<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/green.css" title="green" media="all" />
		<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/purple.css" title="purple" media="all" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/favicon-icon/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/favicon-icon/apple-touch-icon-114-precomposed.html">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/favicon-icon/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/images/favicon-icon/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="assets/images/favicon-icon/favicon.png">
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet"> 
 
</head>
<body>  

<!-- Start Switcher -->
<?php include('includes/colorswitcher.php');?>
<!-- /Switcher -->  
        
<!--Header-->
<?php include('includes/header.php');?>
<!-- /Header --> 



<!--Page Header-->
<section class="page-header profile_page">
  <div class="container">
    <div class="page-header_wrap">
      <div class="page-heading">
        <h1>Your Profile</h1>
      </div>
      <ul class="coustom-breadcrumb">
        <li><a href="#">Home</a></li>
        <li>Profile</li>
      </ul>
    </div>
  </div>
  <!-- Dark Overlay-->
  <div class="dark-overlay"></div>
</section>
<!-- /Page Header--> 


<?php 
$useremail=$_SESSION['login'];
$sql = "SELECT * from tblusers where EmailId=:useremail";
$query = $dbh -> prepare($sql);
$query -> bindParam(':useremail',$useremail, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{ ?>
<section class="user_profile inner_pages">
  <div class="container">
    <div class="user_profile_info gray-bg padding_4x4_40">
      <div class="upload_user_logo"><img src=" ./<?php echo htmlentities($result->usimaje);?>" width="300" height="200" style="border:solid 1px #000">
      </div>

      <div class="dealer_info">
        <h5><?php echo htmlentities($result->FullName);?></h5><br>
        <p><?php echo htmlentities($result->Address);?></p>
         <p> <?php echo htmlentities($result->City);?>&nbsp;</p>
         <p> <?php echo htmlentities($result->Country);?></p>
      </div>
    </div>
  
    <div class="row">
      <div class="col-md-3 col-sm-3">
        <?php include('includes/sidebar.php');?>
      <div class="col-md-6 col-sm-8">
        <div class="profile_wrap">
          <h5 class="uppercase underline">General Settings</h5>
          <?php  
         if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>
          <form  method="post">
           <div class="form-group">
              <label class="control-label">Reg Date -</label>
             <?php echo htmlentities($result->RegDate);?>
            </div>
             <?php if($result->UpdationDate!=""){?>
            <div class="form-group">
              <label class="control-label">Last Update at  -</label>
             <?php echo htmlentities($result->UpdationDate);?>
            </div>
            <?php } ?>
            <div class="form-group">
              <label class="control-label">Full Name</label>
              <input class="form-control white_bg" name="fullname" value="<?php echo htmlentities($result->FullName);?>" id="fullname" type="text"  required>
            </div>
            <div class="form-group">
              <label class="control-label">Email Address</label>
              <input class="form-control white_bg" value="<?php echo htmlentities($result->EmailId);?>" name="emailid" id="email" type="email" required readonly>
            </div>
            <div class="form-group">
              <label class="control-label">Phone Number</label>
              <input class="form-control white_bg" name="mobilenumber" value="<?php echo htmlentities($result->ContactNo);?>" id="phone-number" type="text" required>
            </div>
            <div class="form-group">
              <label class="control-label">Date of Birth&nbsp;(dd/mm/yyyy)</label>
              <input class="form-control white_bg" value="<?php echo htmlentities($result->dob);?>" name="dob" placeholder="dd/mm/yyyy" id="birth-date" type="text" >
            </div>
            <div class="form-group">
              <label class="control-label">Your Address</label>
              <textarea class="form-control white_bg" name="address" rows="4" ><?php echo htmlentities($result->Address);?></textarea>
            </div>
            <div class="form-group">
              <label class="control-label">Country</label>
              <input class="form-control white_bg"  id="country" name="country" value="<?php echo htmlentities($result->City);?>" type="text">
            </div>
            <div class="form-group">
              <label class="control-label">City</label>
              <input class="form-control white_bg" id="city" name="city" value="<?php echo htmlentities($result->City);?>" type="text">
            </div>
            <?php }} ?>
           
            <div class="form-group">
              <button type="submit" name="updateprofile" class="btn">Save Changes <span class="angle_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span></button>
            </div>
          </form>
          <h5 class="uppercase underline">Email Verification</h5>
          <form method="post">
              <h5 class="uppercase underline">If you are validated plz ignore this form.</h5>
              <div class="form-group">
              <label class="control-label">Email Address</label>
              <input class="form-control white_bg" value="<?php echo htmlentities($result->EmailId);?>" name="emailid" id="email" type="email" required>
              </div> 
              <div class="form-group">
              <input type="submit" name="verifyemail"class="btn btn-block" value="Verify it"> 
              </div>
              <div class="form-group">
               <input type="text" class="form-control" name="otp" placeholder="Enter the otp"> 
              </div>
              <div class="form-group">
              <button type="submit" name="sub_otp" class="btn">Submit otp<span class="angle_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
              </button>
              </div>
          </form>
          
        </div>
      </div>
    </div>
  </div>
</section>
<!--/Profile-setting--> 

<<!--Footer -->
<?php include('includes/footer.php');?>
<!-- /Footer--> 

<!--Back to top-->
<div id="back-top" class="back-top"> <a href="#top"><i class="fa fa-angle-up" aria-hidden="true"></i> </a> </div>
<!--/Back to top--> 

<!--Login-Form -->
<?php include('includes/login.php');?>
<!--/Login-Form --> 

<!--Register-Form -->
<?php include('includes/registration.php');?>
<!--/Register-Form --> 

<!--Forgot-password-Form -->
<?php include('includes/forgotpassword.php');?>
<!--/Forgot-password-Form --> 

<!-- Scripts --> 
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script> 
<script src="assets/js/interface.js"></script> 
<!--Switcher-->
<script src="assets/switcher/js/switcher.js"></script>
<!--bootstrap-slider-JS--> 
<script src="assets/js/bootstrap-slider.min.js"></script> 
<!--Slider-JS--> 
<script src="assets/js/slick.min.js"></script> 
<script src="assets/js/owl.carousel.min.js"></script>

</body>
</html>
<?php } ?>