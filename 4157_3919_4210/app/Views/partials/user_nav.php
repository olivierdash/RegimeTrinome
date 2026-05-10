<nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= site_url('dashboard') ?>">RegimeTrinome</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#userNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="userNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('dashboard/regimes') ?>">Regimes</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('dashboard/wallet') ?>">Wallet</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('dashboard/suivi') ?>">Suivi</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('dashboard/profil') ?>">Profil</a></li>
            </ul>
            <span class="navbar-text me-3"><?= esc(session()->get('user_name')) ?></span>
            <a class="btn btn-outline-secondary btn-sm" href="<?= site_url('logout') ?>">Deconnexion</a>
        </div>
    </div>
</nav>
