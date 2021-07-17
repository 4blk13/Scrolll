<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href=".">Scrolll</a>
        <div id="search-container">
            <input class="form-control" type="search" placeholder="Type a category name" aria-label="Search" onkeyup="showResult(this.value)">
            <div id="livesearch"></div>
        </div>
        <?php if (is_authentified()): ?>
            <a href='dashboard.php' class="nav-link" style="color:white;position:absolute;left:90%">Dashboard</a>
            <a href='logout' class="nav-link" style="color:white">Logout</a>
        <?php else: ?>
            <a href='login' class="nav-link" style="color:white">Login</a>
    <?php endif ?>
    </div>
</nav>

<div class="selected-categories text-center"></div>
<div class="container-fluid">
    <div class="d-flex flex-row flex-wrap justify-content-center">
        <div id="column-1" class="d-flex flex-column">
        </div>
        <div id="column-2" class="d-flex flex-column">
        </div>
        <div id="column-3" class="d-flex flex-column">
        </div>
    </div>
</div>

<div id="modal" class="modal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
        <div class="modal-header bg-dark text-white">
            <h5 class="modal-title text-center w-100">Modal title</h5>
            <button type="button" class="btn-close modal-btn" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        </div>
        </div>
    </div>
</div>