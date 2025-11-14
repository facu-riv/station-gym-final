<?php
class PublicController {
  private CategoriaModel $categorias; private ActividadModel $actividades;
  public function __construct(){ $this->categorias=new CategoriaModel(); $this->actividades=new ActividadModel(); $this->categorias->ensureSchema(); $this->actividades->ensureSchema(); }
  public function home(): void { $categorias=$this->categorias->all(); $actividades=$this->actividades->byCategoria(null); include __DIR__ . '/../views/public/home.phtml'; }
  public function categorias(): void { $categorias=$this->categorias->all(); include __DIR__ . '/../views/public/categorias.phtml'; }
  public function actividadesPorCategoria(int $categoriaID): void { $categorias=$this->categorias->all(); $actividades=$this->actividades->byCategoria($categoriaID); include __DIR__ . '/../views/public/actividades_por_categoria.phtml'; }
  public function actividadDetalle(int $actividadID): void { $actividad=$this->actividades->get($actividadID); if(!$actividad){ http_response_code(404); echo "Actividad no encontrada"; return;} include __DIR__ . '/../views/public/actividad_detalle.phtml'; }
}
