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
	session_start();
	
	$pdo = new pdo('mysql:host=localhost;dbname=quizdb', 'root', ''); 	
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

	if($_SESSION['staff']){

		$sql = "SELECT * FROM takesstaff WHERE StaffEmail = :StaffEmail";

		$stmt = $pdo->prepare($sql); 
		$stmt->execute(['StaffEmail' => $_SESSION['email']]);
		$results = $stmt->fetchAll();  

        	echo "<h1>Result quiz page</h1>";
        	echo "<h3>You can see all your results here.</h3>";


        	echo "<p>First column shows the Quiz ID, second shows the score.</p>";
        	echo "<table><tr><th>Quiz ID</th><th>Score</th></tr>";       
		foreach ($results as $value) {
			echo "<tr><td>" . $value[2]. "</td><td>" . $value[3]. "</td><td>";
        	}
        	echo "</table>";

		if(isset($_POST['button1'])) {	
            		header("Location: http://localhost/scripts/MainPageStaff.php");
            		exit; 
		}
	}
	else{
		$sql = "SELECT * FROM takesstudent WHERE StudentID = :StudentID";

		$stmt = $pdo->prepare($sql); 
		$stmt->execute(['StudentID' => $_SESSION['$studentID']]);
		$results = $stmt->fetchAll();  

        	echo "<h1>Result quiz page</h1>";
        	echo "<h3>You can see all your results here.</h3>";


        	echo "<p>First column shows the Quiz ID, second shows the score.</p>";
        	echo "<table><tr><th>Quiz ID</th><th>Score</th></tr>";       
		foreach ($results as $value) {
			echo "<tr><td>" . $value[2]. "</td><td>" . $value[3]. "</td><td>";
        	}
        	echo "</table>";

		if(isset($_POST['button1'])) {	
            		header("Location: http://localhost/scripts/MainPageStudent.php");
            		exit; 
		}
	}
    ?>
      

<form method="post">
  <div class="container cancel">
    <button type="submit" class="registerbtn" name="button1">Main Page</button>
  </div>

</form>

</body>
</html>