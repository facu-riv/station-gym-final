<?php
class AdminController {
  private CategoriaModel $categorias; private ActividadModel $actividades;
  public function __construct(){ $this->categorias = new CategoriaModel(); $this->actividades = new ActividadModel(); $this->categorias->ensureSchema(); $this->actividades->ensureSchema(); }
  public function index(): void {
    $categoriaID = isset($_GET['categoria_id']) ? (int)$_GET['categoria_id'] : null;
    $categorias = $this->categorias->all();
    $actividades = $this->actividades->byCategoria($categoriaID);
    $flash = $_GET['msg'] ?? null;
    include __DIR__ . '/../views/admin/index.phtml';
  }
  public function createCategoria(): void {
    if (!csrf_check($_POST['csrf'] ?? '')) { http_response_code(400); die('CSRF'); }
    $nombre = trim($_POST['nombre'] ?? ''); if ($nombre==='') { redirect('admin?msg=Nombre requerido'); }
    $img = null;
    if (!empty($_FILES['imagen']['name'])) { try { $img = Upload::handle($_FILES['imagen']); } catch (Throwable $e) { redirect('admin?msg='.urlencode($e->getMessage())); } }
    if (!empty($_POST['imagen_url'])) { try { $img = Upload::fromUrl($_POST['imagen_url']); } catch (Throwable $e) { redirect('admin?msg='.urlencode($e->getMessage())); } }
    $this->categorias->create($nombre,$img); redirect('admin?msg=Categoría creada');
  }
  public function editCategoria(): void {
    if (!csrf_check($_POST['csrf'] ?? '')) { http_response_code(400); die('CSRF'); }
    $id=(int)($_POST['id']??0); $nombre=trim($_POST['nombre']??''); if ($id<=0||$nombre===''){ redirect('admin?msg=Datos inválidos'); }
    $img=null;
    if (!empty($_FILES['imagen']['name'])) { try { $img = Upload::handle($_FILES['imagen']); } catch (Throwable $e) { redirect('admin?msg='.urlencode($e->getMessage())); } }
    if (!empty($_POST['imagen_url'])) { try { $img = Upload::fromUrl($_POST['imagen_url']); } catch (Throwable $e) { redirect('admin?msg='.urlencode($e->getMessage())); } }
    $this->categorias->update($id,$nombre,$img); redirect('admin?msg=Categoría actualizada');
  }
  public function deleteCategoria(): void {
    if (!csrf_check($_POST['csrf'] ?? '')) { http_response_code(400); die('CSRF'); }
    $id=(int)($_POST['id']??0); if ($id>0) $this->categorias->delete($id); redirect('admin?msg=Categoría eliminada');
  }
  public function createActividad(): void {
    if (!csrf_check($_POST['csrf'] ?? '')) { http_response_code(400); die('CSRF'); }
    $nombre = trim($_POST['nombre'] ?? ''); $categoriaID = (int)($_POST['categoria_id'] ?? 0);
    if ($nombre===''||$categoriaID<=0){ redirect('admin?msg=Datos inválidos'); }
    $img = null;
    if (!empty($_FILES['imagen']['name'])) { try { $img = Upload::handle($_FILES['imagen']); } catch (Throwable $e) { redirect('admin?msg='.urlencode($e->getMessage())); } }
    if (!empty($_POST['imagen_url'])) { try { $img = Upload::fromUrl($_POST['imagen_url']); } catch (Throwable $e) { redirect('admin?msg='.urlencode($e->getMessage())); } }
    $this->actividades->create($nombre,$categoriaID,$img); redirect('admin?msg=Actividad creada');
  }
  public function editActividad(): void {
    if (!csrf_check($_POST['csrf'] ?? '')) { http_response_code(400); die('CSRF'); }
    $id=(int)($_POST['id']??0); $nombre=trim($_POST['nombre']??''); $categoriaID=(int)($_POST['categoria_id']??0);
    if ($id<=0||$nombre===''||$categoriaID<=0){ redirect('admin?msg=Datos inválidos'); }
    $img = null;
    if (!empty($_FILES['imagen']['name'])) { try { $img = Upload::handle($_FILES['imagen']); } catch (Throwable $e) { redirect('admin?msg='.urlencode($e->getMessage())); } }
    if (!empty($_POST['imagen_url'])) { try { $img = Upload::fromUrl($_POST['imagen_url']); } catch (Throwable $e) { redirect('admin?msg='.urlencode($e->getMessage())); } }
    $this->actividades->update($id,$nombre,$categoriaID,$img); redirect('admin?msg=Actividad actualizada');
  }
  public function deleteActividad(): void {
    if (!csrf_check($_POST['csrf'] ?? '')) { http_response_code(400); die('CSRF'); }
    $id=(int)($_POST['id']??0); if ($id>0) $this->actividades->delete($id); redirect('admin?msg=Actividad eliminada');
  }
}
