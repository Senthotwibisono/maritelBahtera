@extends('partial.main')

@section('content')

<div class="page-header">
    <h1>{{$title}}</h1>
</div>

<section>
    <div class="page-content">
        <div class="card">
            <div class="card-content">
                <div class="card-header">
                    <button type="button" class="btn btn-primary" onClick="addModal(this)"><i class="fas fa-plus"></i></button>
                </div>
                <div class="card-body">
                    <div class="table">
                        <table class="table-hover" id="tableVoyage">
                            <thead style="white-space: nowrap;">
                                <tr>
                                    <th>Ves Name</th>
                                    <th>Ves Code</th>
                                    <th>Voy</th>
                                    <th>Status</th>
                                    <th>Estimate Arrival</th>
                                    <th>Estimate Departure</th>
                                    <th>Arrival Date</th>
                                    <th>Departure Date</th>
                                    <th>Start Work Date</th>
                                    <th>Clossing Date</th>
                                    <th>Clossing Cargo Date</th>
                                    <th>User</th>
                                    <th>Created</th>
                                    <th>Edit</th>
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
            <div class="form-group">
                <label for="">Vessel</label>
                <select name="" id="master_id" class="selectSingle form-control" style="width: 100%;">
                    <option disabled selected value>Pilih Satu!</option>
                    @foreach($vessels as $ves)
                        <option value="{{$ves->id}}">{{$ves->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Voy No</label>
                <input type="text" class="form-control" id="voy">
                <input type="hidden" class="form-control" id="id">
            </div>
            <div class="form-group">
                <label for="">Estimate Arrival Date</label>
                <input type="datetime-local" class="form-control" id="eta">
            </div>
            <div class="form-group">
                <label for="">Estimate Departure Date</label>
                <input type="datetime-local" class="form-control" id="etd">
            </div>
            <div class="form-group">
                <label for="">Arrival Date</label>
                <input type="datetime-local" class="form-control" id="arrival_date">
            </div>
            <div class="form-group">
                <label for="">Departure Date</label>
                <input type="datetime-local" class="form-control" id="departure_date">
            </div>
            <div class="form-group">
                <label for="">Start Work Date</label>
                <input type="datetime-local" class="form-control" id="start_work_date">
            </div>
            <div class="form-group">
                <label for="">Clossing Date</label>
                <input type="datetime-local" class="form-control" id="clossing_date">
            </div>
            <div class="form-group">
                <label for="">Cargo Clossing Date</label>
                <input type="datetime-local" class="form-control" id="cargo_clossing_date">
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

@endsection

@section('custom_js')
<script>
    $(document).ready(function() {
        $('#tableVoyage').dataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: '50vh',
            ajax: '{{route('voyage.data')}}',
            columns: [
                {name: 'name', data: 'name', className: 'text-center'},
                {name: 'code', data: 'code', className: 'text-center'},
                {name: 'voy_no', data: 'voy_no', className: 'text-center'},
                {name: 'status', data: 'status', className: 'text-center'},
                {name: 'eta', data: 'eta', className: 'text-center'},
                {name: 'etd', data: 'etd', className: 'text-center'},
                {name: 'arrival_date', data: 'arrival_date', className: 'text-center'},
                {name: 'departure_date', data: 'departure_date', className: 'text-center'},
                {name: 'start_work_date', data: 'start_work_date', className: 'text-center'},
                {name: 'clossing_date', data: 'clossing_date', className: 'text-center'},
                {name: 'cargo_clossing_date', data: 'cargo_clossing_date', className: 'text-center'},
                {name: 'user', data: 'user', className: 'text-center'},
                {name: 'create_at', data: 'create_at', className: 'text-center'},
                {name: 'edit', data: 'edit', className: 'text-center'},
                {
                    data: null,
                    name: 'sort_order',
                    visible: false,
                    render: function (data, type, row) {
                        const today = new Date().toISOString().slice(0,10); // YYYY-MM-DD
                        const arrival = row.arrival_date ? row.arrival_date.slice(0,10) : null;
                        const departure = row.departure_date ? row.departure_date.slice(0,10) : null;

                        if (arrival && arrival > today) {
                            return 1; // belum datang
                        } else if (arrival && arrival <= today && (!departure || departure > today)) {
                            return 2; // sudah arrival tapi belum depart
                        } else if (departure && departure <= today) {
                            return 3; // sudah departure
                        }
                        return 4; // default
                    }
                }
            ],
            order: [[14, 'asc']]
        });
    });
</script>

<script>
    async function addModal(button) {
        buttonLoading(button);
        $('#master_id').val(null).trigger('change');
        $('#voy').val(null).trigger('change');
        $('#id').val(null).trigger('change');
        $('#eta').val(null).trigger('change');
        $('#etd').val(null).trigger('change');
        $('#arrival_date').val(null).trigger('change');
        $('#departure_date').val(null).trigger('change');
        $('#start_work_date').val(null).trigger('change');
        $('#clossing_date').val(null).trigger('change');
        $('#cargo_clossing_date').val(null).trigger('change');
        $('#addManual').modal('show');
        hideButton(button);
    }

    async function submitVessel(button) {
        const result = await confirmation();
        if (result.isConfirmed) {
            buttonLoading(button);
            const master_id = document.getElementById('master_id').value;
            const voy_no = document.getElementById('voy').value;
            const id = document.getElementById('id').value;
            const eta = document.getElementById('eta').value;
            const etd = document.getElementById('etd').value;
            const arrival_date = document.getElementById('arrival_date').value;
            const departure_date = document.getElementById('departure_date').value;
            const start_work_date = document.getElementById('start_work_date').value;
            const clossing_date = document.getElementById('clossing_date').value;
            const cargo_clossing_date = document.getElementById('cargo_clossing_date').value;

            const data = {
                master_id,
                voy_no,
                id,
                eta,
                etd,
                arrival_date,
                departure_date,
                start_work_date,
                clossing_date,
                cargo_clossing_date,
            };

            const url = '{{route('voyage.post')}}'
            const response = await globalResponse(data, url);
            hideButton(button);
            if (response.ok) {
                const hasil = await response.json();
                if (hasil.success) {
                    $('#tableVoyage').DataTable().ajax.reload();
                    $('#master_id').val(null).trigger('change');
                    $('#voy').val(null).trigger('change');
                    $('#id').val(null).trigger('change');
                    $('#eta').val(null).trigger('change');
                    $('#etd').val(null).trigger('change');
                    $('#arrival_date').val(null).trigger('change');
                    $('#departure_date').val(null).trigger('change');
                    $('#start_work_date').val(null).trigger('change');
                    $('#clossing_date').val(null).trigger('change');
                    $('#cargo_clossing_date').val(null).trigger('change');
                    $('#addManual').modal('hide');
                    successHasil(hasil);
                    return;
                }else{
                    errorHasil(hasil);
                    return;
                }
            }else{
                errorResponse(response);
                return;
            }
        }else{
            return;
        }
    }

    async function editVoy(button) {
        buttonLoading(button);
        const data = {
            id : button.dataset.id
        };

        const url = '{{route('voyage.edit')}}';
        const response = await globalResponse(data, url);
        hideButton(button);
        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                console.log(hasil.data);
                $('#master_id').val(hasil.data.master_id).trigger('change');
                $('#voy').val(hasil.data.voy_no);
                $('#id').val(hasil.data.id);
                $('#eta').val(hasil.data.eta);
                $('#etd').val(hasil.data.etd);
                $('#arrival_date').val(hasil.data.arrival_date);
                $('#departure_date').val(hasil.data.departure_date);
                $('#start_work_date').val(hasil.data.start_work_date);
                $('#clossing_date').val(hasil.data.clossing_date);
                $('#cargo_clossing_date').val(hasil.data.cargo_clossing_date);
                $('#addManual').modal('show');
            }else{
                errorHasil(hasil);
                return;
            }
        }else{
            errorResponse(response);
            return;
        }
    }
</script>
@endsection