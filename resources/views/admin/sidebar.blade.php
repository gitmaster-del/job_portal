

<div class="card account-nav border-0 shadow mb-4 mb-lg-0">
    <div class="card-body p-0">
        <ul class="list-group list-group-flush ">
            <li class="list-group-item d-flex justify-content-between p-3">
                <a href="{{ route('admin.users') }}">Users</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <a href="{{ route('admin.jobs') }}">Jobs</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <a href="{{ route('admin.jobapplications') }}">Job Applications</a>
            </li>
            
            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <a href="{{ route('account.logout')}}">Logout</a>
            </li>
        </ul>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="profilePicForm" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Change Profile Picture</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="file" name="image" id="profileImage" class="form-control" accept="image/*" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Upload</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Success/Error -->
<div id="alertMsg" class="mt-2"></div>

<script>
$(document).ready(function(){
    $('#profilePicForm').submit(function(e){
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: "{{ route('account.updateProfilePic') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(res){
                $('#profileModal').modal('hide');
                $('#alertMsg').html('<div class="alert alert-success">'+res.message+'</div>');
                $('#profilePicPreview').attr('src', res.image);
                setTimeout(()=> $('#alertMsg').fadeOut(), 5000);
            },
            error: function(err){
                let errorMsg = err.responseJSON?.message || 'Something went wrong';
                $('#alertMsg').html('<div class="alert alert-danger">'+errorMsg+'</div>');
                setTimeout(()=> $('#alertMsg').fadeOut(), 5000);
            }
        });
    });
});
</script>
