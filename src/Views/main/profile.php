<?php $this->extend("templates/admin") ?>
<?php $this->section('css') ?>
<?php $this->endSection('css') ?>
<?php $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">

            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" style="width: 150px;height:150px" src="<?=$_auth->user->user_photo?>" alt="User profile picture">
                    </div>
                    <h3 class="profile-username text-center"><?=$_auth->user->name?></h3>
                </div>

            </div>

        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Profile</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="settings">
                            <form class="form-horizontal" method="POST" enctype="multipart/form-data">
                                <div class="form-group row">
                                    <label for="username" class="col-sm-3 col-form-label">Username</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="username" placeholder="Username" value="<?=$_auth->user->username?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="name" class="col-sm-3 col-form-label">Nama</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="name" placeholder="name" value="<?=$_auth->user->name?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-3 col-form-label">Email</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="email" placeholder="email" value="<?=$_auth->user->email?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="user_photo" class="col-sm-3 col-form-label">Foto Profile (upload untuk mengubah gambar)</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" id="user_photo" placeholder="user_photo" name="user_photo">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password" class="col-sm-3 col-form-label">Password (kosongi jika tidak ingin diubah)</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="offset-sm-10 col-sm-2 text-right">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

<?php $this->endSection('content') ?>
<?php $this->section('modal') ?>
<?php $this->endSection('modal') ?>
<?php $this->section('js') ?>
<?php $this->endSection('js') ?>