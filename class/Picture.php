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

	public function upload_img_wl($file, $user, $layer_id)
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
			$source = $this->getLayerPath($layer_id);
			if (count($source) == 0)
			{
				echo 'Veuillez selectionner un filtre valide !';
				die();
			}
			$source = $this->resizePic('..' . $source[0]['path']);
			if ($fileType == 'png')
				$dest = imagecreatefrompng($file['tmp_name']);
			else if ($fileType == 'jpg' || $fileType == 'jpeg')
				$dest = imagecreatefromjpeg($file['tmp_name']);
			imagecopy($dest, $source, 0, 0, 0, 0, imagesx($source), imagesy($source));
			$newP = "../public/tmp/" . $file['name'];
			imagejpeg($dest, $newP);
			if (rename($newP, $target_file))
			{
				imagedestroy($dest);
				$new_target = explode('..', $target_file);
				$new_target = $new_target[1];
				$insert_upload = $this->db->prepare("INSERT INTO pictures (user_id, layer_id, path, status) VALUES (:user_id, 0, :path, 0)");
				$insert_upload->execute(array(  ':user_id' => $res[0]['id'],
												':path' => $new_target));
				$insert_upload->closeCursor();
//				echo("Le fichier a ete uploade dans: $new_target\n");
			}
			else
			{
				echo 'Un probleme a eu lieu !';
			}
		}
	}

	public function upload_img_wc($url, $user, $layer_id)
	{
		$dir = "../public/uploads/";
		$getUser = $this->db->prepare("SELECT * FROM users WHERE token = :token;");
		$getUser->execute(array(':token' => $user));
		$res = $getUser->fetchAll();
		$getUser->closecursor();
		$uploadOk = 1;
		if (count($res) == 0)
		{
			$uploadOk = 0;
		}
		$target_file = $dir . basename($res[0]['id'] . "__" . time() . "__.png");
		if (file_exists($target_file))
		{
			echo 'Un fichier a deja ce nom';
			$uploadOk = 0;
		}
		if ($uploadOk == 0)
		{
			echo "Le fichier n'a pas ete uploade !";
		}
		else
		{
			$source = $this->getLayerPath($layer_id);
			if (count($source) == 0)
			{
				echo 'Veuillez selectionner un filtre valide !';
				die();
			}
			$source = $this->resizePic('..' . $source[0]['path']);
			$dest = imagecreatefrompng($url);
			imagecopy($dest, $source, 0, 0, 0, 0, imagesx($source), imagesy($source));
			$newP = "../public/tmp/" . $res[0]['id'] . "__" . time() . "__.png";
			imagepng($dest, $newP);
			if (rename($newP, $target_file))
			{
				imagedestroy($dest);
				$new_target = explode('..', $target_file);
				$new_target = $new_target[1];
				$insert_upload = $this->db->prepare("INSERT INTO pictures (user_id, layer_id, path, status) VALUES (:user_id, 0, :path, 0)");
				$insert_upload->execute(array(  ':user_id' => $res[0]['id'],
					':path' => $new_target));
				$insert_upload->closeCursor();
			}
			else
			{
				echo 'Un probleme a eu lieu !';
			}
		}
	}

	public function getLayers()
	{
		$layers = $this->db->prepare('SELECT * FROM layers');
		$layers->execute();
		$res = $layers->fetchall();
		$layers->closeCursor();
		return $res;
	}

	private function getLayerPath($layer_id)
	{
		$path = $this->db->prepare('SELECT * FROM layers WHERE id = :id;');
		$path->execute(array(':id' => $layer_id));
		$res = $path->fetchAll();
		$path->closeCursor();
		return $res;
	}

	private function resizePic($src)
	{
		$img = imagecreatefrompng($src);
		$initSize = getimagesize($src);
		$Width = 320;
		$Height = 240;
		$reduce = ($Width * 100) / $initSize[0];
		$newHeight = ($initSize[1] * $reduce) / 100;
		$newimg = imagecreatetruecolor($Width, $Height);
		imagesavealpha($newimg, true);
		$trans_color = imagecolorallocatealpha($newimg, 0, 0, 0, 127);
		imagefill($newimg, 0, 0, $trans_color);
		imagecopyresized($newimg, $img, 0, 0, 0, 0, $Width, $Height, $initSize[0], $initSize[1]);
		imagesavealpha($newimg, true);
		imagedestroy($img);
		return $newimg;
	}
}