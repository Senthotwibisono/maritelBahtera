@extends('partial.main')

@section('content')

<section>
    <div class="page-header">
        <h4>{{$title}}</h4>
    </div>
    <div class="content">
        <div class="card">
            <div class="card-content">
                <div class="card-header">
                    <div class="row">
                        <div class="col-auto">
                            <button type="button" class="btn btn-success" onClick="excelModal(this)" disabled><i class="fas fa-file"></i> Upload File</button>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" onClick="singleModal(this)"><i class="fas fa-plus"></i> Upload Single</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table">
                        <table class="table table-hover" id="tableCountry">
                            <thead>
                                <tr>
                                    <th class="text-cenmter" style="white-space: nowrap;">Country Code</th>
                                    <th class="text-cenmter" style="white-space: nowrap;">Country Name</th>
                                    <th class="text-cenmter" style="white-space: nowrap;">Edit</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="addManual" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">Country Modal</h5>
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
            <label for="" class="form-label">Code</label>
            <input type="code" class="form-control" id="code">
          </div>
          <!-- Tambahkan input lain sesuai kebutuhan -->
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" onClick="submitPort(this)" class="btn btn-primary">Save changes</button>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="addFile" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">Ports Excels Modal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="editUserForm">
          <div class="mb-3">
            <label for="user_name" class="form-label"></label>
            <input type="file" class="dropify" id="file" accept=".xlsx,.xls">
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" onClick="submitFile(this)" class="btn btn-primary">Save changes</button>
      </div>

    </div>
  </div>
</div>
@endsection

@section('custom_js')
@include('master.master_country.js')
<script>
    function singleModal(button) {
        buttonLoading(button);
        $('#addManual').modal('show');
        $('#addManual #name').val('');
        $('#addManual #id').val('');
        $('#addManual #code').val('');
        hideButton(button);
    }
    function excelModal(button) {
      buttonLoading(button);
      $('#addFile').modal('show');
      hideButton(button);
    }
</script>
<script>
    $(document).ready(function() {

        $('.dropify').dropify({
            messages: {
                'default': 'Drag and drop a file here or click',
                'replace': 'Drag and drop or click to replace',
                'remove':  'Remove',
                'error':   'Ooops, something wrong happended.'
            }
        });

        $("#tableCountry").dataTable({
            serverSide: true,
            processing: true,
            ajax: '{{route('master.country.data')}}',
            scrollX: true,
            scrollY: '50vh',
            columns: [
                {data:'name', name:'name'},
                {data:'code', name:'code'},
                {data:'edit', name:'edit', orderable:false, searchable:false},
            ],
        })

    })
</script>

@endsection