<?php $this->extend("templates/auth"); ?>
<?php $this->section('content') ?>
<p class="login-box-msg">Login <?= config('Auth')->appName ?></p>
<form method="post" id="formLogin">
    <div class="messageApi"></div>
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="<?=lang('auth.'.config('Auth')->login_using)?>" name='<?=config('Auth')->login_using?>'>
        <div class="invalid-feedback errorApi errorApi_<?=config('Auth')->login_using?>">
        </div>
    </div>
    <div class="input-group mb-3">
        <input type="password" class="form-control" placeholder="Password" name="password">
        <div class="invalid-feedback errorApi errorApi_password">
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block buttonSubmit">Login</button>
            <button type="button" class="btn btn-primary btn-block loadingApi" disabled>
                <div class="d-flex align-items-center">
                    <strong>Login...</strong>
                    <div class="spinner-border ml-auto spinner-border-sm" role="status" aria-hidden="true"></div>
                </div>
            </button>
        </div>
        <!-- /.col -->
    </div>
</form>
<?php $this->endSection('content') ?>
<?php $this->section('js') ?>
<script>
    $(document).ready(function() {
        $('.loadingApi').hide();
        $('#formLogin').submit(function(e) {
            e.preventDefault()
            $.ajax({
                method: "POST",
                url: "<?= base_url('login') ?>",
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
                                window.location.href = data.data.redir;
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