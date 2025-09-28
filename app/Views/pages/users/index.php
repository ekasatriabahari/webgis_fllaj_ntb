<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">User List</h5>
                <div class="card-tools">
                    <button class="btn btn-primary btn-sm" onclick="addUser()"><i class="fas fa-plus"></i> Add User</button>
                </div>
            </div>
            <div class="card-body">
                <table id="usersTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">No.</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th width="15%">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- modal User -->
 <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userForm">                        
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                                <input type="hidden" class="form-control" id="userId" name="id" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role_id">Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="">Pilih Role</option>
                                    <option value="admin">Administrator</option>
                                    <option value="surveyor">Surveyor</option>
                                    <option value="viewer">Viewer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_active">Status</label>
                                <select class="form-control" id="is_active" name="is_active" required>
                                    <option value="">Pilih Status</option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-divider mb-3"></div>
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" id="saveUserBtn">Simpan</button>
                            <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(() => {
        loadUsers();
        $('#userForm').on('submit', function(e) {
            e.preventDefault();
            saveUser();
        });
    });

    function loadUsers(){
        $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url('/api/users') ?>',
                type: 'GET'
            },
            columns: [
                { data: null, orderable: false, searchable: false, render: function(data, type, row, meta) {
                    return meta.row + 1;
                }},
                { data: 'nama' },
                { data: 'username' },
                { data: 'is_active', render: function(data) {
                    return data ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                }},
                { data: 'role', render: function(data) {
                    return `<span class="badge bg-info">${data}</span>`;
                }},
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<button class="btn btn-primary btn-sm" onclick="editUser(${row.id})"><i class="fas fa-edit"></i>Edit</button>
                                <button class="btn btn-warning btn-sm" onclick="resetPassword(${row.id})"><i class="fas fa-key"></i>Reset Password</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteUser(${row.id})"><i class="fas fa-trash"></i>Delete</button>`;
                    }
                }
            ],
            order: [[1, 'asc']],
            drawCallback: function(settings) {
                var api = this.api();
                api.column(0).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }
        });
    }

    function addUser() {
        $('#kec').on('change', function() {
            const kodeKec = $(this).val();
            getKel(kodeKec, null);
        });
        $('#userModalLabel').text('Add User');
        $('#userId').val('');
        $('#kode_kec').val('').removeAttr('readonly');
        $('#kode_kel').val('').removeAttr('readonly');
        $('#userForm')[0].reset();
        $('#userModal').modal('show');
    }

    function saveUser(){
        const id = $('#userId').val();
        const url = id ? `<?= site_url('/api/users/') ?>${id}` : '<?= site_url('/api/users') ?>';
        const method = id ? 'PUT' : 'POST';
        let formData = $('#userForm').serialize();
        $.ajax({
            url: url,
            type: method,
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#userModal').modal('hide');
                    $('#usersTable').DataTable().ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON.message
                });
            }
        });
    }

    function editUser(id) {
        $.ajax({
            url: `<?= site_url('/api/users/') ?>${id}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const user = response.data;
                    $('#userId').val(user.id);
                    $('#nama').val(user.nama);
                    $('#username').val(user.username);
                    $('#role').val(user.role);
                    $('#email').val(user.email);
                    $('#is_active').val(user.is_active ? 1 : 0);
                    $('#userModalLabel').text('Edit User');
                    $('#userModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON.message
                });
            }
        });
    }

    function deleteUser(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `<?= site_url('/api/users/') ?>${id}`,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            $('#usersTable').DataTable().ajax.reload();
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            );
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON.message
                        });
                    }
                });
            }
        });
    }

    function resetPassword(userId){
        $.ajax({
            url: `<?= site_url('/api/users/reset-password') ?>`,
            type: 'POST',
            data: { id: userId },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON.message
                });
            }
        });
    }
</script>