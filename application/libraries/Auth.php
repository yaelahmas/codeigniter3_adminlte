<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth
{
	/**
	 * CodeIgniter Instace
	 *
	 * @var object
	 */
	protected $ci;

	public function __construct()
	{
		$this->ci = &get_instance();
		$this->ci->load->model('User_model', 'user');
		$this->ci->load->model('UserToken_model', 'user_token');
		// $this->init();
	}

	/**
	 * Generate a random selector/validator couple
	 * This function from ion_auth by @benedmunds
	 *
	 * This is a user code
	 *
	 * @param $selector_size int	size of the selector token
	 * @param $validator_size int	size of the validator token
	 *
	 * @return object
	 * 			->selector			simple token to retrieve the user (to store in DB)
	 * 			->validator_hashed	token (hashed) to validate the user (to store in DB)
	 * 			->user_code			code to be used user-side (in cookie or URL)
	 */
	public function generateSelectorValidatorCouple(
		$selector_size = 40,
		$validator_size = 128
	) {
		// The selector is a simple token to retrieve the user
		$selector = $this->randomToken($selector_size);
		// The validator will strictly validate the user and should be more complex
		$validator = $this->randomToken($validator_size);
		// The validator is hashed for storing in DB (avoid session stealing in case of DB leaked)
		$validator_hashed = $this->hashPassword($validator);
		// The code to be used user-side
		$user_code = "$selector.$validator";
		return (object) [
			'selector' => $selector,
			'validator_hashed' => $validator_hashed,
			'user_code' => $user_code,
		];
	}

	/** Generate a random token
	 * This function from ion_auth by @benedmunds
	 *
	 * Inspired from http://php.net/manual/en/function.random-bytes.php#118932
	 *
	 * @param int $result_length
	 * @return string
	 */
	protected function randomToken(
		$result_length = 32
	) {
		if (!isset($result_length) || intval($result_length) <= 8) {
			$result_length = 32;
		}
		// Try random_bytes: PHP 7
		if (function_exists('random_bytes')) {
			return bin2hex(random_bytes($result_length / 2));
		}
		// Try mcrypt
		if (function_exists('mcrypt_create_iv')) {
			return bin2hex(mcrypt_create_iv($result_length / 2, MCRYPT_DEV_URANDOM));
		}
		// Try openssl
		if (function_exists('openssl_random_pseudo_bytes')) {
			return bin2hex(openssl_random_pseudo_bytes($result_length / 2));
		}
		// No luck!
		return FALSE;
	}

	/**
	 * Hashes the password to be stored in the database.
	 * This function from ion_auth by @benedmunds
	 *
	 * @param string $password
	 *
	 * @return false|string
	 * @author Mathew
	 */
	public function hashPassword(
		$password
	) {
		// Check for empty password, or password containing null char, or password above limit
		// Null char may pose issue: http://php.net/manual/en/function.password-hash.php#118603
		// Long password may pose DOS issue (note: strlen gives size in bytes and not in multibyte symbol)
		if (
			empty($password) || strpos($password, "\0") !== FALSE ||
			strlen($password) > MAX_PASSWORD_SIZE_BYTES
		) {
			return FALSE;
		}
		$algo = PASSWORD_BCRYPT;
		//$params = BCRYPT_COST; // default 60 characters, define 12 characters
		if ($algo !== FALSE) {
			return password_hash(
				$password,
				$algo
				/**, $params*/
			);
		}
		return FALSE;
	}

	/**
	 * This function takes a password and validates it
	 * against an entry in the users table.
	 *
	 * @param string	$password
	 * @param string	$hash_password_db
	 * @param string	$identity			optional @deprecated only for BC SHA1
	 *
	 * @return bool
	 * @author Mathew
	 */
	public function verifyPassword(
		$password,
		$hash_password_db
	) {
		// Check for empty id or password, or password containing null char, or password above limit
		// Null char may pose issue: http://php.net/manual/en/function.password-hash.php#118603
		// Long password may pose DOS issue (note: strlen gives size in bytes and not in multibyte symbol)
		if (
			empty($password) || empty($hash_password_db) || strpos($password, "\0") !== FALSE
			|| strlen($password) > MAX_PASSWORD_SIZE_BYTES
		) {
			return FALSE;
		}
		// password_hash always starts with $
		if (strpos($hash_password_db, '$') === 0) {
			return password_verify($password, $hash_password_db);
		}

		return FALSE;
	}


	/**
	 * is_max_login_attempts_exceeded
	 * This function from ion_auth by @benedmunds
	 *
	 * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
	 *
	 * @param string      $identity   user's identity
	 * @param string|null $ip_address IP address
	 *                                Only used if track_login_ip_address is set to TRUE.
	 *                                If NULL (default value), the current IP address is used.
	 *                                Use get_last_attempt_ip($identity) to retrieve a user's last IP
	 *
	 * @return boolean
	 */
	// public function isMaxLoginAttemptsExceeded(
	// 	$identity,
	// 	$ip_address = NULL
	// ) {
	// 	if (TRACK_LOGIN_ATTEMPTS) {
	// 		$max_attempts = MAXIMUM_LOGIN_ATTEMPTS;
	// 		if ($max_attempts > 0) {
	// 			$attempts = $this->ci->user->getAttemptsNum($identity, $ip_address);
	// 			return $attempts >= $max_attempts;
	// 		}
	// 	}
	// 	return FALSE;
	// }

	/**
	 * Retrieve remember cookie info
	 * This function from ion_auth by @benedmunds
	 *
	 * @param $user_code string	A user code of the form "selector.validator"
	 *
	 * @return object
	 * 			->selector		simple token to retrieve the user in DB
	 * 			->validator		token to validate the user (check against hashed value in DB)
	 */
	public function retrieveSelectorValidatorCouple($user_code)
	{
		if ($user_code) {
			$tokens = explode('.', $user_code);
			if (count($tokens) === 2) {
				return (object) [
					'selector' => $tokens[0],
					'validator' => $tokens[1]
				];
			}
		}
		return FALSE;
	}
}
