<?php
class AuthController {
  private UserModel $users;
  public function __construct(){ $this->users = new UserModel(); $this->users->ensureSeed(); }
  public function showLogin(): void { $error = $_GET['error'] ?? null; include __DIR__ . '/../views/auth/login.phtml'; }
  public function signin(): void {
    if (!csrf_check($_POST['csrf'] ?? '')) { http_response_code(400); die('CSRF'); }
    $u = trim($_POST['username'] ?? ''); $p = $_POST['password'] ?? '';
    if ($u===''||$p==='') { redirect('login?error=Datos incompletos'); }
    $user = $this->users->findByUsername($u);
    if (!$user || !password_verify($p, $user['contrasena'])) { redirect('login?error=Usuario o clave inválidos'); }
    $_SESSION['user']=['id'=>$user['id'],'username'=>$user['usuario']]; redirect('admin');
  }
  public function signup(): void {
    if (!csrf_check($_POST['csrf'] ?? '')) { http_response_code(400); die('CSRF'); }
    $u = trim($_POST['username'] ?? ''); $p = $_POST['password'] ?? '';
    if ($u===''||$p==='') { redirect('login?error=Datos incompletos'); }
    if ($this->users->findByUsername($u)) { redirect('login?error=Usuario ya existe'); }
    $this->users->create($u,$p); redirect('login?error=Cuenta creada, ahora ingresá');
  }
  public function logout(): void { $_SESSION=[]; session_destroy(); redirect('login'); }
}
