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
		$get_user->closeCursor();
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
			mail($this->email,
				'Confirmation du compte',
				"Afin de confirmer votre compte Camagru, merci de cliquer ici:\n\n
							http://localhost:8080/confirm.php?login=$this->username&token=$this->token");
			echo "Veuillez consulter votre email pour confirmer votre compte !";
		}
	}

	public function checkToken($login, $token)
	{
		$getUser = $this->db->prepare("SELECT * FROM users WHERE login = :login AND token = :token AND status = 0;");
		$getUser->execute(array(':login' => $login,
								':token' => $token));
		$res = $getUser->fetchAll();
		$getUser->closeCursor();
		if (count($res) == 0)
		{
			echo "Votre compte est soit deja valide soit une de vos informations est invalide, veuillez contacter le webmaster !";
			die();
		}
		else
		{
			$update = $this->db->prepare("UPDATE users SET status = 1 WHERE login = :login AND token = :token;");
			$update->execute(array( ':login' => $login,
									':token' => $token));
			header('Location: index.php');
		}
	}
}

?>