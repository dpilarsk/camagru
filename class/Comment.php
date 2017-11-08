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
		$getComments = $this->db->prepare("SELECT * FROM comments WHERE picture_id = :id;");
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
}