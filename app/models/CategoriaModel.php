<?php
class CategoriaModel {
  public function ensureSchema(): void {
    DB::pdo()->exec("CREATE TABLE IF NOT EXISTS categorias (id INT AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(120) NOT NULL, imagen VARCHAR(255) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
  }
  public function all(): array { return DB::pdo()->query("SELECT * FROM categorias ORDER BY nombre")->fetchAll(); }
  public function get(int $id): ?array { $st=DB::pdo()->prepare("SELECT * FROM categorias WHERE id=?"); $st->execute([$id]); $r=$st->fetch(); return $r?:null; }
  public function create(string $nombre, ?string $imagen): int { $st=DB::pdo()->prepare("INSERT INTO categorias (nombre, imagen) VALUES (?, ?)"); $st->execute([$nombre,$imagen]); return (int)DB::pdo()->lastInsertId(); }
  public function update(int $id, string $nombre, ?string $imagen): void {
    if ($imagen!==null) { $st=DB::pdo()->prepare("UPDATE categorias SET nombre=?, imagen=? WHERE id=?"); $st->execute([$nombre,$imagen,$id]); }
    else { $st=DB::pdo()->prepare("UPDATE categorias SET nombre=? WHERE id=?"); $st->execute([$nombre,$id]); }
  }
  public function delete(int $id): void { $st=DB::pdo()->prepare("DELETE FROM categorias WHERE id=?"); $st->execute([$id]); }
}
