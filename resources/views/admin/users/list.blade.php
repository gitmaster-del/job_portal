@extends('front.layouts.app')
@section('main')

<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-3">
                @include('admin.sidebar')
            </div>

            <div class="col-lg-9">
                @include('front.message')
                <div class="card border-0 shadow mb-4">
                    
              


      <div class="card border-0  mb-4 p-3">
                    <div class="card-body card-form">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fs-4 mb-1">Users</h3>
                            </div>
                           
                            
                        </div>
                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Mobile</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @if($users->isNotEmpty())
                                    @foreach($users as  $user)
                                         <tr class="active">
                                              <td>
                                            <div class="job-name fw-500">{{$user->id}}</div>
                                        </td>
                                        <td>
                                            <div class="job-name fw-500">{{$user->name}}</div>
                                        </td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->mobile}}</td>
                                       
                                        <td>
                                            <div class="action-dots ">
                                                <button href="#" class="btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">

                                                    <li><a class="dropdown-item" href="{{route('admin.users.edit', $user->id)}}"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a></li>

                                                    <li><a class="dropdown-item" href="#" onclick="deleteUser({{ $user->id}})" ><i class="fa fa-trash" aria-hidden="true"></i> Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                                
                            </table>
                        </div>

                        <div>
                            {{$users->links()}}
                        </div>
                    </div>
                </div>


              
               </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')

<script type="text/javascript">
function deleteUser(id) {
    if(confirm('Are you sure you want to delete this user?')) {
        $.ajax({
            url: "{{ route('admin.users.destroy') }}",
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}',
                id: id
            },
            success: function(response) {
                // Existing front.message use karo
                var msgDiv = $(".alert");
                if (msgDiv.length === 0) {
                    // Agar koi alert nahi hai to banayo
                    $('.col-lg-9').prepend('<div class="alert"></div>');
                    msgDiv = $(".alert");
                }

                msgDiv.removeClass('alert-success alert-danger d-none').html('');

                if(response.status) {
                    msgDiv.addClass('alert-success').html(response.message);
                    location.reload(); // Page refresh
                } else {
                    msgDiv.addClass('alert-danger').html(response.message);
                }

                setTimeout(function(){
                    msgDiv.addClass('d-none').html('');
                }, 5000);
            },
            error: function() {
                alert('Something went wrong');
            }
        });
    }
}
</script>
@endsection


