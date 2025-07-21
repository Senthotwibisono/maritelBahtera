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
                            <button type="button" class="btn btn-success" onClick="excelModal(this)"><i class="fas fa-file"></i> Upload File</button>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" onClick="singleModal(this)"><i class="fas fa-plus"></i> Upload Single</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table">
                        <table class="table table-hover" id="tableKapal">
                            <thead>
                                <tr>
                                    <th class="text-cenmter" style="white-space: nowrap;">Name</th>
                                    <th class="text-cenmter" style="white-space: nowrap;">Code</th>
                                    <th class="text-cenmter" style="white-space: nowrap;">DWT</th>
                                    <th class="text-cenmter" style="white-space: nowrap;">GRT</th>
                                    <th class="text-cenmter" style="white-space: nowrap;">NRT</th>
                                    <th class="text-cenmter" style="white-space: nowrap;">LOA</th>
                                    <th class="text-cenmter" style="white-space: nowrap;">BREADTH</th>
                                    <th class="text-cenmter" style="white-space: nowrap;">Owner</th>
                                    <th class="text-cenmter" style="white-space: nowrap;">Country Flag</th>
                                    <th class="text-cenmter" style="white-space: nowrap;">Edit</th>
                                    <th class="text-cenmter" style="white-space: nowrap;">Delete</th>
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
        <h5 class="modal-title" id="editUserModalLabel">Vessel Modal</h5>
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
          <div class="row">
            <div class="col-4">
              <div class="mb-3">
                  <label for="">DWT</label>
                  <input type="number" id="dwt" step="0.0001" class="form-control">
              </div>
            </div>
            <div class="col-4">
              <div class="mb-3">
                  <label for="">NRT</label>
                  <input type="number" id="nrt" step="0.0001" class="form-control">
              </div>
            </div>
            <div class="col-4">
              <div class="mb-3">
                  <label for="">GRT</label>
                  <input type="number" id="grt" step="0.0001" class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-6">
              <div class="mb-3">
                  <label for="">LOA</label>
                  <input type="number" id="loa" step="0.0001" class="form-control">
              </div>
            </div>
            <div class="col-6">
              <div class="mb-3">
                  <label for="">BREADTH</label>
                  <input type="number" id="breadth" step="0.0001" class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-6">
              <div class="mb-3">
                <label for="" class="form-label">Country Flag</label>
                <select id="country" class="selectSingle form-select" style="height: 150%; width:100%;">
                    <option disabled selected value>Pilih Satu</option>
                    @foreach($countries as $country)
                      <option value="{{$country->id}}">{{$country->name}}</option>
                    @endforeach
                </select>
              </div>
            </div>
            <div class="col-6">
              <div class="mb-3">
                <label for="">Owner</label>
                <textarea name="" id="owner" class="form-control" cols="5" rows="5"></textarea>
              </div>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" onClick="submitVessel(this)" class="btn btn-primary">Save changes</button>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="addFile" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">Vessel Excels Modal</h5>
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

@include('master.master_kapal.js')
@section('custom_js')
<script>
    function singleModal(button) {
        buttonLoading(button);
        $('#addManual').modal('show');
        $('#addManual #name').val('');
        $('#addManual #id').val('');
        $('#addManual #code').val('');
        $('#addManual #dwt').val('');
        $('#addManual #grt').val('');
        $('#addManual #nrt').val('');
        $('#addManual #loa').val('');
        $('#addManual #breadth').val('');
        $('#addManual #owner').val('');
        $('#addManual #country').val('').trigger('change');
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

        $("#tableKapal").dataTable({
            serverSide: true,
            processing: true,
            ajax: '{{route('master.vessel.data')}}',
            scrollX: true,
            scrollY: '50vh',
            columns: [
                {data:'name', name:'name'},
                {data:'code', name:'code'},
                {data:'dwt', name:'dwt'},
                {data:'grt', name:'grt'},
                {data:'nrt', name:'nrt'},
                {data:'loa', name:'loa'},
                {data:'breadth', name:'breadth'},
                {data:'owner', name:'owner'},
                {data:'country', name:'country'},
                {data:'edit', name:'edit', orderable:false, searchable:false},
                {data:'delete', name:'delete', orderable:false, searchable:false},
            ],
        })
    })
</script>

@endsection