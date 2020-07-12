<?php
$mysqli = new mysqli("localhost", "root", "root", "back_office");

/* Vérification de la connexion */
if ($mysqli->connect_errno) {
	printf("Échec de la connexion : %s\n", $mysqli->connect_error);
	exit();
}