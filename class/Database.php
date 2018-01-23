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
			die ("Something Happened !" . $e->getMessage());
		}
	}

	public function init_database($name = setup)
	{
		$this->dbname = $name;
		$this->db->exec("DROP DATABASE $name;");
		$this->db->exec("CREATE DATABASE $name;");
		$this->db->query("use $name;");
		print_r ("DATABASE NAMED $name CREATED.<br/>");

		$this->db->query("CREATE TABLE IF NOT EXISTS users (
								  	id int AUTO_INCREMENT NOT NULL,
								  	login varchar(255) NOT NULL,
								  	password varchar(255) NOT NULL,
								  	email varchar(255) NOT NULL,
								  	status int NOT NULL,
								  	role ENUM('user', 'moderator', 'admin') DEFAULT 'user',
								  	token varchar(255) NOT NULL,
								  	end_at datetime,
								  	primary key (id)
						);");
		print_r ("users TABLE CREATED.<br/>");

		$this->db->query("CREATE TABLE IF NOT EXISTS pictures (
								  	id int AUTO_INCREMENT NOT NULL,
								  	user_id int NOT NULL,
								  	path varchar(255) NOT NULL,
								  	status int NOT NULL,
								  	primary key (id)
						);");
		print_r ("pictures TABLE CREATED.<br/>");

		$this->db->query("CREATE TABLE IF NOT EXISTS comments (
								  	id int AUTO_INCREMENT NOT NULL,
								  	user_id int NOT NULL,
								  	picture_id int NOT NULL,
								  	comment TEXT NOT NULL,
								  	primary key (id)
						);");
		print_r ("comments TABLE CREATED.<br/>");

		$this->db->query("CREATE TABLE IF NOT EXISTS likes (
								  	id int AUTO_INCREMENT NOT NULL,
								  	user_id int NOT NULL,
								  	picture_id int NOT NULL,
								  	like_dis int NOT NULL,
								  	primary key (id)
						);");
		print_r ("likes TABLE CREATED.<br/>");

		$this->db->query("CREATE TABLE IF NOT EXISTS layers (
								  	id int AUTO_INCREMENT NOT NULL,
								  	path varchar(255) NOT NULL,
								  	primary key (id)
						);");
		print_r ("layers TABLE CREATED.<br/>");

		$newpass = hash(sha256, microtime());
		print_r ("User: <b>admin</b><br/>Password: <b>$newpass</b>");
		$newpass = hash(sha256, $newpass);
		$this->db->query("INSERT INTO users (login, password, email, status, token, role) VALUES (\"admin\", \"$newpass\", \"admin@camagru.fr\", 1, \"abcdef1234\", \"admin\");");
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
