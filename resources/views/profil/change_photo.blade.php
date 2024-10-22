<form action="{{ url('/profile/update-photo') }}" method="POST" enctype="multipart/form-data" id="form-change-photo">
    @csrf
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Foto Profil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="file_pfp">Pilih Foto Profil</label>
                    <input type="file" name="file_pfp" id="file_pfp" class="form-control" required>
                    <small id="error-file_pfp" class="error-text form-text text-danger"></small>
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
    $("#form-change-photo").validate({
        rules: { file_pfp: { required: true, extension: "jpg|jpeg|png" } },
        submitHandler: function(form) {
            var formData = new FormData(form);
            $.ajax({
                url: form.action,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status) {
                        $('#profile-modal').modal('hide');
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                        $('#profile-pic').attr('src', response.newProfilePicturePath);
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
