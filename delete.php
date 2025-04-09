<?php
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    $file_path = __DIR__ . '/' . $file;

    if (file_exists($file_path)) {
        unlink($file_path);
        echo "File telah dihapus.<a href='filemanager.php'>kembali</a>";
    } else {
        echo "File tidak ditemukan.<a href='filemanager.php'>kembali</a>";
    }
}
?>
