<?php
session_start();

// variable declaration
$username = "";
$email    = "";
$errors = array(); 
$_SESSION['success'] = "";

// connect to database
$db = mysqli_connect('localhost', 'root', '', 'tutortime');

// REGISTER USER
if (isset($_POST['reg_user'])) {
	// receive all input values from the form
	$name = mysqli_real_escape_string($db, $_POST['name']);
	$email = mysqli_real_escape_string($db, $_POST['email']);
	$password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
	$password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

	// form validation: ensure that the form is correctly filled
	if (empty($name)) { array_push($errors, "Your name is Required"); }
	if (empty($email)) { array_push($errors, "Email is Required"); }
	if (empty($password_1)) { array_push($errors, "Password is Required"); }

	if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
	}

	// register user if there are no errors in the form
	if (count($errors) == 0) {
		$password = md5($password_1);//encrypt the password before saving in the database
		$query = "INSERT INTO users (name, email, password) 
				  VALUES('$name', '$email', '$password')";
		mysqli_query($db, $query);

		$_SESSION['name'] = $name;
		$_SESSION['success'] = "You are now logged in";
		header('location: index.php');
	}
}

if (isset($_POST['login_user'])) {
	$name = mysqli_real_escape_string($db, $_POST['name']);
	$password = mysqli_real_escape_string($db, $_POST['password']);

	if (empty($name)) {
		array_push($errors, "Name is Required");
	}
	if (empty($password)) {
		array_push($errors, "Password is Required");
	}

	if (count($errors) == 0) {
		$password = md5($password);
		$query = "SELECT * FROM users WHERE name='$name' AND password='$password'";
		$results = mysqli_query($db, $query);

		if (mysqli_num_rows($results) == 1) {
			$_SESSION['name'] = $name;
			$_SESSION['success'] = "You are now logged in";
			header('location: index.php');
		}else {
			array_push($errors, "Wrong name and/or password. Please try again.");
		}
	}
}

?>