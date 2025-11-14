<?php
class Upload {
  public static array $allowed = ['image/jpeg','image/png','image/webp'];
  public static int $maxBytes = 2 * 1024 * 1024;
  public static function handle(array $file): ?string {
    if (empty($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) { return null; }
    if ($file['size'] > self::$maxBytes) { throw new RuntimeException('Archivo demasiado grande (max 2MB).'); }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    if (!in_array($mime, self::$allowed, true)) { throw new RuntimeException('Formato no permitido.'); }
    $ext = ['image/jpeg'=>'.jpg','image/png'=>'.png','image/webp'=>'.webp'][$mime] ?? '.bin';
    $name = bin2hex(random_bytes(8)) . $ext;
    $destDir = __DIR__ . '/../../public/uploads'; if (!is_dir($destDir)) { mkdir($destDir, 0775, true); }
    $dest = $destDir . '/' . $name; if (!move_uploaded_file($file['tmp_name'], $dest)) { throw new RuntimeException('No se pudo guardar el archivo.'); }
    return 'uploads/' . $name;
  }
  public static function fromUrl(string $url): ?string {
    $url = trim($url); if ($url === '') return null;
    $ctx = stream_context_create(['http'=>['timeout'=>5]]);
    $data = @file_get_contents($url, false, $ctx); if ($data === false) { throw new RuntimeException('No se pudo descargar la imagen.'); }
    $finfo = new finfo(FILEINFO_MIME_TYPE); $mime = $finfo->buffer($data);
    if (!in_array($mime, self::$allowed, true)) { throw new RuntimeException('Formato no permitido.'); }
    $ext = ['image/jpeg'=>'.jpg','image/png'=>'.png','image/webp'=>'.webp'][$mime] ?? '.bin';
    $name = bin2hex(random_bytes(8)) . $ext;
    $destDir = __DIR__ . '/../../public/uploads'; if (!is_dir($destDir)) { mkdir($destDir, 0775, true); }
    $dest = $destDir . '/' . $name; if (file_put_contents($dest, $data) === false) { throw new RuntimeException('No se pudo guardar la imagen.'); }
    return 'uploads/' . $name;
  }
}
