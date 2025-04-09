<?php
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    $file_path = __DIR__ . '/' . $file;

    // Pastikan file ada sebelum menampilkan kontennya
    if (file_exists($file_path)) {
        $content = file_get_contents($file_path);
        echo htmlspecialchars($content); // Menampilkan konten file dengan HTML encoding untuk menghindari XSS
    } else {
        echo 'File tidak ditemukan.';
    }
}
?>
