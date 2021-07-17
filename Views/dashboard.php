<?php
force_user_authentification();

?>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href=".">Scrolll</a>
            <a href='logout.php' class="nav-link" style="color:white">Logout</a>
    </div>
</nav>

<?php 

if(isset($_GET['delete'])) {
    if ($_GET['delete'] === '1') {
        echo '<div class="bg-warning text-white text-center">File deleted</div>';
    }
    else {
        echo '<div class="bg-danger text-white text-center">Error</div>';
    }
}

if(isset($_GET['edit'])) {
    if ($_GET['edit'] === '1') {
        echo '<div class="bg-success text-white text-center">File added</div>';
    }
    else {
        echo '<div class="bg-danger text-white text-center">Error</div>';
    }
}
?>

<a class="btn btn-primary" href="add-categories.php">Add categories</a>

<table class="table text-white">
    <thead>
        <th>Image</th>
        <th>Filename</th>
        <th>Actions</th>
    </thead>
    <tbody>
        <?php
            $currentPage = (int)($_GET['page'] ?? 0);
            $arrayFilename = scandir('Assets/Uncategorized');
            $arrayFilename = array_splice($arrayFilename, 2);
            for ($i = $currentPage * 9; $i < $currentPage * 9 + 9 && $i < count($arrayFilename); $i++) :?>
        <tr>
            <?php $filename = $arrayFilename[$i]; $mime = mime_content_type('Assets/Uncategorized/'.$filename); if(strstr($mime, "image/")): ?>
                <td><img src="Assets/Uncategorized/<?= $filename ?>" height="200px" width="200px"></td>
            <?php elseif(strstr($mime, "video/")): ?>
                <td><video src="Assets/Uncategorized/<?= $filename ?>" height="200px" width="200px" playsinline autoplay muted loop></video></td>
            <?php endif ?>
            <td style="word-break: break-all;"><?= $filename ?></td>
            <td><button class="btn btn-dark" onclick="document.location.href = 'edit-file.php?filename=<?=rawurlencode($arrayFilename[$i])?>'">Edit</button><button class="btn btn-dark" onclick="document.location.href = 'delete-file.php?filename=<?=rawurlencode($arrayFilename[$i])?>'">Delete</button></td>
        </tr>
        <?php endfor ?>
    </tbody>
</table>
<div class="text-center">
<?php if ($currentPage > 0) : ?><a class="btn btn-primary" href="dashboard.php?page=<?= $currentPage - 1 ?>">Previous</a><?php endif ?><a class="btn btn-primary" href="dashboard.php?page=<?= $currentPage + 1 ?>">Next</a>
</div>
<?php require_once 'footer.php'; ?>