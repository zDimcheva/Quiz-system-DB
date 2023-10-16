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
	$sql = "SELECT QuestionNumber,Question,AnswerOne,AnswerTwo,AnswerThree,AnswerFour FROM questions WHERE QuizID = :QuizID";

	$stmt = $pdo->prepare($sql); 
	$stmt->execute(['QuizID' => $_SESSION['quizID']]);
	$results = $stmt->fetchAll();  

        echo "<h1>Delete quiz questions page</h1>";
        echo "<p>Please pick an option.</p>";
     
        echo "<table><tr><th>Number</th><th>Question</th><th>Answer 1</th><th>Answer 2</th><th>Answer 3</th><th>Answer 4</th></tr>";       
	foreach ($results as $value) {
		echo "<tr><td>" . $value[0]. "</td><td>" . $value[1]. "</td><td>" . $value[2]. "</td><td>" . $value[3]. "</td><td>" . $value[4]. "</td><td>" . $value[5] ."</td></tr>";
        }
        echo "</table>";

        if(isset($_POST['button3'])) {

	    $pdo = new pdo('mysql:host=localhost;dbname=quizdb', 'root', ''); 
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); 
 
            $sql = "DELETE FROM questions WHERE QuizID=:QuizID AND QuestionNumber=:QuestionNumber"; 

                $QuizID = $_SESSION['quizID'];
		$QuestionNumber = $_POST['questionNumber'];

                $stmt = $pdo->prepare($sql);
                $stmt->execute([ 
                        'QuizID' => $QuizID, 
                        'QuestionNumber' => $QuestionNumber,
                      ]);

	        $sql2 = "UPDATE questions SET QuestionNumber = QuestionNumber - 1 WHERE QuestionNumber > :QuestionNumber AND QuizID = :QuizID"; 

                $stmt1 = $pdo->prepare($sql2);
                $stmt1->execute([ 
                        'QuizID' => $QuizID,
                        'QuestionNumber' => $QuestionNumber
                      ]);

		header("Location: http://localhost/scripts/DeleteQuestions.php");
                exit;
        }
    ?>
      

<form method="post">

    <hr>
    <label for="questionNumber"><b>Enter number of question you want to delete</b></label>
    <input type="number" min="1" placeholder="Enter Question Number" name="questionNumber" id="questionNumber" required><br><br>

    <button type="submit" class="registerbtn" name="button3">Delete Question</button>

    <p>Click here if you want to update different quiz: <a href="UpdateQuiz.php">New Quiz Update</a>.</p>
    <p>Cancel updating of quiz by clicking here: <a href="MainPageStaff.php">Cancel</a>.</p>

</form>

</body>
</html>