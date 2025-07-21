@extends('partial.main')
@section('content')

<section>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <h2>User List</h2>
                <div class="col-auto">
                    <button type="button" class="btn btn-success" onClick="createUser(this)">Create User</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover table-stripped" id="tableUser">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="editUserForm">
          <div class="mb-3">
            <label for="user_name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name">
            <input type="hidden" class="form-control" id="id">
          </div>
          <div class="mb-3">
            <label for="user_email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email">
          </div>
          <div class="mb-3">
            <label for="user_email" class="form-label">Role</label>
            <select id="role" class="selectSingle form-select" style="height: 150%; width:100%;">
                <option disabled selected value>Pilih Satu</option>
                @foreach($roles as $role)
                    <option value="{{$role->name}}">{{$role->name}}</option>
                @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="user_email" class="form-label">Password</label>
            <input type="password" class="form-control" id="password">
          </div>
          <!-- Tambahkan input lain sesuai kebutuhan -->
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" onClick="submitUserForm(this)" class="btn btn-primary">Save changes</button>
      </div>

    </div>
  </div>
</div>
@endsection
@section('custom_js')
@include('userSystem.user.js')
<script>
    $(document).ready(function() {
        $('#tableUser').dataTable({
            processing: true,
            serverSide: true,
            ajax: '{{route('user.indexData')}}',
            scrollY: '50vh',
            columns: [
                {name:'name', data:'name', className:'text-centrer'},
                {name:'email', data:'email', className:'text-centrer'},
                {name:'role', data:'role', className:'text-centrer'},
                {name:'edit', data:'edit', className:'text-centrer'},
                {name:'delete', data:'delete', className:'text-centrer'},
            ]
        })
    })
</script>

@endsection

