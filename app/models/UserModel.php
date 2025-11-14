<?php
class UserModel {
  public function findByUsername(string $username): ?array {
    $st = DB::pdo()->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $st->execute([$username]); $row = $st->fetch(); return $row ?: null;
  }
  public function create(string $username, string $password): int {
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $st = DB::pdo()->prepare("INSERT INTO usuarios (usuario, contrasena) VALUES (?, ?)");
    $st->execute([$username, $hash]); return (int)DB::pdo()->lastInsertId();
  }
  public function ensureSeed(): void {
    DB::pdo()->exec("CREATE DATABASE IF NOT EXISTS gymdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    DB::pdo()->exec("CREATE TABLE IF NOT EXISTS usuarios (id INT AUTO_INCREMENT PRIMARY KEY, usuario VARCHAR(100) UNIQUE NOT NULL, contrasena VARCHAR(255) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    $c = (int)(DB::pdo()->query("SELECT COUNT(*) as c FROM usuarios")->fetch()['c'] ?? 0);
    if ($c===0) { $this->create('webadmin','admin'); }
  }
}
