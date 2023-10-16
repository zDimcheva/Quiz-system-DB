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
  padding: 15px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;
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
	
	$score = 0;
	$QuizID = $_SESSION['quizTaken'];
	$pdo = new pdo('mysql:host=localhost;dbname=quizdb', 'root', ''); 
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); 
 
	$sql = "SELECT QuestionNumber,Question,AnswerOne,AnswerTwo,AnswerThree,AnswerFour FROM questions WHERE QuizID = :QuizID"; 

	$stmt = $pdo->prepare($sql); 
	$stmt->execute([ 
		'QuizID' => $QuizID
	]);

	$questions = $stmt->fetchAll();
	$QuestionNumber = $_SESSION['questionNumber'];
	$isLast = False;

      	echo "<h1>Quiz Page</h1>";
      	echo "<h4>You are taking quiz № $QuizID</h4>";

	if(isset($_POST['buttonNext'])) {
		if(isset($_POST['option'])){
			array_push($_SESSION['arrayAnswers'], $_POST['option']);
		}	
		$QuestionNumber++;

		if($QuestionNumber >= count($questions) - 1){
		        $isLast = True;
		}
	
		if($QuestionNumber < count($questions)){
			$_SESSION['questionNumber'] = $QuestionNumber;	
		}
		else{
			$QuestionNumber--;
			foreach ($_SESSION['arrayAnswers'] as $value) { 
  				if(strcmp($value, "0") == 0){
					$score = $score + 1;
				}
			}
 
			$sql = "SELECT ID FROM student WHERE Email = :Email";			
			$stmt = $pdo->prepare($sql); 
			$stmt->execute([ 
				'Email' => $_SESSION['email']
			]);
			$results = $stmt->fetchAll();

			$StudentID = $results[0][0];
			$_SESSION['$studentID'] = $StudentID;
			$_SESSION['score'] = $score;

			if(!$_SESSION['staff']){
				$sql2 = "INSERT INTO takesstudent (StudentID,QuizID,Score) VALUES (:StudentID,:QuizID,:Score)";			
				$stmt2 = $pdo->prepare($sql2); 
				$stmt2->execute([ 
					'StudentID' => $StudentID,
					'QuizID' => $QuizID,
					'Score' => (($score * 100.0) / count($questions))
				]);
			}
			else{
				$sql2 = "INSERT INTO takesstaff (StaffEmail,QuizID,Score) VALUES (:StaffEmail,:QuizID,:Score)";			
				$stmt2 = $pdo->prepare($sql2); 
				$stmt2->execute([ 
					'StaffEmail' => $_SESSION['email'],
					'QuizID' => $QuizID,
					'Score' => (($score * 100.0) / count($questions))
				]);
			}

			header("Location: http://localhost/scripts/Result.php");
            		exit;
		}
	}

	$position = rand(0,3);
    ?>

<form method="post">
  <p><?php echo $questions[$QuestionNumber][1]; ?></p>

  <input type="radio" id="option1" name="option" value="<?php echo (($position) % 4) ;?>">
  <label for="option1">
	<?php 
		echo $questions[$QuestionNumber][2 + ($position) % 4];
        ?>
  </label><br>

  <input type="radio" id="option2" name="option" value="<?php echo (($position + 1) % 4) ;?>">
  <label for="option2">
	<?php 
		echo $questions[$QuestionNumber][2 + ($position + 1) % 4] 
	;?>
  </label><br>

  <input type="radio" id="option3" name="option" value="<?php echo (($position + 2) % 4) ;?>">
  <label for="option3">
	<?php 
		echo $questions[$QuestionNumber][2 + ($position + 2) % 4] 
	;?>
  </label><br>

  <input type="radio" id="option4" name="option" value="<?php echo (($position + 3) % 4) ;?>">
  <label for="option4">
	<?php
		echo $questions[$QuestionNumber][2 + ($position + 3) % 4] 
	;?>
  </label><br><br>

  <div>
    <button class="registerbtn" name="buttonNext">
	<?php
		if($isLast){echo "Submit";}
		else{echo "Next";}
	;?>
    </button>
  </div>
</form>

</body>
</html>