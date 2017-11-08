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
		date_default_timezone_set('Europe/Paris');
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
			$insert = $this->db->prepare("INSERT INTO users(login, password, email, status, token, end_at) VALUES (:username, :password, :email, 0, :token, :end_at);");
			$insert->execute(array( ':username' => $this->username,
									':password' => $this->password,
									':email' => $this->email,
									':token' => $this->token,
									':end_at' =>  date('Y-m-d h:m:s', strtotime('+ 1 day'))));
			mail($this->email,
				'Confirmation du compte',
				"Afin de confirmer votre compte Camagru, merci de cliquer ici:\n\n
							http://localhost:8080/confirm.php?login=$this->username&token=$this->token");
			echo "Veuillez consulter votre email pour confirmer votre compte !";
		}
	}

	public function checkToken($login, $token)
	{
		date_default_timezone_set('Europe/Paris');
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
			if (date('Y-m-d h:m:s') > $res[0]['end_at'])
			{
				echo "Votre Token n'est plus valide, veuillez redemander un mot de passe.";
				die();
			}
			$update = $this->db->prepare("UPDATE users SET status = 1 WHERE login = :login AND token = :token;");
			$update->execute(array( ':login' => $login,
									':token' => $token));
			$update->closeCursor();
			echo "<script>
				setTimeout(function () {
					window.location.replace('/');
				}, 3000)</script>";
		}
	}

	public function checkMail($mail)
	{
		date_default_timezone_set('Europe/Paris');
		$getMail = $this->db->prepare("SELECT * FROM users WHERE email = :email;");
		$getMail->execute(array(':email' => $mail));
		$res = $getMail->fetchAll();
		$getMail->closeCursor();
		if (count($res) == 0)
		{
			echo 'Votre email est introuvable, veuillez le verifier !';
			die();
		}
		else
		{
			$this->token = hash(sha256, microtime() + 0.3);
			$update = $this->db->prepare("UPDATE users SET token = :token, end_at = :end WHERE email = :email;");
			$update->execute(array( ':token' => $this->token,
									':end' => date('Y-m-d h:m:s', strtotime('+ 1 day')),
									':email' => $mail));
			$update->closeCursor();
			mail($mail,
				'Nouveau mot de passe',
				"Voici un lien pour reinitialiser votre mot de passe:\n\n
							http://localhost:8080/reset.php?login=$this->username&token=$this->token");
			echo "Veuillez consulter votre email pour reinitialiser votre mot de passe !";
		}
	}

	public function checkForgot($login, $token)
	{
		date_default_timezone_set('Europe/Paris');
		$getUser = $this->db->prepare("SELECT * FROM users WHERE login = :login AND token = :token;");
		$getUser->execute(array(':login' => $login,
			':token' => $token));
		$res = $getUser->fetchAll();
		$getUser->closeCursor();
		if (count($res) == 0)
		{
			echo "Une de vos informations est invalide, veuillez contacter le webmaster !";
			die();
		}
		else
		{
			if (date('Y-m-d h:m:s') > $res[0]['end_at'])
			{
				echo "Votre Token n'est plus valide, veuillez redemander un mot de passe.";
				die();
			}
			$password = hash(sha256, time());
			$passDB = hash(sha256, $password);
			$update = $this->db->prepare("UPDATE users SET password = :password, token = :token2 WHERE login = :login AND token = :token;");
			$update->execute(array( ':password' => $passDB,
									':login' => $login,
									':token2' => hash(sha512, microtime() + rand(0.037, 137.029)),
									':token' => $token));
			$update->closeCursor();
			echo 'Votre nouveau mot de passe est: ' . $password;
		}
	}

	public function login($login, $password)
	{
		$hashPass = hash(sha256, $password);
		$getUser = $this->db->prepare("SELECT * FROM users WHERE login = :login AND password = :password;");
		$getUser->execute(array(':login' => $login,
								':password' => $hashPass));
		$res = $getUser->fetchAll();
		$getUser->closeCursor();
		if (count($res) == 0)
		{
			echo "Veuillez verifier votre nom d'utilisateur et votre mot de passe !";
			die();
		}
		else if ($res[0]['status'] == 0)
		{
			echo 'Veuillez confirmer votre compte !';
			die();
		}
		else if ($res[0]['status'] == 2)
		{
			echo 'Vous avez ete banni, veuillez contacter le webmaster !';
			die();
		}
		else
		{
			session_start();
			$_SESSION['id'] = $res[0]['id'];
			$_SESSION['token'] = $res[0]['token'];
			$_SESSION['login'] = $res[0]['login'];
			$_SESSION['role'] = $res[0]['role'];
			echo 'Vous etes connecte';
		}
	}

	public function getUser($id)
	{
		$getUser = $this->db->prepare("SELECT * FROM users WHERE id = :id;");
		$getUser->execute(array(':id' => $id));
		$res = $getUser->fetchAll();
		$getUser->closeCursor();
		if (count($res) == 0)
		{
			die ('Utilisateur disparu');
		}
		else
		{
			return $res[0];
		}
	}
}

?>