<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $page_title ?> | <?= config('Auth')->appName ?></title>
    <link rel="shortcut icon" href="<?= base_url('auth_assets/img/logo.png') ?>" type="image/x-icon">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('auth_assets/vendor/adminlte') ?>/dist/css/adminlte.min.css">
    <?= $this->renderSection('css') ?>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="<?= base_url() ?>" class="h1"><?= config('Auth')->appName ?></a>
            </div>
            <div class="card-body">
                <?= $this->renderSection('content') ?>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url('auth_assets/vendor/adminlte') ?>/dist/js/adminlte.min.js"></script>
    <?= $this->renderSection('js') ?>
</body>

</html>