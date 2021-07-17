<?php
force_user_authentification();

if (!empty($_GET)) {
    $filename = rawurldecode($_GET['filename']);
    if (rename(__DIR__ . '/../public/Assets/Uncategorized/' . $filename, __DIR__ . '/../public/Assets/Deleted/'.$filename)) {
        header('Location:/dashboard.php?delete=1');
        exit();
    }
}
header('Location:/dashboard.php?delete=0');