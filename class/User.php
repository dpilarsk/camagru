<?php

class User
{

	private $db;
	private $username;
	private $password;
	private $email;
	private $token;

	public function __construct($db)
	{
		$this->db = $db;
	}

	public function register($data)
	{
		$this->username = $data['username'];
		$this->password = hash(sha256, $data['password']);
		$this->email = $data['email'];
		$this->token = hash(sha256, microtime() + 0.3);
		$get_user = $this->db->prepare("SELECT * FROM users WHERE login = :username OR email = :email;");
		$get_user->execute(array(':username' => $this->username, ':email' => $this->email));
		$res = $get_user->fetchAll();
		if (count($res) != 0)
		{
			echo "Un utilisateur utilise deja ce nom ou cet email !";
			die();
		}
		else
		{
			$insert = $this->db->prepare("INSERT INTO users(login, password, email, status, token) VALUES (:username, :password, :email, 0, :token);");
			$insert->execute(array( ':username' => $this->username,
									':password' => $this->password,
									':email' => $this->email,
									':token' => $this->token));
			echo "Veuillez consulter votre email pour confirmer votre compte !";
		}
	}
}

?>