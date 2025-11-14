<?php
class ActividadModel {
  public function ensureSchema(): void {
    DB::pdo()->exec("CREATE TABLE IF NOT EXISTS actividades (id INT AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(120) NOT NULL, categoria_id INT NOT NULL, imagen VARCHAR(255) DEFAULT NULL, CONSTRAINT fk_cat FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
  }
  public function byCategoria(?int $categoriaID): array {
    if ($categoriaID) {
      $st=DB::pdo()->prepare("SELECT a.*, c.nombre AS categoria FROM actividades a JOIN categorias c ON c.id=a.categoria_id WHERE categoria_id=? ORDER BY a.nombre");
      $st->execute([$categoriaID]); return $st->fetchAll();
    }
    return DB::pdo()->query("SELECT a.*, c.nombre AS categoria FROM actividades a JOIN categorias c ON c.id=a.categoria_id ORDER BY c.nombre, a.nombre")->fetchAll();
  }
  public function get(int $id): ?array {
    $st=DB::pdo()->prepare("SELECT a.*, c.nombre AS categoria FROM actividades a JOIN categorias c ON c.id=a.categoria_id WHERE a.id=?");
    $st->execute([$id]); $r=$st->fetch(); return $r?:null;
  }
  public function create(string $nombre, int $categoriaID, ?string $imagen): int { $st=DB::pdo()->prepare("INSERT INTO actividades (nombre, categoria_id, imagen) VALUES (?, ?, ?)"); $st->execute([$nombre,$categoriaID,$imagen]); return (int)DB::pdo()->lastInsertId(); }
  public function update(int $id, string $nombre, int $categoriaID, ?string $imagen): void {
    if ($imagen!==null) { $st=DB::pdo()->prepare("UPDATE actividades SET nombre=?, categoria_id=?, imagen=? WHERE id=?"); $st->execute([$nombre,$categoriaID,$imagen,$id]); }
    else { $st=DB::pdo()->prepare("UPDATE actividades SET nombre=?, categoria_id=? WHERE id=?"); $st->execute([$nombre,$categoriaID,$id]); }
  }
  public function delete(int $id): void { $st=DB::pdo()->prepare("DELETE FROM actividades WHERE id=?"); $st->execute([$id]); }
}
