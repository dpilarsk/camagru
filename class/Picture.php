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
		$dir = "../public/uploads/";
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
				echo "Le fichier a ete uploade dans: $target_file";
			}
			else
			{
				echo 'Un probleme a eu lieu !';
			}
		}
	}
}