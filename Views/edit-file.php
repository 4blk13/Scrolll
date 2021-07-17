<?php
require_once 'thumbnail.php';
force_user_authentification();
$connection=$db->getPDO();


if (!empty($_POST)) {
    if ($connection && isset($_POST['json'])) {
        $json = json_decode($_POST['json']);
        if (empty($json[2])) {
            header('HTTP/1.0 400 Bad error');
            die();
        }
        $stmt2 = $connection->prepare("INSERT INTO files (url, title, format, extension) VALUES (?, ?, ?, ?)");
        $url = $json[0];
        $title = $json[1];
        $ext = pathinfo('../public/Assets/Uncategorized/'.$url, PATHINFO_EXTENSION);
        $mime = mime_content_type('../public/Assets/Uncategorized/'.$url);
        if (strstr($mime, "image/")) {
            $format = 'image';
        }
        elseif (strstr($mime, "video/")) {
            $format = 'video';
        }
        else {
            header('HTTP/1.0 400 Bad error');
            die();
        }
        $newUrl = uniqid();
        $stmt2->bind_param("ssss", $newUrl, $title, $format, $ext);
        if (!($stmt2->execute())) {
            header('HTTP/1.0 400 Bad error');
            die();
        }
        $stmt2->close();

        $stmt3 = $connection->prepare("INSERT INTO cat_files (file_id, cat_id) VALUES (?, ?)"); 
        $lastInsertedId = mysqli_insert_id($connection);
        $stmt3->bind_param("ii", $lastInsertedId, $id);
        foreach($json[2] as $id) {
            $stmt3->execute();
        }
        $stmt3->close();
        if ($format === 'image') {
            scaleImage('../public/Assets/Uncategorized/'.$url, '../public/Assets/Categorized/'.$newUrl.'_thumb'.'.'.$ext, 388, null, null, IMAGES_SCALE_AXIS_X);
        }
        rename('../public/Assets/Uncategorized/'.$url, '../public/Assets/Categorized/'.$newUrl.'.'.$ext);
    }
    die();
}

function nextFilename($filename) {
    $files = scandir("../public/Assets/Uncategorized");
    $key = array_search($filename, $files);
    if ($key + 1 < count($files)) {
        return $files[$key + 1];
    }
    return '';
}

if ($connection && isset($_GET['filename'])){
    if($stmt = $connection->prepare("SELECT id, name FROM category;")){
        $stmt->execute();
        $stmt->bind_result($id, $cat);
        ?>
        <nav class="navbar navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href=".">Scrolll</a>
                    <a href='logout.php' class="nav-link" style="color:white">Logout</a>
            </div>
        </nav>
        <div class="d-flex min-vh-100 justify-content-center align-items-center text-white" style="margin-top:-3%;">
            <div class="text-center" style="max-width: 50%;">
                <?php $filename = htmlspecialchars($_GET['filename']); $mime = mime_content_type('../public/Assets/Uncategorized/'.$filename); if(strstr($mime, "image/")): ?>
                    <img src="<?= 'Assets/Uncategorized/'.$filename ?>" height="400vh"><img>
                <?php elseif(strstr($mime, "video/")): ?>
                    <video src="Assets/Uncategorized/<?= $filename ?>" class="" height="400px" width="400px" playsinline autoplay muted loop></video>
                <?php endif ?>
                <h1 name="filename" style="word-break: break-all;"><?= $filename ?></h1>
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="text" class="form-control" style="text-align:center;" autofocus>
                </div>
                <div class="control-group">
					<label for="select-categories">Categories</label>
					<select multiple id="select-categories">
                    </select>
				</div>
                <?php $nextFilename = nextFilename($filename) ?>
                <button class="btn btn-primary mt-2" onclick="window.location.href = 'dashboard.php';">Back</button>
                <button class="btn btn-primary submit-btn mt-2" style="margin-left:1%" onclick="AjaxAndNextPage('<?= $nextFilename ?>')">Submit</button>
            </div>
        </div>
        <script>
            function AjaxAndNextPage(filename) {
                $.ajax({
                    type: 'POST',
                    url:  'edit-file.php',
                    data: {json : JSON.stringify(Array($('[name="filename"]')[0]['textContent'], $('[name="text"]').val(), $('#select-categories').val()))},
                    cache: false,
                    success: function() {
                            window.location.href = '/edit-file.php?filename=' + filename;
                    },
                    error: function(data) {
                        $('.navbar').after('<div class="bg-danger text-white text-center">Error</div>');
                    }
                });
            }
        </script>
        <?php
        $stmt->close();
    }
}
?>