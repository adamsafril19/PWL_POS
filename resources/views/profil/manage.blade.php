<form action="{{ url('/profile/update') }}" method="POST" id="form-manage-profile">
    @csrf
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kelola Profil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" value="{{ auth()->user()->username }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" name="nama" id="nama" value="{{ auth()->user()->nama }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Password (Kosongkan jika tidak ingin mengganti)</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
$(document).ready(function() {
    $("#form-manage-profile").validate({
        rules: {
            username: { required: true },
            nama: { required: true }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#profile-modal').modal('hide');
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                        location.reload();
                    } else {
                        $('.error-text').text('');
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                    }
                }
            });
            return false;
        }
    });
});
</script>
