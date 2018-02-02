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
		$this->password = hash("sha256", $data['password']);
		$this->email = $data['email'];
		$this->token = hash("sha256", date("d-D-z-W:hH0s37u"));
		$get_user = $this->db->prepare("SELECT * FROM users WHERE login = :username OR email = :email;");
		$get_user->execute(array(':username' => $this->username, ':email' => $this->email));
		$res = $get_user->fetchAll();
		$get_user->closeCursor();
		if (count($res) != 0)
		{
			http_response_code(412);
			echo "<p>Un utilisateur utilise deja ce nom ou cet email !</p>";
		}
		else
		{
			$insert = $this->db->prepare("INSERT INTO users(login, password, email, status, token, end_at) VALUES (:username, :password, :email, 0, :token, :end_at);");
			$insert->execute(array( ':username' => $this->username,
									':password' => $this->password,
									':email' => $this->email,
									':token' => $this->token,
									':end_at' =>  date('Y-m-d h:m:s', strtotime('+ 1 day'))));
			if (mail($this->email,
				'Confirmation du compte',
				"Afin de confirmer votre compte Camagru, merci de cliquer ici:\n\n
							http://" . $_SERVER['HTTP_HOST'] . "/confirm.php?login=$this->username&token=$this->token"))
							echo "<p>Veuillez consulter votre email pour confirmer votre compte !</p>";
			else
			{
				http_response_code(412);
				echo "<p>Impossible d'envoyer le mail.</p>";
			}
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
			echo "<p>Votre compte est soit deja valide soit une de vos informations est invalide, veuillez contacter le webmaster !</p>";
			return ;
		}
		else
		{
			if (date('Y-m-d h:m:s') > $res[0]['end_at'])
			{
				echo "<p>Votre Token n'est plus valide, veuillez redemander un mot de passe.</p>";
				return ;
			}
			$update = $this->db->prepare("UPDATE users SET status = 1 WHERE login = :login AND token = :token;");
			$update->execute(array( ':login' => $login,
									':token' => $token));
			$update->closeCursor();
			echo "<script>
				setTimeout(function () {
					window.location.replace('/');
				}, 100)</script>";
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
			echo '<p>Votre email est introuvable, veuillez le verifier !</p>';
			die();
		}
		else
		{
			$this->username = $res[0]['login'];
			$this->token = hash('sha256', date("d-D-z-W:hH0s37u"));
			$update = $this->db->prepare("UPDATE users SET token = :token, end_at = :end WHERE email = :email;");
			$update->execute(array( ':token' => $this->token,
									':end' => date('Y-m-d h:m:s', strtotime('+ 1 day')),
									':email' => $mail));
			$update->closeCursor();
			mail($mail,
				'Nouveau mot de passe',
				"Voici un lien pour reinitialiser votre mot de passe:
http://" . $_SERVER['HTTP_HOST'] . "/reset.php?login=$this->username&token=$this->token");
			echo "<p>Veuillez consulter votre email pour reinitialiser votre mot de passe !</p>";
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
			echo "<p>Une de vos informations est invalide, veuillez contacter le webmaster !</p>";
			die();
		}
		else
		{
			if (date('Y-m-d h:m:s') > $res[0]['end_at'])
			{
				echo "<p>Votre Token n'est plus valide, veuillez redemander un mot de passe.</p>";
				die();
			}
			$password = hash('sha256', time());
			$passDB = hash('sha256', $password);
			$update = $this->db->prepare("UPDATE users SET password = :password, token = :token2 WHERE login = :login AND token = :token;");
			$update->execute(array( ':password' => $passDB,
									':login' => $login,
									':token2' => hash('sha512', date("d-D-z-W:hH0s37u")),
									':token' => $token));
			$update->closeCursor();
			echo '<p>Votre nouveau mot de passe est: ' . $password . '</p>';
		}
	}

	public function login($login, $password)
	{
		$hashPass = hash('sha256', $password);
		$getUser = $this->db->prepare("SELECT * FROM users WHERE login = :login AND password = :password;");
		$getUser->execute(array(':login' => $login,
								':password' => $hashPass));
		$res = $getUser->fetchAll();
		$getUser->closeCursor();
		if (count($res) == 0)
		{
			http_response_code(412);
			echo "<p>Veuillez verifier votre nom d'utilisateur et votre mot de passe !</p>";
		}
		else if ($res[0]['status'] == 0)
		{
			http_response_code(412);
			echo '<p>Veuillez confirmer votre compte !</p>';
		}
		else if ($res[0]['status'] == 2)
		{
			http_response_code(412);
			echo '<p>Vous avez ete banni, veuillez contacter le webmaster !</p>';
		}
		else
		{
			session_start();
			$_SESSION['id'] = $res[0]['id'];
			$_SESSION['token'] = $res[0]['token'];
			$_SESSION['login'] = $res[0]['login'];
			$_SESSION['role'] = $res[0]['role'];
			$_SESSION['get_email?'] = $res[0]['get_comments'];
			$_SESSION['flash'] = '<p>Vous etes connecte</p>';
		}
		return ;
	}

	public function getUser($id)
	{
		$getUser = $this->db->prepare("SELECT * FROM users WHERE id = :id;");
		$getUser->execute(array(':id' => $id));
		$res = $getUser->fetchAll();
		$getUser->closeCursor();
		if (count($res) == 0)
		{
			die ('<p>Utilisateur disparu</p>');
		}
		else
		{
			return $res[0];
		}
	}

	public function changePass($newPass, $token)
	{
		$this->password = hash('sha256', $newPass);
		$update = $this->db->prepare("UPDATE users SET password = :password WHERE token = :token;");
		$update->execute(array( ':password' => $this->password,
			':token' => $token));
		$update->closecursor();
		echo '<p>Votre mot de passe est bien change</p>';
	}

	public function changeCommentsEmail($value, $token)
	{
		$val = ($value == 1 ? 1 : 0);
		$update = $this->db->prepare("UPDATE users SET get_comments = :value WHERE token = :token;");
		$update->execute(array( ':value' => $val,
			':token' => $token));
		$update->closecursor();
		echo "<p>Vos préférences ont étés mises à jour.</p>";
	}
}

?>
