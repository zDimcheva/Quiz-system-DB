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

        if(isset($_POST['button2'])) {

            $pdo = new pdo('mysql:host=localhost;dbname=quizdb', 'root', ''); 
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); 
 
	    $sql2 = "SELECT ID FROM staff WHERE Email = :Email";
            $stmt1 = $pdo->prepare($sql2); 
            $stmt1->execute(['Email' => $_SESSION['email']]);
	    $results = $stmt1->fetchAll();

            $ID = $results[0][0]; 

            $sql = "INSERT INTO quiz (QuizName, StaffID, Availability, TimeLimit) VALUES (:QuizName, :StaffID, :Availability, :TimeLimit)"; 

                $QuizName = $_POST['quiz']; 
                $Availability = $_POST['available']; 
                $TimeLimit = $_POST['timeLimit']; 

		if (empty($TimeLimit)) {
		  $TimeLimit = NULL;
		}

                $stmt = $pdo->prepare($sql); 
                $stmt->execute([ 
                        'QuizName' => $QuizName, 
                        'StaffID' => $ID,
                        'Availability' => $Availability, 
                        'TimeLimit' => $TimeLimit
                      ]); 
 
		$sql2 = "SELECT ID FROM quiz WHERE QuizName = :quiz";

        	$stmt1 = $pdo->prepare($sql2); 
        	$stmt1->execute(['quiz' => $_POST['quiz']]);
        	$result = $stmt1->fetch();
		$_SESSION['quiz'] = $result[0][0];
		$_SESSION['questionNumber'] = 0;

            header("Location: http://localhost/scripts/CreateQuestion.php");
            exit; 
        }
    ?>
      

<form method="post">
  <div class="container">
    <h1>Create quiz page</h1>
    <p>Please fill in this form to create the quiz.</p>
    <hr>

    <label for="quiz"><b>Enter quiz name *</b></label>
    <input type="text" placeholder="Enter Quiz Name" name="quiz" id="quiz" required>

    <label for="quiz"><b>Is the quiz available to take?</b></label><br>
    <label for="available">Choose an option:</label>
    <select id="available" name="available">
      <option value="yes">Yes</option>
      <option value="no">No</option>
    </select><br><br>

    <label for="timeLimit"><b>Enter time limit</b></label>
    <input type="number" min="0" placeholder="Enter Time Limit" name="timeLimit" id="timeLimit">
    <hr>

    <button type="submit" class="registerbtn" name="button2">Create quiz</button>
  </div>

  <div class="container cancel">
    <p>Cancel creation of quiz and go back to main page by clicking here: <a href="MainPageStaff.php">Cancel</a>.</p>
  </div>

</form>

</body>
</html>