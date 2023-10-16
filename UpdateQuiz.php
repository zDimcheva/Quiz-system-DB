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
	$sql = "SELECT * FROM quiz";

	$stmt = $pdo->prepare($sql); 
	$stmt->execute();
	$results = $stmt->fetchAll();  

        echo "<h1>Update quiz page</h1>";
        echo "<p>Please pick a quiz to update.</p>";
     
        echo "<table><tr><th>ID</th><th>Name</th><th>Author</th><th>Availability</th><th>Time Limit</th></tr>";       
	foreach ($results as $value) {
		echo "<tr><td>" . $value[0]. "</td><td>" . $value[1]. "</td><td>" . $value[2]. "</td><td>" . $value[3]. "</td><td>" . $value[4]. "</td></tr>";
        }
        echo "</table>";

        if(isset($_POST['button1'])) {
            $_SESSION['quizID'] = $_POST['id'];

            header("Location: http://localhost/scripts/UpdateQuestion.php");
            exit; 
        }

        if(isset($_POST['button2'])) {
            $_SESSION['quizID'] = $_POST['id'];

            header("Location: http://localhost/scripts/DeleteQuestions.php");
            exit;  
        }

        if(isset($_POST['button3'])) {
	    $pdo = new pdo('mysql:host=localhost;dbname=quizdb', 'root', ''); 	 
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	
	    $ID = $_POST['id'];

	    $sql = "DELETE FROM questions WHERE QuizID = :ID";
            $stmt = $pdo->prepare($sql);  
            $stmt->execute(['ID' => $ID]);

	    $sql2 = "DELETE FROM quiz WHERE ID = :ID";
            $stmt2 = $pdo->prepare($sql2);  
            $stmt2->execute(['ID' => $ID]);

            header("Location: http://localhost/scripts/UpdateQuiz.php");
            exit; 
        }
        if(isset($_POST['button4'])) {
 	    $pdo = new pdo('mysql:host=localhost;dbname=quizdb', 'root', ''); 	 
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

	    $sql = "UPDATE quiz SET QuizName = :QuizName WHERE ID = :ID";
	    
	    if(!empty($_POST['name'])){
	        $stmt1 = $pdo->prepare($sql); 
                $stmt1->execute([ 
    			'ID' => $_POST['id'],
			'QuizName' => $_POST['name']
                      ]);
	    }

            header("Location: http://localhost/scripts/UpdateQuiz.php");
            exit;  
        }

        if(isset($_POST['button5'])) { 
	    $pdo = new pdo('mysql:host=localhost;dbname=quizdb', 'root', ''); 	 
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	    $sql = "UPDATE quiz SET Availability = :Availability WHERE ID = :ID";
	    
	    $stmt1 = $pdo->prepare($sql); 
                $stmt1->execute([ 
                        'Availability' => $_POST['available'], 
    			'ID' => $_POST['id']
                      ]);

            header("Location: http://localhost/scripts/UpdateQuiz.php");
            exit; 	    
        }

        if(isset($_POST['button6'])) {
	    $pdo = new pdo('mysql:host=localhost;dbname=quizdb', 'root', ''); 	 
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

	    $sql = "UPDATE quiz SET TimeLimit = :TimeLimit WHERE ID = :ID";
	    
	    $stmt1 = $pdo->prepare($sql); 
                $stmt1->execute([ 
                        'TimeLimit' => $_POST['time'], 
    			'ID' => $_POST['id']
                      ]);

            header("Location: http://localhost/scripts/UpdateQuiz.php");
            exit; 	   
        }
    ?>
      

<form method="post">

    <hr>
    <label for="id"><b>Enter id of quiz you want to update or delete *</b></label>
    <input type="number" min="1" placeholder="Enter Quiz ID" name="id" id="id" required><br><br>

    <button type="submit" class="registerbtn" name="button1">Update Quiz Questions</button>
    <button type="submit" class="registerbtn" name="button2">Delete Quiz Questions</button>
    <button type="submit" class="registerbtn" name="button3">Delete Quiz</button>
    <hr>
    <label for="id"><b>Enter new name of quiz if wanted</b></label>
    <input type="text" placeholder="Enter Quiz Name" name="name" id="name"><br>

    <button type="submit" class="registerbtn" name="button4">Update Quiz Name</button>

    <hr>
    <label for="quiz"><b>Is the quiz available to take?</b></label><br>
    <label for="available">Choose an option:</label>
    <select id="available" name="available">
      <option value="yes">Yes</option>
      <option value="no">No</option>
    </select><br><br>
    <button type="submit" class="registerbtn" name="button5">Update Quiz Availability</button>
    <hr>

    <label for="time"><b>Enter quiz time limit</b></label>
    <input type="number" min="1" placeholder="Enter Quiz Time" name="time" id="time"><br><br>
    <hr>

    <button type="submit" class="registerbtn" name="button6">Update Quiz Time</button>
    <hr>

  <div class="container cancel">
    <p>Cancel updating of quiz and return to main page by clicking here: <a href="MainPageStaff.php">Cancel</a>.</p>
  </div>

</form>

</body>
</html>