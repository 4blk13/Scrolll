<?php
require_once 'auth.php';
require_once 'thumbnail.php';
force_user_authentification();
$connection=$db->getPDO();

if(isset($_GET['add'])) {
    if ($_GET['add'] === '1') {
        echo '<div class="bg-sucess text-white text-center">Category added</div>';
    }
    else {
        echo '<div class="bg-danger text-white text-center">Error</div>';
    }
}

function resizeImage($url) {
    $img = file_get_contents($url);
    $im = imagecreatefromstring($img);
    $width = imagesx($im);
    $height = imagesy($im);
    $newwidth = '200';
    $newheight = '200';
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    return $thumb;
}

if (isset($_POST['json'])) {
    $json = json_decode($_POST['json']);
    $name = $json[0];
    $url = $json[1];
    $newUrl = 'Categories_thumb/'. $name .'.jpg';

    if (!($img = file_get_contents($url))) {
        header('HTTP/1.0 400 Bad error');
        die();
    }
    require_once 'thumbnailCat.php';
    if(!(imagejpeg($thumb, __DIR__ . '/../public/Assets/' . $newUrl, 100))) {
        header('HTTP/1.0 400 Bad error');
        die();
    }
    unlink($url);
    imagedestroy($thumb);
    imagedestroy($im);

    $stmt2 = $connection->prepare("INSERT INTO category (name, thumbnail) VALUES (?, ?)");
    $stmt2->bind_param("ss", $name , $newUrl);
    if(!$stmt2->execute()) {
        header('HTTP/1.0 400 Bad error');
        die();
    }
    $stmt2->close();
}

?>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Scrolll</a>
            <a href='logout.php' class="nav-link" style="color:white">Logout</a>
    </div>
</nav>
<div class="d-flex justify-content-center text-white">
    <div class="text-center">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="Name" class="form-control" style="text-align:center;" autofocus>
        </div>
        <div class="form-group">
            <label>Url</label>
            <input type="text" name="Url" class="form-control" style="text-align:center;">
        </div>
        <button class="btn btn-primary back-btn mt-2" onclick="window.location.href = '/dashboard.php';">Back</button><button class="btn btn-primary preview-btn mt-2">Preview</button><button class="btn btn-primary submit-btn mt-2" style="margin-left:1%">Submit</button>
        <img height=200 width=200 class="img-fluid" src=""></img>
    </div>
</div>
<table class="table text-white">
    <thead>
        <th>Image</th>
        <th>Name</th>
    </thead>
    <tbody>
        <?php
        $stmt = $connection->query('SELECT name, thumbnail FROM category ORDER BY id DESC');
        $res = $stmt->fetch_all();
        foreach ($res as $cat): ?>
        <tr>
            <td><img height="200px" width="200px" src="<?= '/Assets/' . $cat[1] ?>"></img></td>
            <td><?= $cat[0] ?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>
<script>
    $('.preview-btn').click(function() {
        $('.img-fluid').attr("src", '/thumbnailCat.php/url/' + $('input[name="Url"').val());
    });

    $('.submit-btn').click(function() {
        $.ajax({
            type: 'POST',
            url:  'add-categories.php',
            data: {json : JSON.stringify(Array($('input[name="Name"]').val(), $('input[name="Url"').val()))},
            cache: false,
            success: function() {
                window.location.pathname = 'add-categories.php/add/1';
            },
            error: function() {
                window.location.pathname = 'add-categories.php/add/0';
            }
        });
    });
</script>