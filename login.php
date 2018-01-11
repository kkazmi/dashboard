<?php
include_once('common.php');
$allowed = array(".", "-", "_");
$email_id ="";
$password = "";

$error = array();
if(isset($_POST['btnlogin']))
{

	$email_id = $_POST['txtEmailID'];
	$password = $_POST['txtpassword'];

	if (empty($email_id))
	{
		$error['userError'] = "Please Provide valid email id";
	}
	if(empty($password))
	{
		$error['userError'] = "Please Provide valid passowrd";
	}
	elseif (!isEmail($email_id))
	{
		$error['userError'] = "Please Provide valid email id";
	}

	if(empty($error))
	{
		$email_id = $email_id;
		$password_value = hash('sha256',addslashes(strip_tags($password)));
		$qstring = "select coalesce(id,0) as id, coalesce(username,'') as username,
					coalesce(password,'') as password,
					coalesce(email,'') as email_id,
					coalesce(admin,'') as admin,
					coalesce(locked,0) as locked,
					coalesce(supportpin,'') as supportpin,
					coalesce(is_email_verify,0) as is_email_verify,
					coalesce(secret,'') as secret,
					coalesce(authused,0) as authused
					from users WHERE encrypt_username = '" . hash('sha256',$email_id) . "'";

		$result	= @mysqli_query($mysqli,$qstring);
		$user = mysqli_fetch_assoc($result);
		$secret = $user['secret'];
		if (($user) && ($user['password'] == $password_value) && ($user['locked'] == 0))
		{
			session_regenerate_id (true); 
			$_SESSION['user_id'] = $user['id'];
			$_SESSION['user_email_id'] = $user['email_id'];
			$_SESSION['user_session'] = $user['username'];
			$_SESSION['user_admin'] = $user['admin'];
			$_SESSION['user_supportpin'] = $user['supportpin'];
			$_SESSION['is_email_verify'] = $user['is_email_verify'];
			$_SESSION['secret'] = $user['secret'];
			$_SESSION['authused']=$user['authused'];

			if($user['authused']==1)
			{
				header("location:tfalogin.php");
			}else{
				header("Location:dashboard.php");
			}


			
			exit();

		}
		elseif (($user) && ($user['password'] == $password_value) && ($user['locked'] == 1))
		{
			$pin = $user['supportpin'];
			$error['userError'] = "Account is locked. Contact support for more information. $pin";
		}
		elseif (($user) && ($user['password'] == $password_value) && ($user['locked'] == 0) && ($user['authused'] == 1 && ($oneCode == $_POST['auth'])))
		{
			session_regenerate_id (true);

			$_SESSION['user_id'] = $user['id'];
			$_SESSION['user_email_id'] = $user['email_id'];
			$_SESSION['user_session'] = $user['username'];
			$_SESSION['user_admin'] = $user['admin'];
			$_SESSION['user_supportpin'] = $user['supportpin'];
			$_SESSION['is_email_verify'] = $user['is_email_verify'];
			$_SESSION['secret'] = $user['secret'];
			header("Location:dashboard.php");
			exit();
		}
		else
		{
			$error['userError'] = "Your E-mail id or password is incorrect.";
		}
	}
	else
	{
		$error['userError'] = "Your E-mail id or password is incorrect.";
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
  <title>Login |<?php echo $coin_fullname;?>(<?php echo $coin_short;?>) </title>
  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
</head>

<body class="bg-dark">
  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Login</div>
      <div class="card-body">
        <form>
          <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input class="form-control" id="exampleInputEmail1" type="email" aria-describedby="emailHelp" placeholder="Enter email">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input class="form-control" id="exampleInputPassword1" type="password" placeholder="Password">
          </div>
          <div class="form-group">
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox"> Remember Password</label>
            </div>
          </div>
          <a class="btn btn-primary btn-block" href="index.php">Login</a>
        </form>
        <div class="text-center">
          <a class="d-block small mt-3" href="register.php">Register an Account</a>
          <a class="d-block small" href="forgot-password.php">Forgot Password?</a>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
</body>

</html>
