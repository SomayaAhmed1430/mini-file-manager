<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mini File Manager</title>
</head>
<body>
    <h1>Mini File Manager</h1>

    <!-- Form لإنشاء ملف جديد -->
    <form action="" method="post">
        <input type="text" name="new_file" placeholder="Enter file name">
        <button type="submit" name="create_file">Create File</button>
    </form>

    <br>

    <!-- Form لرفع ملف -->
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="uploaded_file">
        <button type="submit" name="upload">Upload File</button>
    </form>

    <br>

    <h2>Files in Directory:</h2>
    <table border="1">
        <tr>
            <th>File Name</th>
            <th>Size (bytes)</th>
            <th>Last Modified</th>
            <th>Actions</th>
        </tr>

        <?php
        $files = scandir(__DIR__);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $fileSize = filesize($file);
                $lastModified = date("Y-m-d H:i:s", filemtime($file));
                echo "<tr>
                        <td>$file</td>
                        <td>$fileSize</td>
                        <td>$lastModified</td>
                        <td>
                            <form style='display:inline;' method='post'>
                                <input type='hidden' name='delete_file' value='$file'>
                                <button type='submit'>Delete</button>
                            </form>
                            <form style='display:inline;' method='post'>
                                <input type='hidden' name='copy_file' value='$file'>
                                <input type='text' name='copy_target' placeholder='Copy to'>
                                <button type='submit'>Copy</button>
                            </form>
                        </td>
                      </tr>";
            }
        }
        ?>
    </table>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // 1. إنشاء ملف جديد
        if (isset($_POST['create_file']) && !empty($_POST['new_file'])) {
            $filename = $_POST['new_file'];
            if (file_put_contents($filename, "")) {
                echo "File '$filename' created successfully!<br>";
            } else {
                echo "Failed to create file.<br>";
            }
        }

        // 2. رفع ملف
        if (isset($_POST['upload']) && isset($_FILES['uploaded_file'])) {
            $uploaded_file = $_FILES['uploaded_file']['name'];
            $target_path = __DIR__ . "/" . basename($uploaded_file);

            if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $target_path)) {
                echo "File '$uploaded_file' uploaded successfully!<br>";
            } else {
                echo "Failed to upload file.<br>";
            }
        }

        // 3. حذف ملف
        if (isset($_POST['delete_file'])) {
            $fileToDelete = $_POST['delete_file'];
            if (unlink($fileToDelete)) {
                echo "File '$fileToDelete' deleted successfully!<br>";
            } else {
                echo "Failed to delete file.<br>";
            }
        }

        // 4. نسخ ملف
        if (isset($_POST['copy_file']) && !empty($_POST['copy_target'])) {
            $fileToCopy = $_POST['copy_file'];
            $copyTarget = $_POST['copy_target'];

            if (copy($fileToCopy, $copyTarget)) {
                echo "File '$fileToCopy' copied to '$copyTarget' successfully!<br>";
            } else {
                echo "Failed to copy file.<br>";
            }
        }
    }
    ?>
</body>
</html>
