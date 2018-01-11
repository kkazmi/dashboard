<?php
include_once('common.php');
$allowed = array(".", "-", "_");
$email_id = "";
$password = "";
$confirmpassword = "";
$spendingpassword = "";
$confirmspendingpassword = "";

$error = array();
if(isset($_POST['btnsignup']))
{
//  var_dump($_POST);
  $email_id = $_POST['txtEmailID'];
  $password = $_POST['signuppassword'];
  $confirmpassword = $_POST['confirmpassword'];
  $spendingpassword = $_POST['spendingpassword'];
  $confirmspendingpassword = $_POST['confirmspendingpassword'];

  if (empty($email_id))
  {
    $error['emailError'] = "Please Provide valid email id";
  }
  if(empty($password))
  {
    $error['passwordError'] = "Please Provide valid Password";
  }
  if(empty($confirmpassword))
  {
    $error['confirmpasswordError'] = "Please Provide valid Password";
  }
  else if($confirmpassword != $password)
  {
    $error['confirmpasswordError'] = "Password and Confirm Password Must be same";
  }
  if(empty($spendingpassword))
  {
    $error['spendingpasswordError'] = "Please Provide valid Spending Password";
  }
  if(empty($confirmspendingpassword))
  {
    $error['confirmspendingpasswordError'] = "Please Provide valid Spending Password";
  }
  else if($confirmspendingpassword != $spendingpassword)
  {
    $error['confirmpasswordError'] = "Spending Password and Confirm Password Must be same";
  }

  if (!isEmail($email_id))
  {
    $error['emailError'] = "Please Provide valid email id";
  }

  $email_id = $mysqli->real_escape_string(strip_tags($email_id));
  $password_value = hash('sha256',addslashes(strip_tags($password)));
  $qstring = "select coalesce(id,0) as id
        from users WHERE encrypt_username = '" . hash('sha256',$email_id) . "'";

  $result = $mysqli->query($qstring);
  $user = $result->fetch_assoc();
  //var_dump($user);
  if ($user['id']> 0)
  {
    $error['emailError'] = "User with email id $email_id already exist.";
  }

  if(empty($error))
  {
    $email_id = $mysqli->real_escape_string(strip_tags($email_id));
    $password_value = hash('sha256',addslashes(strip_tags($password)));
    $spendingpassword_value = hash('sha256',addslashes(strip_tags($spendingpassword)));

    $qstring = "insert into `users`( `date`, `ip`, `username`,
    `encrypt_username`, `password`, `transcation_password`,
    `email`) values (";
    $qstring .= "now(), ";
    $qstring .= "'".$_SERVER['REMOTE_ADDR']."', ";
    $qstring .= "'".$email_id."', ";
    $qstring .= "'".hash('sha256',$email_id)."', ";
    $qstring .= "'".$password_value."', ";
    $qstring .= "'".$spendingpassword_value."', ";
    $qstring .= "'".$email_id."') ";

    $result2  = $mysqli->query($qstring);
    // echo $result2;

    // die;
    if ($result2)
    {
      //  $user2 = $result2->fetch_assoc();
      // var_dump($result2);
      // die;
      //  header("Location:login.php");
      $email_id = "";
      $password = "";
      $confirmpassword = "";
      $spendingpassword = "";
      $confirmspendingpassword = "";
      $error['emailError2'] = "Your Account has successfully register. Please Login to continue";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Register</title>
  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
</head>

<body class="bg-dark">
  <div class="container">
    <div class="card card-register mx-auto mt-5">
      <div class="card-header">Register an Account for Wallet</div>
      <div class="card-body">
        <form>
          <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input class="form-control" id="txtEmailID" name="txtEmailID" type="email" aria-describedby="emailHelp" placeholder="Enter Email" value="<?php echo $email_id;?>">
            <?php if(isset($error['emailError'])) { echo "<br/><small class=\"messageClass text-danger\">".$error['emailError']."</small>";  }?>
            <?php if(isset($error['emailError2'])) { echo "<br/><small class=\"messageClass2 text-success\">".$error['emailError2']."</small>";  }?>
          </div>
          <div class="form-group">
            <div class="form-row">
              <div class="col-md-6">
                <label for="exampleInputPassword1">Password</label>
                <input class="form-control" id="signuppassword" name="signuppassword" autocomplete="off" value="<?php echo $password;?>" type="password" placeholder="Password">
                <?php if(isset($error['passwordError'])) { echo "<br/><small class=\"messageClass text-danger\">".$error['passwordError']."</small>";  }?>
                <span id="result" style="float:left"></span>
              </div>
              <div class="col-md-6">
                <label for="exampleConfirmPassword">Confirm Password</label>
                <input class="form-control" id="confirmpassword" name="confirmpassword" type="password" placeholder="Confirm Password" value="<?php echo $confirmpassword;?>">
                <?php if(isset($error['confirmpasswordError'])) { echo "<br/><small class=\"messageClass text-danger\">".$error['confirmpasswordError']."</small>";  }?>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="form-row">
              <div class="col-md-6">
                <label for="exampleInputPassword1">Spanding Password</label>
                <input class="form-control" id="spendingpassword" name="spendingpassword" type="password" placeholder=" Spanding Password" value="<?php echo $spendingpassword;?>">
                <?php if(isset($error['spendingpasswordError'])) { echo "<br/><small class=\"messageClass text-danger\">".$error['spendingpasswordError']."</small>";  }?>
                  <span id="spendingresult" style="float:left"></span>
              </div>
              <div class="col-md-6">
                <label for="exampleConfirmPassword">Confirm Spanding Password</label>
                <input class="form-control"  id="confirmspendingpassword" name="confirmspendingpassword"  value="<?php echo $confirmspendingpassword;?>" type="password" placeholder="Confirm Spanding Password">
                <?php if(isset($error['confirmspendingpasswordError'])) { echo "<br/><small class=\"messageClass text-danger\">".$error['confirmspendingpasswordError']."</small>";  }?>
              </div>
            </div>
          </div>
          <div class="flex-center flex-end mtm mbl">
                      <input id="agreement_accept" name="agreement" ng-model="fields.acceptedAgreement"
                      required="" class="pull-right ng-pristine ng-untouched ng-empty ng-invalid ng-invalid-required" type="checkbox">
                      <label translate="ACCEPT_TOS" class="em-300 mbn mls right-align">I have read and agree to the <a>Terms of Service</a></label>
                    </div>
                    <div class="mtl flex-center flex-end">
                      
                      <span class="button Lockerblue ladda-button" id="btnLoading" style="display:none">
                        <span style="position:relative;">
                          <span class="loader"></span>
                        </span>
                        <span class="val1">Loading</span>
                      </span>
                    </div>
          <input type="submit" class="btn btn-primary btn-block" id="btnsignup" name="btnsignup" value="Register"/>
        </form>
        <div class="text-center">
          <a class="d-block small mt-3" href="login.php">Login Page</a>
          <a class="d-block small" href="forgot-password.php">Forgot Password?</a>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap core JavaScript-->
  <script type="text/javascript">

    function validateEmail(emailField) {
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return expr.test(emailField);
    }

function checkStrength(password)
{
    var strength = 0
    if (password.length < 6)
  {
        $('#result').removeClass()
        $('#result').addClass('short')
        return 'Weak'
    }
    if (password.length > 7) strength += 1
    // If password contains both lower and uppercase characters, increase strength value.
    if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1
    // If it has numbers and characters, increase strength value.
    if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) strength += 1
    // If it has one special character, increase strength value.
    if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
    // If it has two special characters, increase strength value.
    if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
    // Calculated strength value, we can return messages
    // If value is less than 2
    if (strength < 2)
  {
        $('#result').removeClass()
        $('#result').addClass('weak')
        return 'Regular'
    }
  else if (strength == 2)
  {
        $('#result').removeClass()
        $('#result').addClass('good')
        return 'Normal'
    }
  else
  {
        $('#result').removeClass()
        $('#result').addClass('strong')
        return 'Strong'
    }
}
</script>
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
</body>

</html>
