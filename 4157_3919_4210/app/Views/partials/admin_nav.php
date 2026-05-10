<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= site_url('admin') ?>">Back-office</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="<?= site_url('admin') ?>">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('admin/regimes') ?>">Regimes</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('admin/activites') ?>">Activites</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('admin/objectifs') ?>">Objectifs</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('admin/codes') ?>">Codes</a></li>
            </ul>
            <span class="navbar-text me-3"><?= esc(session()->get('admin_name')) ?></span>
            <a class="btn btn-outline-light btn-sm" href="<?= site_url('admin/logout') ?>">Deconnexion</a>
        </div>
    </div>
</nav>
