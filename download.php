<?php
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    $file_path = __DIR__ . '/' . $file;

    if (file_exists($file_path)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        readfile($file_path);
        exit;
    } else {
        echo "File tidak ditemukan.";
    }
}
?>
