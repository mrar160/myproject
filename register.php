<?php
/**
*	Exercise : Create a new registration form (30 minutes)
*
*	Input :
*		1. Name
*		2. Email address
*		3. Userid
*		4. Password
*		5. Confirm password
*
*	Requirements :
*		1. Perform validation on user inputs
*			- all fields cannot be empty
*		2. Password must be min 8 characters
*		3. Password == Confirm password
*		4. Sticky form
*
*	Post-processing :
*		1. Success : redirect to login2.php
*		2. Fail : re-enter inputs
*
*/

// array to store error messages
$errormsg = array();

if($_SERVER['REQUEST_METHOD'] == "POST"){
	// retrieve all parameters
	$name = trim($_POST['name']);
	$email = trim($_POST['email']);
	$userid = trim($_POST['userid']);
	$password = trim($_POST['password']);
	$confirm = trim($_POST['confirm']);

	// check if password min 8 characters
	if(strlen($password) < 8){
		$errormsg[] = "Password must be minimum 8 characters.";
	}
	if($password != $confirm){
		$errormsg[] = "Password does not match.";
	}

	// if validation failed
	if(count($errormsg) > 0){
		// display error message(s)
		echo "<ul><font color='red'>";
		foreach($errormsg as $msg){
			echo "<li>$msg</li>";
		}
		echo "</ul></font>";
	}else{
		// insert new user to table
		try{
			$db = new PDO('mysql:host=localhost;dbname=mydb', 'root', '');
			$stmt = $db->prepare("INSERT INTO user (name, email, userid, password) VALUES (:name, :email, :userid, md5(:password))");
			$stmt->bindParam(':name', $name);
			$stmt->bindParam(':email', $email);
			$stmt->bindParam(':userid', $userid);
			$stmt->bindParam(':password', $password);
			$stmt->execute();

			// success : redirect to login2.php
			header("Location: login2.php");
		}catch(PDOException $e){
			echo "Couldn't connect to the database: ".$e->getMessage();
		}
		
	}
}
?>
<h1>Register New User</h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	<p>
		Name: <input type="text" name="name" required="required" 
			value="<?php if(isset($_POST['name'])) echo $_POST['name']; ?>" />
	</p>
	<p>
		Email: <input type="email" name="email" required="required" 
			value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" />
	</p>
	<p>
		User Id: <input type="text" name="userid" required="required" 
			value="<?php if(isset($_POST['userid'])) echo $_POST['userid']; ?>" />
	</p>
	<p>
		Password: <input type="password" name="password" required="required" />
	</p>
	<p>
		Confirm: <input type="password" name="confirm" required="required" />
	</p>
	<input type="submit" value="Register"/>
</form>