<?php
	class Acocunt {

		public function __construct() {

		}

		public function register($em, $pw, $pw2) {
			$this->validateEmails($em);
			$this->validatePasswords($pw, $pw2);

			if (empty($this->errorArray) == true) {
				//Insert into DB
				return $this->insertUserDetails($em, $pw);
			} else {
				return false;
			}
		}

		private function validateEmails($em) {
			if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
				array_push($this->errorArray, "Email is not valid");
				return;
			}

			//TODO: Check that username has not already been used.
		}

		private function validatePasswords($pw, $pw2) {
		    if ($pw != $pw2) {
				array_push($this->errorArray, Constants::$passwordsDoNotMatch);
				return;
			}

			if (preg_match('/[^A-Za-z0-9]/', $pw)) {
				array_push($this->errorArray, Constants::$passwordMustBeAlphaNumeric);
				return;
			}

			if(strlen($pw) > 30 || strlen($pw) < 5) {
				array_push($this->errorArray, Constants::$passwordCharacters);
				return;
			}
		}

	}
?>