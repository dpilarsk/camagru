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
				$insert_upload = $this->db->prepare("INSERT INTO pictures (user_id, path, status) VALUES (:user_id, :path, 0)");
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
				$insert_upload = $this->db->prepare("INSERT INTO pictures (user_id, path, status) VALUES (:user_id, :path, 0)");
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

	public function getLastPics($token)
	{
		$getUser = $this->db->prepare("SELECT * FROM users WHERE token = :token;");
		$getUser->execute(array(':token' => $token));
		$res = $getUser->fetchAll();
		$getUser->closecursor();
		if (count($res) == 0)
		{
			die('Veuillez vous reconnecter !');
		}
		else
		{
			$getPics = $this->db->prepare("SELECT * FROM pictures WHERE user_id = :id AND status = 0 ORDER BY id DESC LIMIT 5;");
			$getPics->execute(array(':id' => $res[0]['id']));
			$res = $getPics->fetchAll();
			$getPics->closeCursor();
			return $res;
		}
	}

	public function getPic($id)
	{
		$getUser = $this->db->prepare("SELECT * FROM pictures WHERE id = :id;");
		$getUser->execute(array(':id' => $id));
		$res = $getUser->fetchAll();
		$getUser->closecursor();
		if (count($res) == 0)
		{
			return (0);
		}
		else
		{
			return $res[0];
		}
	}

	public function getAllPics($page = 1)
	{
		$offset = ($page - 1) * 25;
		$getPics = $this->db->prepare("SELECT * FROM pictures WHERE status = 0 ORDER BY id DESC LIMIT 25 OFFSET " . $offset . ";");
		$getPics->execute();
		$res = $getPics->fetchAll();
		$getPics->closeCursor();
		return $res;
	}

	public function getUser($id)
	{
		$getUser = $this->db->prepare("SELECT * FROM users WHERE id = :id;");
		$getUser->execute(array(':id' => $id));
		$res = $getUser->fetchAll();
		$getUser->closecursor();
		return $res[0]['login'];
	}

	public function getLikes($id)
	{
		$getUser = $this->db->prepare("SELECT * FROM likes WHERE picture_id = :id AND like_dis = 1;");
		$getUser->execute(array(':id' => $id));
		$res = $getUser->fetchAll();
		$getUser->closecursor();
		return count($res);
	}

	public function getLikeUser($token, $pic)
	{
		$getUser = $this->db->prepare("SELECT * FROM users WHERE token = :token;");
		$getUser->execute(array(':token' => $token));
		$res = $getUser->fetchAll();
		$getUser->closecursor();
		if (count($res) == 0)
		{
			die();
		}
		else
		{
			$getUserLike = $this->db->prepare("SELECT * FROM likes WHERE picture_id = :id AND like_dis = 1 AND user_id = :u_id;");
			$getUserLike->execute(array(':id' => $pic,
				':u_id' => $res[0]['id']));
			$res = $getUserLike->fetchAll();
			$getUserLike->closecursor();
			if (count($res) == 0)
			{
				return (0);
			}
			else
			{
				return (1);
			}
		}
	}

	public function like($user, $pic)
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
			$getLike = $this->db->prepare("SELECT * FROM likes WHERE user_id = :u_id AND picture_id = :p_id;");
			$getLike->execute(array(':u_id' => $res[0]['id'],
									':p_id' => $pic));
			$res1 = $getLike->fetchAll();
			$getLike->closeCursor();
			$getUserIdPic = $this->db->prepare("SELECT * FROM pictures WHERE id = :id;");
			$getUserIdPic->execute(array(':id' => $pic));
			$res2 = $getUserIdPic->fetchAll();
			$getUserIdPic->closeCursor();
			$getUser = $this->db->prepare("SELECT * FROM users WHERE id = :id;");
			$getUser->execute(array(':id' => $res2[0]['user_id']));
			$res3 = $getUser->fetchAll();
			$getUser->closeCursor();
			if (count($res1) == 0)
			{
				$insert = $this->db->prepare("INSERT INTO likes (user_id, picture_id, like_dis) VALUES (:u_id, :p_id, 1);");
				$insert->execute(array(':u_id' => $res[0]['id'],
										':p_id' => $pic));
				$insert->closeCursor();
				echo 'like';
			}
			else
			{
				if ($res1[0]['like_dis'] == 1)
				{
					$update = $this->db->prepare("UPDATE likes SET like_dis = 0 WHERE picture_id = :p_id;");
					$update->execute(array(':p_id' => $pic));
					$update->closeCursor();
					echo 'dislike';
				}
				else
				{
					$update = $this->db->prepare("UPDATE likes SET like_dis = 1 WHERE picture_id = :p_id;");
					$update->execute(array(':p_id' => $pic));
					$update->closeCursor();
					mail($res3[0]['email'],
						'Un utilisateur a aime votre photo',
						"Un utilisateur a aime votre photo, allez voir:\r\n
							http://localhost:8080/view.php?id=" . $pic);
					echo 'like';
				}
			}
		}
	}

	public function delete($pic, $token)
	{
		$getUser = $this->db->prepare("SELECT * FROM users WHERE token = :token;");
		$getUser->execute(array(':token' => $token));
		$res = $getUser->fetchAll();
		$getUser->closecursor();
		if (count($res) == 0)
		{
			header('Location: /');
			$_SESSION['flash'] = "Votre compte n'est pas valide !";
		}
		else
		{
			$getPic = $this->db->prepare("SELECT * FROM pictures WHERE user_id = :u_id AND id = :p_id;");
			$getPic->execute(array(':u_id' => $res[0]['id'],
									':p_id' => $pic));
			$res1 = $getPic->fetchAll();
			$getPic->closecursor();
			if (count($res1) == 0)
			{
				header('Location: /');
				$_SESSION['flash'] = "Cette image est introuvable ou ne vous appartient pas";
			}
			else
			{
				$delete = $this->db->prepare("DELETE FROM pictures WHERE user_id = :u_id AND id = :p_id");
				$delete->execute(array(':u_id' => $res[0]['id'],
										':p_id' => $pic));
				header('Location: /');
				$_SESSION['flash'] = "L'image est supprimee";
			}
		}
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
