<?php
$pdo = new PDO("sqlite:./data.sqlite", null, null, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
$pdo->query("DELETE FROM users");
$pdo->query("DELETE from sqlite_sequence where name='users';");
$query = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$query->execute([
    "admin",
    password_hash("admin", PASSWORD_BCRYPT),
    "admin",
]);
$query->execute([
    "user",
    password_hash("user", PASSWORD_BCRYPT),
    "user",
]);
echo "done";
