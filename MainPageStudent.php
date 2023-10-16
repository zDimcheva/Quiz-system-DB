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

}
</style>
</head>
<body>

    <?php
        session_start();	

	if(isset($_POST['take']) && !empty($_POST['number'])) {	
	    $_SESSION['questionNumber'] = 0;
	    $_SESSION['quizTaken'] = $_POST['number'];
	    $_SESSION['arrayAnswers'] = array();
            header("Location: http://localhost/scripts/TakeQuiz.php");
            exit; 
	}
	if(isset($_POST['results'])) {	
            header("Location: http://localhost/scripts/Result.php");
            exit; 
	}
    ?>

<form method="post">

<div class="container">
 <h1>Main Student Page</h1>
  <h2>Welcome to our main page. You can use this page to navigate through the available options.</h2>
    <p>Please choose one of the options below.</p>
      <hr>

         <label for="quiz"><b>If you want to do one of the available quizzes, please enter the number of the quiz you want to take click the button below.</b></label><br><br>
	 <input type="number" min="0" placeholder="Enter Quiz Number" name="number" id="number"><br><br>
	 <p>These are the available quizzes to take:</p>

    <?php
	$pdo = new pdo('mysql:host=localhost;dbname=quizdb', 'root', ''); 	
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	$sql = "SELECT * FROM quiz WHERE Availability = 'yes'";

	$stmt = $pdo->prepare($sql); 
	$stmt->execute();
	$results = $stmt->fetchAll();  
     
        echo "<table><tr><th>ID</th><th>Name</th><th>Author</th><th>Time Limit</th></tr>";       
	foreach ($results as $value) {
		echo "<tr><td>" . $value[0]. "</td><td>" . $value[1]. "</td><td>" . $value[2]. "</td><td>". $value[4]. "</td></tr>";
        }
        echo "</table>";

    ?>
	 <button type="submit" class="registerbtn" name="take">Take Quiz</button>
	 <hr>
         <label for="results"><b>If you want to see the results from the quizzes you have taken, please click the button below.</b></label>
	 <button type="submit" class="registerbtn" name="results">Results</button>

</div>
</form>

</body>
</html>