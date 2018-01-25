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
			die('<p>Aucun commentaire trouve.</p>');
		else
			return $res;
	}

	public function add($comment, $user, $pic)
	{
		$getUser = $this->db->prepare("SELECT * FROM users WHERE token = :token;");
		$getUser->execute(array(':token' => $user));
		$res = $getUser->fetchAll();
		$getUser->closecursor();
		if (count($res) == 0)
			die("<p>Impossible d'ajouter votre commentaire.</p>");
		else
		{
			try {
				$insert = $this->db->prepare("INSERT INTO comments(user_id, picture_id, comment) VALUES (:u_id, :p_id, :comment);");
				$insert->execute(array( ':u_id' => $res[0]['id'],
					':p_id' => $pic,
					':comment' => $comment));
				$getUserPictureInfo = $this->db->prepare("SELECT user_id FROM pictures WHERE id = :id");
				$getUserPictureInfo->execute(array(':id' => $pic));
				$res1 = $getUserPictureInfo->fetchAll();
				$getUserInfos = $this->db->prepare("SELECT email, get_comments FROM users WHERE id = :id");
				$getUserInfos->execute(array(':id' => $res1[0]["user_id"]));
				$res2 = $getUserInfos->fetchAll()[0];
				if ($res2['get_comments'] == 1)
				{
					mail($res2['email'],
						'Un utilisateur a commenté votre photo',
						"Un utilisateur a commenté votre photo, allez voir:\r\n
								http://" . $_SERVER['HTTP_HOST'] . "/view.php?id=" . $pic);
				}
			} catch (\Exception $e) {
				die("<p>Une erreur est survenue lors de la création de votre commentaire.</p>");
			}

		}
	}
}
