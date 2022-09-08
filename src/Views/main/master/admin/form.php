<?php $this->extend("templates/main") ?>
<?php $this->section('css') ?>
<?php $this->endSection('css') ?>
<?php $this->section('content') ?>
<form method="post" id="formAccount">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-12">
                <div class="form-group">
                    <label for="username"><?= lang('cruds.account.fields.username') ?></label>
                    <input type="text" class="form-control" name="username" placeholder="<?= lang('global.form.fill', [lang('cruds.account.fields.username')]) ?>" value="<?= $admin->username ?>">
                    <div class="invalid-feedback errorApi errorApi_username"></div>
                </div>
            </div>
            <div class="col-md-12 col-12">
                <div class="form-group">
                    <label for="name"><?= lang('cruds.account.fields.name') ?></label>
                    <input type="text" class="form-control" name="name" placeholder="<?= lang('global.form.fill', [lang('cruds.account.fields.name')]) ?>" value="<?= $admin->name ?>">
                    <div class="invalid-feedback errorApi errorApi_name"></div>
                </div>
            </div>
            <div class="col-md-12 col-12">
                <div class="form-group">
                    <label for="email"><?= lang('cruds.account.fields.email') ?></label>
                    <input type="email" class="form-control" name="email" placeholder="<?= lang('global.form.fill', [lang('cruds.account.fields.email')]) ?>" value="<?= $admin->email ?>">
                    <div class="invalid-feedback errorApi errorApi_email"></div>
                </div>
            </div>
            <div class="col-md-12 col-12">
                <div class="form-group">
                    <label for="password"><?= lang('cruds.account.fields.password') ?></label>
                    <input type="password" class="form-control" name="password" placeholder="<?= !empty($admin->password) ? lang('global.form.fill', [lang('cruds.account.fields.password')]) . "(" . lang('global.form.optional') . ")" : lang('global.form.fill', [lang('cruds.account.fields.password')]) ?>" value="<?= empty($admin->password) ? '123456' : '' ?>">
                    <div class="invalid-feedback errorApi errorApi_password"></div>
                </div>
            </div>
            <div class="col-12 mb-5">
                <button type="submit" class="btn btn-primary btn-block buttonSubmit"><?= lang('global.form.' . (empty($admin->password) ? 'create' : 'edit')) ?></button>
                <button type="button" class="btn btn-primary btn-block loadingApi" disabled>
                    <div class="d-flex align-items-center">
                        <strong><?= lang('global.form.' . (empty($admin->password) ? 'creating' : 'editing')) ?></strong>
                        <div class="spinner-border ml-auto spinner-border-sm" role="status" aria-hidden="true"></div>
                    </div>
                </button>
            </div>
        </div>
    </div>
</form>
<?php $this->endSection('content') ?>
<?php $this->section('modal') ?>
<?php $this->endSection('modal') ?>
<?php $this->section('js') ?>
<script>
    $(document).ready(function() {
        $('.loadingApi').hide();
        $('#formAccount').submit(function(e) {
            e.preventDefault()
            $.ajax({
                method: "POST",
                url: "<?= $url_process ?>",
                data: $(this).serialize(),
                beforeSend: function(xhr) {
                    $('.messageApi').html('')
                    $('.errorApi').html('')
                    $(".is-invalid").removeClass('is-invalid')
                    $('.loadingApi').show();
                    $('.buttonSubmit').hide();
                },
                complete: function(res) {
                    if (res.status === 200) {
                        var data = res.responseJSON;
                        if (data.status) {
                            $('.messageApi').html('<div class="alert alert-success">' + data.message + '</div>')
                            setTimeout(() => {
                                window.location.href = "<?= $url_previous_page ?>";
                            }, 1000);
                        } else {
                            $('.messageApi').html('<div class="alert alert-danger">' + data.message + '</div>')
                        }
                    } else {
                        if (res.status === 400) {
                            $('.messageApi').html('<div class="alert alert-danger">' + res.responseJSON.message + '</div>')
                            var data = res.responseJSON.data
                            for (key in data) {
                                if (data.hasOwnProperty(key)) {
                                    $("[name=" + key + "]").addClass('is-invalid')
                                    $('.errorApi_' + key).html(data[key])
                                }
                            }
                        } else {
                            $('.messageApi').html('<div class="alert alert-danger"> Error ' + res.status + '</div>')
                        }
                    }
                    $('.loadingApi').hide();
                    $('.buttonSubmit').show();
                }
            });
        })
    })
</script>
<?php $this->endSection('js') ?>