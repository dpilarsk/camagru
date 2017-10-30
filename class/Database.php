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
								  	token varchar(255) NOT NULL,
								  	primary key (id)
						);");

		print_r ("users TABLE CREATED.<br/>");
		$this->db->query("CREATE TABLE IF NOT EXISTS pictures (
								  	id int AUTO_INCREMENT NOT NULL,
								  	user_id int NOT NULL,
								  	layer_id int NOT NULL,
								  	path varchar(255) NOT NULL,
								  	status int NOT NULL,
								  	primary key (id)
						);");

		print_r ("pictures TABLE CREATED.<br/>");
		$this->db->query("CREATE TABLE IF NOT EXISTS layers (
								  	id int AUTO_INCREMENT NOT NULL,
								  	path varchar(255) NOT NULL,
								  	primary key (id)
						);");

		$newpass = hash(sha256, microtime());
		print_r ("layers TABLE CREATED.<br/>");
		print_r ("User: <b>admin</b><br/>Password: <b>$newpass</b>");
		$newpass = hash(sha256, $newpass);
		$this->db->query("INSERT INTO users (login, password, email, status, token) VALUES (\"admin\", \"$newpass\", \"admin@camagru.fr\", 3, \"abcdef1234\");");
	}

	public function connect($name = 'setup')
	{
		if (!$this->db->query("use $name;"))
			print_r("Please setup database");
	}
}

?>