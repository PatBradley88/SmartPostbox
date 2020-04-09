<?php
	class Account {

		private $con;
		private $errorArray;

		public function __construct($con) {
			$this->con = $con;
			$this->errorArray = array();
		}

		public function login($em, $pw) {
			$pw = md5($pw);
			$query = mysqli_query($this->con, "SELECT * FROM users WHERE email='$em' AND password='$pw'");
			if(mysqli_num_rows($query) == 1) {
				return true;
			} else {
				array_push($this->errorArray, "Login failed");
				return false;
			}
		}

		public function register($em, $pw, $pw2) {
			$this->validateEmails($em);
			$this->validatePassword($pw, $pw2);

			if (empty($this->errorArray) == true) {
				//Insert into DB
				return $this->insertUserDetails($em, $pw);
			} else {
				return false;
			}
		}

		public function getError($error) {
			if(!in_array($error, $this->errorArray)) {
				$error = "";
			}
			return "<span class='errorMessage'>$error</span>";
		}

		private function insertUserDetails($em, $pw) {
			$encryptedPw = md5($pw);
			$date = date("Y-m-d");

			$result = mysqli_query($this->con, "INSERT INTO users VALUES ('', '$em', '$encryptedPw', '$date')");

			return $result;
		}

		private function validateEmails($em) {
			if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
				array_push($this->errorArray, "Email is invalid");
				return;
			}

			$checkEmailQuery = mysqli_query($this->con, "SELECT email FROM users WHERE email='$em'");
			if(mysqli_num_rows($checkEmailQuery) != 0) {
				array_push($this->errorArray, "This email is aleady in use.");
				return;
			}
		}

		private function validatePassword($pw, $pw2) {
		    if ($pw != $pw2) {
				array_push($this->errorArray, "Your passwords do not match");
				return;
			}

			if (preg_match('/[^A-Za-z0-9]/', $pw)) {
				array_push($this->errorArray, "Password can only contain numbers and letters");
				return;
			}

			if(strlen($pw) > 30 || strlen($pw) < 5) {
				array_push($this->errorArray, "Passwords must be between 5 and 30 characters");
				return;
			}
		}

	}
?>