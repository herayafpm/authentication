<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $page_title ?? 'Dashboard' ?> | <?= config('Auth')->appName ?></title>
    <link rel="shortcut icon" href="<?= base_url('auth_assets/img/logo.png') ?>" type="image/x-icon">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/fontawesome-free/css/all.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet"
        href="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('auth_assets/vendor/adminlte') ?>/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet"
        href="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/flag-icon-css/css/flag-icon.min.css">
    <?php if ($_with_datatable ?? false) : ?>
    <link rel="stylesheet"
        href="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet"
        href="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet"
        href="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <?php endif ?>
    <?= $this->renderSection('css') ?>
</head>

<body class="hold-transition light-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper" id="appVue">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="<?= base_url('auth_assets/img/logo.png') ?>"
                alt="<?= config('Auth')->appName ?>Logo" height="60" width="60">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="<?= main_url('') ?>" class="brand-link">
                <img src="<?= base_url('auth_assets/img/logo.png') ?>" alt="<?= config('Auth')->appName ?> Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light"><?= config('Auth')->appName ?></span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="<?=$_auth->user->user_photo?>"
                            class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?= $_auth->user->name ?></a>
                    </div>
                </div>
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent text-sm" data-widget="treeview"
                        role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="<?= main_url('dashboard') ?>"
                                class="nav-link <?=url_main_is('dashboard') || url_main_is('')?'active':''?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Beranda
                                </p>
                            </a>
                        </li>
                        <?php if (!empty($_masterDatas)) : ?>
                        <li class="nav-item <?=url_main_is('master*')?'menu-open':''?>">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    <?= lang('global.sidemenu.master_data.title_singular') ?>
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <?php
                                    foreach ($_masterDatas as $masterdata) :
                                        if(can($masterdata['permissions'])):
                                    ?>
                                <li class="nav-item">
                                    <a href="<?= main_url('master/' . $masterdata['url']) ?>"
                                        class="nav-link <?=url_main_is('master/'.$masterdata['url']."*")?'active':''?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p><?= lang("global.sidemenu.master_data.sub_menu." . $masterdata['url']) ?></p>
                                    </a>
                                </li>
                                <?php 
                                    endif;
                                    endforeach;
                                    ?>
                            </ul>
                        </li>
                        <?php endif ?>
                        <li class="nav-item">
                            <a href="<?= main_url('profile') ?>" class="nav-link <?=url_main_is('profile*')?'active':''?>">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    Profile
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('logout') ?>" class="nav-link bg-danger">
                                <i class="nav-icon fas fa-power-off"></i>
                                <p>
                                    Logout
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <h1 class="m-0"><?= $page_title ?? 'Dashboard' ?></h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="messageApi"></div>
                <?php $this->renderSection('content') ?>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; <?= date("Y") ?> <a
                    href="<?= main_url("") ?>"><?= config('Auth')->appName ?></a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>
    </div>
    <?php $this->renderSection('modal') ?>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script
        src="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js">
    </script>
    <!-- AdminLTE App -->
    <script src="<?= base_url('auth_assets/vendor/adminlte') ?>/dist/js/adminlte.js"></script>

    <!-- PAGE PLUGINS -->
    <!-- jQuery Mapael -->
    <script src="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
    <script src="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/raphael/raphael.min.js"></script>
    <script src="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="<?= base_url('auth_assets/vendor/adminlte/plugins/moment/moment-with-locales.min.js') ?>"></script>
    <?php if ($_with_datatable ?? false) : ?>
    <script src="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js">
    </script>
    <script
        src="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js">
    </script>
    <script
        src="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js">
    </script>
    <script src="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/datatables-buttons/js/dataTables.buttons.min.js">
    </script>
    <script src="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js">
    </script>
    <script src="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/jszip/jszip.min.js"></script>
    <script src="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="<?= base_url('auth_assets/vendor/adminlte') ?>/plugins/datatables-buttons/js/buttons.colVis.min.js">
    </script>
    <?php endif ?>
    <script>
        if (!String.prototype.format) {
            String.prototype.format = function () {
                var args = arguments;
                return this.replace(/{(\d+)}/g, function (match, number) {
                    return typeof args[number] != 'undefined' ?
                        args[number] :
                        match;
                });
            };
        }
    </script>
    <script>
        function toLocaleDate(date, format = 'LL') {
            moment.locale("id")
            return moment(date).format(format)
        }

        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }
    </script>
    <?php $this->renderSection('js') ?>
    <?php
    if ($menu_open ?? false) :
    ?>
    <script>
        document.querySelector('.menuMaster').classList.add('menu-open')
        document.querySelector('.menuMaster .menuMasterLink').classList.add('active')
    </script>
    <?php endif ?>
</body>

</html>