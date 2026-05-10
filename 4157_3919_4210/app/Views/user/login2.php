<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
</head>

<body>
    <div class="row">
        <div class="col-md-6">
            <form class="row g-3 needs-validation" action="<?= site_url('user/login') ?>" method="post" novalidate>
                <div class="col-md-4">
                    <label for="validationCustom01" class="form-label">Taille </label>
                    <input type="number" name="taille" id="validationCustom01" placeholder="Taille (en cm)">
                    <div class="invalid-feedback">
                        La taille est un nombre entier positif
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="validationCustom02" class="form-label">Poids</label>
                    <input type="number" class="form-control" id="validationCustom02">
                    <div class="invalid-feedback">
                        Le poids doit etre un nombre entier positif 
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Creer le compte</button>
                </div>
            </form>
        </div>
    </div>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>