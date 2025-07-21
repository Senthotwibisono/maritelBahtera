@extends('partial.main')

@section('custom_styles')

@endsection

@section('content')

    <div class="page-header">
        <h4>{{$title}}</h4>
    </div>
    <section>
        <div class="card">
            <div class="card-content">
                <div class="card-header">
                    <button type="button" class="button btn btn-outline-primary" onClick="openModal(this)"><i class="fas fa-plus"></i></button>
                </div>
                <div class="card-body">
                    <div class="table">
                        <table class="table table-hover table-stripped" id="tableItem">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Tarif Dasar</th>
                                    <th>Formula</th>
                                    <th>Basic Unit</th>
                                    <th>Remark</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>


<div class="modal fade" id="addManual" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">Item Modal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="editUserForm">
            <div class="mb-3">
              <label for="user_name" class="form-label">Name</label>
              <input type="text" class="form-control required-field" id="name">
              <input type="hidden" class="form-control" id="id">
            </div>
            <div class="mb-3">
              <label for="" class="form-label">Tarif Dasar</label>
              <input type="number" step="0.0001" class="form-control" id="tarif_dasar">
            </div>
            <!-- Formula -->
            <div class="mb-3">
                <label for="">Formula</label>
                <textarea id="formula" class="form-control required-field" cols="5" rows="5"></textarea>
            </div>

            <div class=" mb-3" role="group" id ="selectFormula">
                @foreach($variables as $variable)
               
                    <button type="button" class="btn btn-outline-secondary" onClick="addToFormula('{{$variable->key}}')">{{$variable->label}}</button>
                
                @endforeach
                <div class="col-auto">
                    <button type="button" class="btn btn-outline-primary" onClick="addVariable(this)">Add New Variable</button>
                </div>
            </div>

            <div class="btn-group mb-3" role="group">
                <button type="button" class="btn btn-outline-secondary" onClick="addToFormula('+')">+</button>
                <button type="button" class="btn btn-outline-secondary" onClick="addToFormula('-')">-</button>
                <button type="button" class="btn btn-outline-secondary" onClick="addToFormula('*')">*</button>
                <button type="button" class="btn btn-outline-secondary" onClick="addToFormula('/')">/</button>
                <button type="button" class="btn btn-outline-secondary" onClick="addToFormula('(')">(</button>
                <button type="button" class="btn btn-outline-secondary" onClick="addToFormula(')')">)</button>
            </div>

            <div class="btn-group" role="group">
                <button type="button" class="btn btn-danger" onClick="clearFormula()">Clear</button>
            </div>

            <div class="mb-3">
              <label for="" class="form-label">Basic Unit (Satuan)</label>
              <input type="text" class="form-control required-field" id="unit">
            </div>
            <div class="mb-3">
              <label for="">Remarks</label>
              <textarea id="remark" class="form-control required-field" cols="5" rows="5"></textarea>
            </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="saveButton" onClick="submitItem(this)" class="btn btn-primary saveButton">Save changes</button>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="addVariableModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">Variable Modal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="editUserForm">
            <div class="mb-3">
              <label for="user_name" class="form-label">Key</label>
              <input type="text" class="form-control" id="key">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Label</label>
                <input type="text" name="" class="form-control" id="label">
            </div>
            <div class="mb-3">
              <label for="" class="form-label">Sorce Table</label>
              <select name="" id="source_table" class="form-select">
                <option value="master_item">master_item</option>
                <option value="master_kapal">master_kapal</option>
                <option value="manual_input">manual_input</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="" class="form-label">Sorce Field</label>
              <select name="" id="source_field" class="form-select">
                
              </select>
            </div>
            <div class="mb-3">
              <label for="">Description</label>
              <textarea id="desc" class="form-control" cols="5" rows="5"></textarea>
            </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="saveButton" onClick="submitVariable(this)" class="btn btn-primary saveButton">Save changes</button>
      </div>

    </div>
  </div>
</div>


@endsection
@include('master.master_item.js');
@section('custom_js')
<script>
    function openModal(button) {
        buttonLoading(button);
        $('#addManual #name').val('');
        $('#addManual #id').val(null);
        $('#addManual #tarif_dasar').val('0,0000');
        $('#addManual #unit').val('');
        $('#addManual #remark').val('');
        $('#addManual #formula').val('');
        checkFields();
        $('#addManual').modal('show');
        hideButton(button);
    }

    function addVariable(button) {
        buttonLoading(button);
        $('#addVariableModal #key').val(null);
        $('#addVariableModal #label').val(null);
        $('#addVariableModal #source_table').val(null).trigger('change');
        $('#addVariableModal #source_field').val(null);
        $('#addVariableModal #desc').val(null);
        checkFields();
        $('#addVariableModal').modal('show');
        hideButton(button);
    }

    $(document).ready(function() {
        $('#tableItem').dataTable({
            serverSide: true,
            proccesing: true,
            scrollX: true,
            scrollY: '50vh',
            ajax: '{{route('master.item.data')}}',
            columns: [
                {data:'name', name:'name', className:'text-center'},
                {data:'tarif_dasar', name:'tarif_dasar', className:'text-center'},
                {data:'formula', name:'formula', className:'text-center'},
                {data:'unit', name:'unit', className:'text-center'},
                {data:'remark', name:'remark', className:'text-center'},
                {data:'edit', name:'edit', className:'text-center', orderable: false, searchable: false},
                {data:'delete', name:'delete', className:'text-center', orderable: false, searchable: false},
            ]
        });

        const tableFields = {
            master_item: ['tarif_dasar'],
            master_kapal: ['dwt', 'grt', 'nrt', 'loa', 'breadth'],
            manual_input: ['manual_input'] // bisa Anda sesuaikan
        };

        $('#source_table').on('change', function() {
            const selectedTable = $(this).val();
            const fieldSelect = $('#source_field');

            fieldSelect.empty(); // kosongkan isi sebelumnya

            if (tableFields[selectedTable]) {
                tableFields[selectedTable].forEach(field => {
                    fieldSelect.append(`<option value="${field}">${field}</option>`);
                });
            } else {
                fieldSelect.append(`<option disabled selected>Tidak ada field</option>`);
            }
        })
    });

    // Formula
    function addToFormula(value) {
        const input = document.getElementById('formula');
        input.value += (input.value && !isOperator(value)) ? ' ' + value : value;
        checkFields();
    }

    function clearFormula() {
        document.getElementById('formula').value = '';
        checkFields();
    }

    function isOperator(char) {
        return ['+', '-', '*', '/', '(', ')'].includes(char);
    }
</script>
@endsection