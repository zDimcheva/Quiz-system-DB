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

        echo "<h1>Update quiz questions page</h1>";
        echo "<p>Please pick an option.</p>";
     
        echo "<table><tr><th>Number</th><th>Question</th><th>Answer 1</th><th>Answer 2</th><th>Answer 3</th><th>Answer 4</th></tr>";       
	foreach ($results as $value) {
		echo "<tr><td>" . $value[0]. "</td><td>" . $value[1]. "</td><td>" . $value[2]. "</td><td>" . $value[3]. "</td><td>" . $value[4]. "</td><td>" . $value[5] ."</td></tr>";
        }
        echo "</table>";


        if(isset($_POST['button1'])) { 
	    $pdo = new pdo('mysql:host=localhost;dbname=quizdb', 'root', ''); 
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); 
 
            $sql = "INSERT INTO questions (QuizID, QuestionNumber, Question, AnswerOne, AnswerTwo, AnswerThree, AnswerFour) VALUES (:QuizID, :QuestionNumber, :Question, :AnswerOne, :AnswerTwo, :AnswerThree, :AnswerFour)"; 

		$_SESSION['questionNumber'] = count($results);
		$QuestionNumber = $_SESSION['questionNumber'] + 1; 

                $QuizID = $_SESSION['quizID']; 
                $Question = $_POST['question']; 
                $AnswerOne = $_POST['answer'];
                $AnswerTwo = $_POST['answerTwo'];
                $AnswerThree = $_POST['answerThree'];
                $AnswerFour = $_POST['answerFour'];
 
                $stmt = $pdo->prepare($sql);
                $stmt->execute([ 
                        'QuizID' => $QuizID, 
                        'QuestionNumber' => $QuestionNumber,
                        'Question' => $Question,
                        'AnswerOne' => $AnswerOne,
                        'AnswerTwo' => $AnswerTwo,
                        'AnswerThree' => $AnswerThree,
                        'AnswerFour' => $AnswerFour
                      ]);	

            header("Location: http://localhost/scripts/UpdateQuestion.php");
            exit;     
        }

        if(isset($_POST['button2'])) {	   
	    $pdo = new pdo('mysql:host=localhost;dbname=quizdb', 'root', ''); 
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); 

		$QuestionNumber = $_POST['questionNumber']; 
                $Question = $_POST['question']; 
                $AnswerOne = $_POST['answer'];
                $AnswerTwo = $_POST['answerTwo'];
                $AnswerThree = $_POST['answerThree'];
                $AnswerFour = $_POST['answerFour'];
 
		if (!empty($Question)) {
		  $sql = "UPDATE questions SET Question = :Question WHERE QuestionNumber = :QuestionNumber";
                  $stmt = $pdo->prepare($sql);
                  $stmt->execute([ 
                        'Question' => $Question,
                        'QuestionNumber' => $QuestionNumber
                      ]);
		}
		if (!empty($AnswerOne)) {
		  $sql = "UPDATE questions SET AnswerOne = :AnswerOne WHERE QuestionNumber = :QuestionNumber";
                  $stmt = $pdo->prepare($sql);
                  $stmt->execute([ 
                        'AnswerOne' => $AnswerOne,
                        'QuestionNumber' => $QuestionNumber
                      ]);
		}
		if (!empty($AnswerOne)) {
		  $sql = "UPDATE questions SET AnswerTwo = :AnswerTwo WHERE QuestionNumber = :QuestionNumber";
                  $stmt = $pdo->prepare($sql);
                  $stmt->execute([ 
                        'AnswerTwo' => $AnswerTwo,
                        'QuestionNumber' => $QuestionNumber
                      ]);
		}
		if (!empty($AnswerOne)) {
		  $sql = "UPDATE questions SET AnswerThree =:AnswerThree WHERE QuestionNumber = :QuestionNumber";
                  $stmt = $pdo->prepare($sql);
                  $stmt->execute([ 
                        'AnswerThree' => $AnswerThree,
                        'QuestionNumber' => $QuestionNumber
                      ]);
		if (!empty($AnswerOne)) {
		}
		  $sql = "UPDATE questions SET AnswerFour = :AnswerFour WHERE QuestionNumber = :QuestionNumber";
                  $stmt = $pdo->prepare($sql);
                  $stmt->execute([ 
                        'AnswerFour' => $AnswerFour,
                        'QuestionNumber' => $QuestionNumber
                      ]);
		}	

            header("Location: http://localhost/scripts/UpdateQuestion.php");
            exit;
        }

    ?>
      

<form method="post">

    <hr>
    <label for="questionNumber"><b>Enter number of question you want to update</b></label>
    <input type="number" min="1" placeholder="Enter Question Number" name="questionNumber" id="questionNumber"><br><br>

    <label for="question"><b>Enter question</b></label>
    <input type="text" placeholder="Enter Question" name="question" id="question" required>

    <label for="answer"><b>Enter correct answer</b></label>
    <input type="text" placeholder="Enter Answer" name="answer" id="answer" required>

    <label for="answerTwo"><b>Enter second answer option</b></label>
    <input type="text" placeholder="Enter Second Answer Option" name="answerTwo" id="answerTwo" required>

    <label for="answerThree"><b>Enter third answer option</b></label>
    <input type="text" placeholder="Enter Third Answer Option" name="answerThree" id="answerThree" required>

    <label for="answerFour"><b>Enter forth answer option</b></label>
    <input type="text" placeholder="Enter Forth Answer Option" name="answerFour" id="answerFour" required>

    <button type="submit" class="registerbtn" name="button1">Add Question</button>
    <button type="submit" class="registerbtn" name="button2">Update Question</button>

    <p>Click here if you want to update different quiz: <a href="UpdateQuiz.php">New Quiz Update</a>.</p>
    <p>Cancel updating of quiz by clicking here: <a href="MainPageStaff.php">Cancel</a>.</p>


</form>

</body>
</html>