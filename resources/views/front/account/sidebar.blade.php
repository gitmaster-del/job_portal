<div class="card border-0 shadow mb-4 p-3">
    <div class="s-body text-center mt-3">
        @auth
        <div class="sidebar-profile">
            <h5>{{ Auth::user()->name }}</h5>
            <p class="text-muted">{{ Auth::user()->designation ?? 'No Designation' }}</p>
        </div>
        @endauth

        
    </div>
</div>

<div class="card account-nav border-0 shadow mb-4 mb-lg-0">
    <div class="card-body p-0">
        <ul class="list-group list-group-flush ">
            <li class="list-group-item d-flex justify-content-between p-3">
                <a href="{{ route('account.profile') }}">Account Settings</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <a href="{{route('account.createJob')}}">Post a Job</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <a href="{{route('account.myJob')}}">My Jobs</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <a href="{{route('account.myJobApplications')}}">Jobs Applied</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <a href="{{route('account.savedJobs')}}">Saved Jobs</a>
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

</script>
