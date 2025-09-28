<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-3"><?= $title ?></h1>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <form id="profileForm">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap:</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>                        
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                        </div>                        
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">Role:</label>
                                <input type="text" class="form-control" id="role" name="role" readonly>
                            </div>
                        </div>  
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="password_change" class="form-label">Ganti Password:</label>
                                <input type="checkbox" class="form-check-input" id="password_change" name="password_change">
                                <input type="password" class="form-control" id="password" name="password" readonly hidden>
                                <input type="hidden" name="is_active" id="is_active">
                            </div>
                        </div>
                        <script>
                            $('#password_change').on('change', function () {
                                if (this.checked) {
                                    $('#password').removeAttr('readonly hidden');
                                    $('#saveProfileBtn').removeAttr('disabled');
                                } else {
                                    $('#password').attr({'readonly': true, 'hidden': true});
                                }
                            });

                            $('input, select, textarea').on('input', function(){
                                $('#saveProfileBtn').removeAttr('disabled');
                            });
                        </script>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary" id="saveProfileBtn" disabled><i class="fas fa-save"></i> Simpan</button>
                </div>
            </div>
        </form>        
    </div>
</div>
<script>
    $(() => {
        getData();
        $('#profileForm').on('submit', function (e) {
            e.preventDefault();
            saveData();
        });
    });

    function getData(){
        let id = `<?= session('user_id') ?>`;
        $.ajax({
            url: `<?= base_url('api/users/') ?>${id}`,
            method: 'GET',
            dataType: 'JSON',
            beforeSend: function () {
                Swal.fire({
                    title: 'Loading...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function (response) {
                Swal.close();
                let data = response.data;
                $('#nama').val(data.nama);
                $('#email').val(data.email);
                $('#username').val(data.username);
                $('#role').val(data.role);
                $('#role_id').val(data.role_id);
                $('#is_active').val(data.is_active);
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    }

    function saveData(){
        let id = `<?= session('user_id') ?>`;
        let formData = $('#profileForm').serialize();
        $.ajax({
            url: `<?= site_url('api/users/') ?>${id}`,
            method: 'PUT',
            dataType: 'JSON',
            data: formData,
            beforeSend: function () {
                Swal.fire({
                    title: 'Loading...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function (response) {
                console.log(response);
                Swal.close();
                if(response.status){
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    }
</script>