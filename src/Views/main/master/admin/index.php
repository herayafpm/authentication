<?php $this->extend("templates/main") ?>
<?php $this->section('css') ?>
<?php $this->endSection('css') ?>
<?php $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <?php if(can('admin_create')):?>
                <a role="button" class="btn btn-sm btn-success" href="<?= $url_add ?>"><i class="fas fa-fw fa-plus"></i> <?=lang('global.create',[lang('cruds.admin.title_singular')])?></a>
                <?php endif?>
            </div>
            <div class="card-body">
                <table id="tableDatatable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center" width="10">No</th>
                            <th><?=lang('cruds.account.fields.username')?></th>
                            <th><?=lang('cruds.account.fields.name')?></th>
                            <th><?=lang('cruds.account.fields.email')?></th>
                            <th width="100"><?=lang('global.datatable.action')?></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-center" width="10">No</th>
                            <th><?=lang('cruds.account.fields.username')?></th>
                            <th><?=lang('cruds.account.fields.name')?></th>
                            <th><?=lang('cruds.account.fields.email')?></th>
                            <th width="100"><?=lang('global.datatable.action')?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection('content') ?>
<?php $this->section('modal') ?>
<?php $this->endSection('modal') ?>
<?php $this->section('js') ?>
<script>
    var table_datatable = null;
    var params = {};
    var list_data = []

    function reloadDatatable() {
        table_datatable.ajax.reload(function(json) {
            list_data = json.data
        })
    }

    async function deleteData(index) {
        var list = list_data[index]
        $.ajax({
            method: "POST",
            url: decodeURIComponent("<?= $url_delete ?>").format(list.id_encode),
            data: {},
            beforeSend: function(xhr) {
                $('.messageApi').html('')
            },
            complete: function(res) {
                if (res.status === 200) {
                    var data = res.responseJSON;
                    if (data.status) {
                        $('.messageApi').html('<div class="alert alert-success">' + data.message + '</div>')
                        reloadDatatable()
                    } else {
                        $('.messageApi').html('<div class="alert alert-danger">' + data.message + '</div>')
                    }
                } else {
                    if (res.status === 400) {
                        $('.messageApi').html('<div class="alert alert-danger">' + res.responseJSON.message + '</div>')
                    } else {
                        $('.messageApi').html('<div class="alert alert-danger"> Error ' + res.status + '</div>')
                    }
                }
            }
        });
    }
    async function purgeData(index) {
        var list = list_data[index]
        $.ajax({
            method: "POST",
            url: decodeURIComponent("<?= $url_purge ?>").format(list.id_encode),
            data: {},
            beforeSend: function(xhr) {
                $('.messageApi').html('')
            },
            complete: function(res) {
                if (res.status === 200) {
                    var data = res.responseJSON;
                    if (data.status) {
                        $('.messageApi').html('<div class="alert alert-success">' + data.message + '</div>')
                        reloadDatatable()
                    } else {
                        $('.messageApi').html('<div class="alert alert-danger">' + data.message + '</div>')
                    }
                } else {
                    if (res.status === 400) {
                        $('.messageApi').html('<div class="alert alert-danger">' + res.responseJSON.message + '</div>')
                    } else {
                        $('.messageApi').html('<div class="alert alert-danger"> Error ' + res.status + '</div>')
                    }
                }
            }
        });
    }
    async function restoreData(index) {
        var list = list_data[index]
        $.ajax({
            method: "POST",
            url: decodeURIComponent("<?= $url_restore ?>").format(list.id_encode),
            data: {},
            beforeSend: function(xhr) {
                $('.messageApi').html('')
            },
            complete: function(res) {
                if (res.status === 200) {
                    var data = res.responseJSON;
                    if (data.status) {
                        $('.messageApi').html('<div class="alert alert-success">' + data.message + '</div>')
                        reloadDatatable()
                    } else {
                        $('.messageApi').html('<div class="alert alert-danger">' + data.message + '</div>')
                    }
                } else {
                    if (res.status === 400) {
                        $('.messageApi').html('<div class="alert alert-danger">' + res.responseJSON.message + '</div>')
                    } else {
                        $('.messageApi').html('<div class="alert alert-danger"> Error ' + res.status + '</div>')
                    }
                }
            }
        });
    }

    $(document).ready(function() {
        var columns = [{
                "data": "no",
            },
            {
                "data": "username"
            },
            {
                "data": "name",
            },
            {
                "data": "email"
            },
            {
                "data": "id_encode",
                "render": function(dt, type, row, meta) { // Tampilkan kolom aksi
                    var html = '';
                    var url_edit = decodeURIComponent("<?= $url_edit ?>").format(row.id_encode);
                    html += `
                        <a role="button" class="btn btn-sm btn-primary" href="${url_edit}">
                            <i class="fas fa-fw fa-edit"></i>
                        </a>
                        `
                    <?php if (can('admin_delete')) : ?>
                        if (row.deleted_at === null) {
                            html += `
                            <a role="button" class="btn btn-sm btn-danger deleteData" data-index="${meta.row}">
                                <i class="fas fa-fw fa-trash"></i>
                            </a>
                            `
                        } else {
                            html += `
                            <a role="button" class="btn btn-sm btn-info restoreData" data-index="${meta.row}">
                                <i class="fas fa-fw fa-recycle"></i>
                            </a>
                            <a role="button" class="btn btn-sm btn-danger purgeData" data-index="${meta.row}">
                                <i class="fas fa-fw fa-trash"></i>
                            </a>
                            `
                        }
                    <?php else : ?>
                        html += `
                            <a role="button" class="btn btn-sm btn-danger deleteData" data-index="${meta.row}">
                                <i class="fas fa-fw fa-trash"></i>
                            </a>
                            `
                    <?php endif ?>
                    return html
                }
            },
        ];
        var orderDisableTargets = [0,4]
        
        table_datatable = $("#tableDatatable").DataTable({
            "responsive": true,
            "language": {
                "buttons": {
                    "pageLength": {
                        "_": "Tampilkan" + " %d " + "Baris" + " <i class='fas fa-fw fa-caret-down'></i>",
                        "-1": "Tampilkan Semua" + " <i class='fas fa-fw fa-caret-down'></i>"
                    }
                },
                "lengthMenu": "Tampilkan" + " _MENU_ " + "data" + " " + "per" + " " + "Halaman",
                "zeroRecords": "data" + " " + "tidak ditemukan",
                "info": "Tampilkan" + " " + "Halaman" + " _PAGE_ " + "dari" + " _PAGES_",
                "infoEmpty": "data" + " " + "kosong",
                "infoFiltered": "(" + "di" + "filter" + " " + "dari" + " _MAX_ " + "total" + " " + "data" + ")"
            },
            "dom": 'Bfrtip',
            "buttons": [
                "copy", "csv", "excel", "pdf", "print", "colvis", {
                    extend: "pageLength",
                    attr: {
                        "class": "btn btn-primary"
                    },
                }
            ],
            "searching": true,
            "processing": true,
            "serverSide": true,
            "ordering": true, // Set true agar bisa di sorting
            "order": [
                [2, 'desc']
            ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
            "autoWidth": false,
            "lengthMenu": [
                [10, 25, 50, -1],
                ['10 ' + "baris", '25 ' + "baris", '50 ' + "baris", "Tampilkan Semua"]
            ],
            "ajax": {
                "url": "<?= $url_datatable ?>", // URL file untuk proses select datanya
                "type": "POST",
                "data": function(d) {
                    return {
                        ...d,
                        ...params
                    }
                }
            },
            "initComplete": function(settings, json) {
                list_data = json.data;
            },
            'columnDefs': [{
                "targets": orderDisableTargets,
                "orderable": false
            }, {
                "targets": [0],
                "className": 'text-center'
            }],
            "columns": columns,
        });
        table_datatable.on('order.dt page.dt', function() {
            table_datatable.column(0, {
                order: 'applied',
                page: 'applied',
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        });
        $("#tableDatatable").on('click', '.deleteData', function() {
            var index = $(this).data('index')
            Swal.fire({
                title: "<?=lang('global.confirm.delete',[lang("cruds.admin.title_singular")])?>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: "Batal",
                confirmButtonText: "Ya",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteData(index)
                }
            })
        })
        $("#tableDatatable").on('click', '.restoreData', function() {
            var index = $(this).data('index')
            Swal.fire({
                title: "<?=lang('global.confirm.restore',[lang("cruds.admin.title_singular")])?>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: "Batal",
                confirmButtonText: "Ya",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    restoreData(index)
                }
            })
        })
        $("#tableDatatable").on('xhr.dt', function ( e, settings, json, xhr ) {
           list_data = json.data
        } )
        $("#tableDatatable").on('click', '.purgeData', function() {
            var index = $(this).data('index')
            Swal.fire({
                title: "<?=lang('global.confirm.purge',[lang("cruds.admin.title_singular")])?>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: "Batal",
                confirmButtonText: "Ya",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    purgeData(index)
                }
            })
        })
    })
</script>
<?php $this->endSection('js') ?>