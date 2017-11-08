<?php

class Comment
{
	private $db;

	public function __construct($db)
	{
		$this->db = $db;
	}

	public function getLastComments($id)
	{
		$getComments = $this->db->prepare("SELECT * FROM comments WHERE picture_id = :id ORDER BY id DESC;");
		$getComments->execute(array(':id' => $id));
		$res = $getComments->fetchAll();
		$getComments->closecursor();
		if (count($res) == 0)
		{
			die('Aucun commentaire trouve.');
		}
		else
		{
			return $res;
		}
	}

	public function add($comment, $user, $pic)
	{
		$getUser = $this->db->prepare("SELECT * FROM users WHERE token = :token;");
		$getUser->execute(array(':token' => $user));
		$res = $getUser->fetchAll();
		$getUser->closecursor();
		if (count($res) == 0)
		{
			die();
		}
		else
		{
			$insert = $this->db->prepare("INSERT INTO comments(user_id, picture_id, comment) VALUES (:u_id, :p_id, :comment);");
			$insert->execute(array( ':u_id' => $res[0]['id'],
				':p_id' => $pic,
				':comment' => $comment));
		}
	}
}