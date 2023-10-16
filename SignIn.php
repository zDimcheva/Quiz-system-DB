<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
  font-family: Arial, Helvetica, sans-serif;
  background-color: white;
}

* {
  box-sizing: border-box;
}

/* Add padding to containers */
.container {
  padding: 16px;
  background-color: white;
}

/* Full-width input fields */
input[type=text], input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

input[type=text]:focus, input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

/* Overwrite default styles of hr */
hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}

/* Set a style for the submit button */
.registerbtn {
  background-color: #800080;
  color: white;
  padding: 16px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

.registerbtn:hover {
  opacity: 1;
}

/* Add a blue text color to links */
a {
  color: dodgerblue;
}

/* Set a grey background color and center the text of the "sign in" section */
.signin {
  background-color: #f1f1f1;
  text-align: center;
}
</style>
</head>
<body>
<?php
     if(isset($_POST['signIn'])) {
        session_start();

	$Email = $_POST['email'];
	$Password = $_POST['psw'];
	
	$pdo = new pdo('mysql:host=localhost;dbname=quizdb', 'root', ''); 
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); 
 
      if(strcmp($_POST['option'],"staff") == 0){

	$sql = "SELECT Password FROM staff WHERE Email = :Email"; 
	$stmt = $pdo->prepare($sql); 
	$stmt->execute([ 
		'Email' => $Email
	]);
	$results = $stmt->fetchAll();

	if(count($results) > 0 && password_verify($Password, $results[0][0])){
	    $_SESSION['staff'] = True;
	    $_SESSION['email'] = $Email;
            header("Location: http://localhost/scripts/MainPageStaff.php");
            exit;
	}
	else{
		echo '<script>alert("No such user exists. Try again!")</script>';
	}
      }
      else{
	$sql = "SELECT Password FROM student WHERE Email = :Email"; 
	$stmt = $pdo->prepare($sql); 
	$stmt->execute([ 
		'Email' => $Email
	]);
	$results = $stmt->fetchAll();

	if(count($results) > 0 && password_verify($Password, $results[0][0])){
	        $_SESSION['staff'] = False;
	        $_SESSION['email'] = $Email;
        	header("Location: http://localhost/scripts/MainPageStudent.php");
        	exit;		
	}
	else{
		echo '<script>alert("No such user exists. Try again!")</script>';
	}
       }
    }
?>

<form method="post">
  <div class="container">
    <h1>Sign In</h1>
    <p>Please fill in this form to sign into your account.</p>
    <hr>

    <label for="email"><b>Enter Email *</b></label><br>
    <input type="text" pattern="[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*" placeholder="Enter Email" name="email" id="email" required><br><br>

    <label for="psw"><b>Password (8 characters minimum) *</b></label>
    <input type="password" id="psw" name="psw" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>

    <label for="member"><b>Are you staff member or student? *</b></label><br>
    <label for="option">Choose an option:</label>
    <select id="option" name="option">
      <option value="staff">Staff</option>
      <option value="student">Student</option>
    </select><br><br>
    <hr>

    <button type="submit" class="registerbtn" name="signIn">Sign In</button>
  </div>
  
  <div class="container register">
    <p>Don't have an account? <a href="Index.php">Register</a>.</p>
  </div>
</form>

</body>
</html>