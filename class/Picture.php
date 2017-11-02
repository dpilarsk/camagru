<?php

class Picture
{
	private $db;

	public function __construct($db)
	{
		$this->db = $db;
	}

	public function upload_layout($name, $file, $user)
	{
		$dir = "../public/layouts/";
		$fileType = pathinfo($file['name'], PATHINFO_EXTENSION);
		$target_file = $dir . basename($name . "_" . $file['name']);
		$uploadOk = 1;
		if (file_exists($target_file))
		{
			echo 'Un fichier a deja ce nom';
			$uploadOk = 0;
		}
		if ($file['size'] > 5000000)
		{
			echo 'Le fichier est trop grand !';
			$uploadOk = 0;
		}
		if ($fileType != 'png')
		{
			echo 'Uniquement un .png';
			$uploadOk = 0;
		}
		if ($uploadOk == 0)
		{
			echo "Le fichier n'a pas ete uploade !";
		}
		else
		{
			if (move_uploaded_file($file['tmp_name'], $target_file))
			{
				$new_target = explode('..', $target_file);
				$new_target = $new_target[1];
				$insert_upload = $this->db->prepare("INSERT INTO layers (path) VALUES (:path);");
				$insert_upload->execute(array(':path' => $new_target));
				$insert_upload->closeCursor();
				echo("Le fichier a ete uploade dans: $new_target\n");
			}
			else
			{
				echo 'Un probleme a eu lieu !';
			}
		}
	}

	public function upload_img_wl($file, $user)
	{
		$dir = "../public/uploads/";
		$fileType = pathinfo($file['name'], PATHINFO_EXTENSION);
		$getUser = $this->db->prepare("SELECT * FROM users WHERE token = :token;");
		$getUser->execute(array(':token' => $user));
		$res = $getUser->fetchAll();
		$getUser->closecursor();
		$uploadOk = 1;
		if (count($res) == 0)
		{
			$uploadOk = 0;
		}
		$target_file = $dir . basename($res[0]['id'] . "__" . time() . "__" . $file['name']);
		if (file_exists($target_file))
		{
			echo 'Un fichier a deja ce nom';
			$uploadOk = 0;
		}
		if ($file['size'] > 5000000)
		{
			echo 'Le fichier est trop grand !';
			$uploadOk = 0;
		}
		if ($fileType != 'png' && $fileType != 'jpg' && $fileType != 'jpeg')
		{
			echo 'Uniquement un .png ou .jpg ou .jpeg';
			$uploadOk = 0;
		}
		if ($uploadOk == 0)
		{
			echo "Le fichier n'a pas ete uploade !";
		}
		else
		{
			if (move_uploaded_file($file['tmp_name'], $target_file))
			{
				$new_target = explode('..', $target_file);
				$new_target = $new_target[1];
				$insert_upload = $this->db->prepare("INSERT INTO pictures (user_id, layer_id, path, status) VALUES (:user_id, 0, :path, 0)");
				$insert_upload->execute(array(  ':user_id' => $res[0]['id'],
												':path' => $new_target));
				$insert_upload->closeCursor();
				echo("Le fichier a ete uploade dans: $new_target\n");
			}
			else
			{
				echo 'Un probleme a eu lieu !';
			}
		}
	}
}