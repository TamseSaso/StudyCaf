<?php
	try {
		$host = "localhost";
		$user = "root";
		$password = "";
		$db = "Studycaf";
		$charset = "utf8mb4";

		$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

		$options = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES   => false,
		];

		$pdo = new PDO($dsn, $user, $password, $options);

	} catch (PDOException $e) {
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}
?>