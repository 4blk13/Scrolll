<?php

function is_authentified (): bool {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return !empty($_SESSION['auth']);
}

function force_user_authentification(): void {
    if (!is_authentified()) {
        header('Location: login.php');
    }
}

?>