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
	createDatabase();
        session_start();
	
        if(isset($_POST['button1'])) {

            $pdo = new pdo('mysql:host=localhost;dbname=quizdb', 'root', ''); 
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	    $sql = "(SELECT Email FROM staff WHERE Email = :Email) UNION (SELECT Email FROM student WHERE Email = :Email)";

	    $flag = True;
	    $Name = $_POST['name'];
	    $Email = $_POST['email'];
	    $Password = $_POST['psw'];
	    $Option = $_POST['option'];

	    $stmt = $pdo->prepare($sql); 
            $stmt->execute([ 
                'Email' => $Email
            ]);

            $results = $stmt->fetchAll();  

            if(count($results) > 0){
		echo '<script>alert("Email already in use. Try a different one.")</script>';
	    }
            else{
	    	if($Option == "staff"){
	    	    $sql2 = "INSERT INTO staff (Email, Name, Password) VALUES (:Email, :Name, :Password)";
	    
	    	    $stmt1 = $pdo->prepare($sql2); 
            	    $stmt1->execute([ 
                        'Email' => $Email,
                        'Name' => $Name,
                        'Password' => password_hash($Password, PASSWORD_DEFAULT)
                    ]);
	    	}
	    	if($Option == "student"){
	    	    $sql2 = "INSERT INTO student (Email, Name, Password) VALUES (:Email, :Name, :Password)";
	    
	    	    $stmt1 = $pdo->prepare($sql2); 
            	    $stmt1->execute([ 
                        'Email' => $Email,
                        'Name' => $Name,
                        'Password' => password_hash($Password, PASSWORD_DEFAULT)
                    ]);
	    	}
            	header("Location: http://localhost/scripts/SignIn.php");
            	exit;
	    }
        }
    ?>

<form method="post">
  <div>
    <h1>Register</h1>
    <p>Please fill in this form to create an account.</p>
    <hr>

    <label for="name"><b>Enter Your Full Name *</b></label>
    <input type="text" pattern="([a-zA-Z]+\s){1,}([a-zA-Z]+)" placeholder="Enter Your Name" name="name" id="name" required>

    <label for="email"><b>Enter Email *</b></label><br><br>
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

    <button type="submit" class="registerbtn" name="button1">Register</button>
  </div>
  
  <div class="container signin">
    <p>Already have an account? <a href="SignIn.php">Sign in</a>.</p>
  </div>
</form>

</body>
</html>

<?php

function createDatabase() 
{ 
    $sql = "CREATE DATABASE IF NOT EXISTS quizdb"; 
 
    $pdo = new pdo('mysql:host=localhost', 'root', ''); 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); 
 
    // query if NO variables are to be used.  
    $pdo->query($sql); 

    $pdo2 = new pdo('mysql:host=localhost;dbname=quizdb', 'root', ''); 	
    $pdo2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql2 = "CREATE TABLE IF NOT EXISTS student (
    		ID int AUTO_INCREMENT NOT NULL,
    		Name varchar(255) NOT NULL,
    		Email varchar(255) NOT NULL UNIQUE,
    		Password  varchar(255) NOT NULL,
    		PRIMARY KEY(ID)
	     );

CREATE TABLE IF NOT EXISTS staff (
    Email varchar(255) NOT NULL UNIQUE,
    Name varchar(255) NOT NULL,
    Password  varchar(255) NOT NULL,
    ID int AUTO_INCREMENT,
    PRIMARY KEY(ID)
);

CREATE TABLE IF NOT EXISTS quiz (
    ID int AUTO_INCREMENT NOT NULL,
    QuizName varchar(255) NOT NULL,
    StaffID int NOT NULL,
    Availability varchar(255) NOT NULL,
    TimeLimit int,
    CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`StaffID`) REFERENCES `staff`(`ID`) ON DELETE RESTRICT ON UPDATE CASCADE,
    PRIMARY KEY(ID)
);



CREATE TABLE IF NOT EXISTS takesstudent (
    TakesID int AUTO_INCREMENT,
    StudentID int NOT NULL,
    QuizID int NOT NULL,
    Score int NOT NULL,
    CONSTRAINT `student_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `student`(`ID`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `quizi_ibfk_1` FOREIGN KEY (`QuizID`) REFERENCES `quiz`(`ID`) ON DELETE RESTRICT ON UPDATE CASCADE,
    PRIMARY KEY(TakesID)
);

CREATE TABLE IF NOT EXISTS takesstaff (
    TakesID int AUTO_INCREMENT,
    StaffEmail varchar(255) NOT NULL,
    QuizID int NOT NULL,
    Score int NOT NULL,
    CONSTRAINT `staffid_ibfk_3` FOREIGN KEY (`StaffEmail`) REFERENCES `staff`(`Email`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `quizid_ibfk_3` FOREIGN KEY (`QuizID`) REFERENCES `quiz`(`ID`) ON DELETE RESTRICT ON UPDATE CASCADE,
    PRIMARY KEY(TakesID)
);

CREATE TABLE IF NOT EXISTS questions (
    QuizID int,
    QuestionNumber int,
    Question varchar(255) NOT NULL,
    AnswerOne varchar(255) NOT NULL,
    AnswerTwo varchar(255) NOT NULL,
    AnswerThree varchar(255) NOT NULL,    
    AnswerFour varchar(255) NOT NULL,
    CONSTRAINT `quiz_ibfk_2` FOREIGN KEY (`QuizID`) REFERENCES `quiz`(`ID`) ON DELETE RESTRICT ON UPDATE CASCADE,
    PRIMARY KEY (QuizID, QuestionNumber) 
);

CREATE TABLE IF NOT EXISTS delete_quiz_trigger (
    StaffID int NOT NULL,
    QuizID int,
    DateCurrent varchar(255) NOT NULL,
    TimeCurrent varchar(255) NOT NULL,
    PRIMARY KEY(QuizID)
);";

    // query if no tables are created  
    $stmt = $pdo2->prepare($sql2); 
    $stmt->execute(); 
}
?>