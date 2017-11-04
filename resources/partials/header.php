<header>
	<nav>
		<ul>
			<hr>
			<a href="/"><li>Accueil</li></a>
			<hr>
            <a href="gallery.php"><li>Galerie</li></a>
            <hr>
            <?php if (isset($_SESSION['id'])){ ?>
				<a href="assembly.php"><li>Montage</li></a>
				<hr>
				<a href="account.php"><li>Compte</li></a>
				<hr>
				<a href="disconnect.php"><li>Deconnexion</li></a>
				<hr>
			<?php } else { ?>
				<a href="login.php"><li>Connexion</li></a>
				<hr>
				<a href="register.php"><li>Inscription</li></a>
				<hr>
			<?php } ?>
		</ul>
	</nav>
</header>