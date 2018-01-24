<?php
if (PHP_SAPI === "cli")
{
	require_once 'class/Database.class.php';
	require_once 'Database.class.php';

	$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
	$db->init_database("camagru");
}
else
{
	echo "<img src='https://media.giphy.com/media/RX3vhj311HKLe/giphy.gif'>";
}
?>
