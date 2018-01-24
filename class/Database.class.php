<?php

class Database
{

	private $dsn;
	private $user;
	private $pass;
	private $db;
	private $dbname;

	public function __construct($dsn, $user, $pass)
	{
		$this->dsn = $dsn;
		$this->user = $user;
		$this->pass = $pass;
		try
		{
			$this->db = new PDO($this->dsn, $this->user, $this->pass);
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			die ("Un problème est survenu lors de la connexion à la base de données: " . $e->getCode());
		}
	}

	public function init_database($name = "setup")
	{
		$this->dbname = $name;
		$this->db->exec("DROP DATABASE IF EXISTS $name;");
		try {
			$this->db->exec("CREATE DATABASE $name;");
			echo("\e[32m* La base de données `\e[94m$name\e[0m\e[32m` à été créée avec succès.\e[0m\n");
		} catch (\Exception $e) {
			die("\e[31m> La base de données `\e[94m$name\e[0m\e[31m` n'a pas pu être créée:\n> " . $e->getMessage() . ".\e[0m\n");
		}
		$this->db->query("use $name;");
		try {
			$this->db->query("CREATE TABLE IF NOT EXISTS users (
				id int AUTO_INCREMENT NOT NULL,
				login varchar(255) NOT NULL,
				password varchar(255) NOT NULL,
				email varchar(255) NOT NULL,
				status int NOT NULL,
				role ENUM('user', 'moderator', 'admin') DEFAULT 'user',
				token varchar(255) NOT NULL,
				end_at datetime,
				get_comments int(1) DEFAULT '1',
				primary key (id));");
			echo("\e[32m* La table `\e[94musers\e[0m\e[32m` à été créée avec succès.\e[0m\n");
		} catch (\Exception $e) {
			die("\e[31m> La table `\e[94musers\e[0m\e[31m` n'a pas pu être créée:\n> " . $e->getMessage() . ".\e[0m\n");
		}
		try {
			$this->db->query("CREATE TABLE IF NOT EXISTS pictures (
				id int AUTO_INCREMENT NOT NULL,
				user_id int NOT NULL,
				path varchar(255) NOT NULL,
				status int NOT NULL,
				primary key (id));");
			echo("\e[32m* La table `\e[94mpictures\e[0m\e[32m` à été créée avec succès.\e[0m\n");
		} catch (\Exception $e) {
			die("\e[31m> La table `\e[94mpictures\e[0m\e[31m` n'a pas pu être créée:\n> " . $e->getMessage() . ".\e[0m\n");
		}


		try {
			$this->db->query("CREATE TABLE IF NOT EXISTS comments (
				id int AUTO_INCREMENT NOT NULL,
				user_id int NOT NULL,
				picture_id int NOT NULL,
				comment TEXT NOT NULL,
				primary key (id));");
			echo("\e[32m* La table `\e[94mcomments\e[0m\e[32m` à été créée avec succès.\e[0m\n");
		} catch (\Exception $e) {
			die("\e[31m> La table `\e[94mcomments\e[0m\e[31m` n'a pas pu être créée:\n> " . $e->getMessage() . ".\e[0m\n");
		}


		try {
			$this->db->query("CREATE TABLE IF NOT EXISTS likes (
				id int AUTO_INCREMENT NOT NULL,
				user_id int NOT NULL,
				picture_id int NOT NULL,
				like_dis int NOT NULL,
				primary key (id));");
			echo("\e[32m* La table `\e[94mlikes\e[0m\e[32m` à été créée avec succès.\e[0m\n");
		} catch (\Exception $e) {
			die("\e[31m> La table `\e[94mlikes\e[0m\e[31m` n'a pas pu être créée:\n> " . $e->getMessage() . ".\e[0m\n");
		}


		try {
			$this->db->query("CREATE TABLE IF NOT EXISTS layers (
				id int AUTO_INCREMENT NOT NULL,
				path varchar(255) NOT NULL,
				primary key (id));");
			echo("\e[32m* La table `\e[94mlayers\e[0m\e[32m` à été créée avec succès.\e[0m\n");
		} catch (\Exception $e) {
			die("\e[31m> La table `\e[94layers\e[0m\e[31m` n'a pas pu être créée:\n> " . $e->getMessage() . ".\e[0m\n");
		}

		try {
			if (!file_exists("public/layouts/xen_xen.png"))
				die("\e[31m> Le filtre `\e[94mxen\e[0m\e[31m` n'a pas pu être ajouté à la base de données.\e[0m\n");
			else
			{
				$this->db->query("INSERT INTO layers (id, path) VALUES(1, '/public/layouts/xen_xen.png');");
				echo("\e[32m* Le filtre `\e[94mxen\e[0m\e[32m` à été ajouté avec succès.\e[0m\n");
			}
		} catch (\Exception $e) {

		}



		try {
			$password = hash('sha256', microtime());
			$newpass = hash('sha256', $password);
			$this->db->query("INSERT INTO users (login, password, email, status, token, role) VALUES (\"admin\", \"$newpass\", \"admin@camagru.fr\", 1, \"abcdef1234\", \"admin\");");
			echo("\e[32m* L'utilisateur `\e[94madmin\e[0m\e[32m` à été crée avec succès.\e[0m\n");
			echo("\e[32m* Son mot de passe est: `\e[94m$password\e[0m\e[32m`\e[0m\n");
		} catch (\Exception $e) {
			die("\e[31m> L'utilisateur `\e[94madmin\e[0m\e[31m` n'a pas pu être crée:\n> " . $e->getMessage() . ".\e[0m\n");
		}
	}

	public function connect($name = 'setup')
	{
		if (!$this->db->query("use $name;"))
			die("Please setup database");
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $this->db;
	}
}

?>
