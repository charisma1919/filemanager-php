<?php
// Mengambil path lengkap dan IP server
//step
$server_ip = $_SERVER['SERVER_ADDR'];
// Folder uploads di dalam direktori yang sama dengan file PHP
//step
$path = __DIR__ . '/'; 

// Pastikan folder 'uploads' ada dan dapat diakses
//step
if (!is_dir($path)) {
    die('Folder uploads tidak ditemukan!');
}

// Menangani unggahan file
//step
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file_upload'])) {
    // Mengambil informasi file yang diunggah
	//step
    $file = $_FILES['file_upload'];
    $upload_dir = $path . '/';

    // Menentukan nama file yang akan disimpan (misalnya dengan nama asli)
	//step
    $upload_file = $upload_dir . basename($file['name']);
    
    // Memeriksa apakah file diunggah dengan benar
	//step
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Memindahkan file ke folder tujuan
		//step
        if (move_uploaded_file($file['tmp_name'], $upload_file)) {
            echo "File berhasil diunggah!";
        } else {
            echo "Terjadi kesalahan saat memindahkan file.";
        }
    } else {
        echo "Terjadi kesalahan saat mengunggah file: " . $file['error'];
    }
}

// Mengambil semua file dalam folder uploads
//step
$files = scandir($path);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .file-list {
            margin-top: 20px;
        }
        .file-item {
            margin-bottom: 10px;
        }
        .file-item button {
            margin-right: 5px;
        }
        h1 {
            text-align: center;
        }
        .upload-form {
            text-align: center;
            margin-bottom: 20px;
        }
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-height: 500px;
            overflow-y: auto;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h1>Path: <?= $path . ' | IP Server: ' . $server_ip ?></h1>

<!-- Form untuk mengunggah file -->
<!-- STEP-->
<div class="upload-form">
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="file_upload" required>
        <button type="submit">Unggah File</button>
    </form>
</div>

<!-- Menampilkan daftar file -->
<!-- STEP-->
<div class="file-list">
    <?php
    // Memastikan ada file di dalam folder
    if (count($files) <= 2) { // . dan ..
        echo "Tidak ada file di dalam folder uploads.";
    } else {
        foreach ($files as $file) {
            // Mengabaikan direktori . dan ..
            if ($file === '.' || $file === '..') {
                continue;
            }

            $file_path = $path . '/' . $file;
            $file_extension = pathinfo($file_path, PATHINFO_EXTENSION);
            ?>

            <div class="file-item">
                <strong><?= $file ?></strong> (<?= $file_extension ?>)

                <!-- Tombol-tombol untuk setiap file -->
                <a href="download.php?file=<?= urlencode($file) ?>"><button>Download</button></a>
                <button onclick="showPreviewSource('<?= urlencode($file) ?>')">Preview Source</button>
                <a href="delete.php?file=<?= urlencode($file) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus file ini?')"><button>Delete</button></a>
            </div>

            <?php
        }
    }
    ?>
</div>

<!-- Modul untuk preview source -->
<!-- STEP-->
<div id="previewModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <pre id="fileContent"></pre>
    </div>
</div>

<script>
// Menampilkan modal untuk preview source
function showPreviewSource(file) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'preview.php?file=' + file, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById('fileContent').textContent = xhr.responseText;
            document.getElementById('previewModal').style.display = 'block';
        } else {
            alert('Terjadi kesalahan saat mengambil isi file.');
        }
    };
    xhr.send();
}

// Menutup modal
function closeModal() {
    document.getElementById('previewModal').style.display = 'none';
}
</script>

</body>
</html>
