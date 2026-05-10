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
                    <label for="validationCustom01" class="form-label">Nom </label>
                    <input type="text" name="nom" class="form-control" id="validationCustom01" placeholder="Mark">
                    <div class="invalid-feedback">
                        Veuillez entrer votre nom 
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="validationCustom02" class="form-label">Email</label>
                    <input type="email" name="email"  class="form-control" id="validationCustom02" placeholder="Otto@gmail.com">
                    <div class="invalid-feedback">
                        Entrer un email non vide et dans un format valide 
                    </div>
                </div>
                <div class="form-check col-md-6">
                    <label for="validationCustom03" class="form-label">Homme</label>
                    <input type="radio" class="form-control" name="genre" id="validationCustom03" value="1">
                </div>
                <div class="form-check col-md-6">
                    <label for="validationCustom04" class="form-label">Femme</label>
                    <input type="radio" class="form-control" name="genre" id="validationCustom04" value="2">
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