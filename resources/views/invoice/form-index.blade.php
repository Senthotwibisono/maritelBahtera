@extends('partial.main')

@section('custom_styles')

@endsection

@section('content')

<div id="layoutProfile">
   
</div>

<section>
    <div class="card">
        <div class="card-content">
            <div class="card-header">
                <h4>Master Layout</h4>
            </div>
            <div class="card-body" id ="contentLayout">
                @include('invoice.form-content')
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="addMainModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <!-- <h5 class="modal-title" id="editUserModalLabel">Vessel Excels Modal</h5> -->
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="editUserForm">
          <div class="mb-3">
            <label for="user_name" class="form-label">Name</label>
            <input type="text" class="form-control" id="mainName">
            <input type="hidden" class="form-control" id="mainId">
          </div>
          <div class="mb-3">
            <label for="user_name" class="form-label">Currency Flag</label>
            <select  id="currency_flag" class="form-select">
                <option value="idr">IDR</option>
                <option value="usd">USD</option>
            </select>
          </div>
          <div class="row">
            <div class="col-auto">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" name="" id="notaItMain">
                <label for="">Nota It</label>
              </div>
            </div>
            <div class="col-auto">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" name="" id="notaAdminMain">
                <label for="">Nota Administrasi</label>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label for="">VAT (%)</label>
            <input type="number" name="" id="vatMain" class="form-control">
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" onClick="submitMain(this)" class="btn btn-primary">Save changes</button>
      </div>

    </div>
  </div>
</div>


<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <!-- <h5 class="modal-title" id="editUserModalLabel">Vessel Excels Modal</h5> -->
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="editUserForm">
          <div class="mb-3">
            <label for="user_name" class="form-label">Item</label>
            <select  id="mitem" class="form-select selectSingle" style="height: 150%; width:100%;">
              <option disabled selected value>Wajib Pilih Satu!!</option>
              @foreach($mitems as $item)
                <option value="{{$item->id}}">{{$item->name}}</option>
              @endforeach
            </select>
            <input type="hidden" class="form-control" id="mainId">
            <input type="hidden" class="form-control" id="itemId">
          </div>
          <div class="mb-3">
            <label for="unit" class="form-label">Unit (Satuan)</label>
            <input type="text" id="unitItem" class="form-control">
          </div>

          <div class="mb-3">
              <label for="">Formula</label>
              <textarea id="formulaItem" class="form-control required-field" cols="5" rows="5"></textarea>
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
              <label for="">Remark</label>
              <textarea id="remarkItem" class="form-control required-field" cols="5" rows="5"></textarea>
          </div>
          
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" onClick="submitItem(this)" class="btn btn-primary">Save changes</button>
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

@include('invoice.js')

@section('custom_js')

<script>
  $(document).ready(function() {

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
    });

    $('#mitem').on('change', function() {
      showLoading();
      const id = $(this).val();
      const data = {
        id
      };
      
      getDataItem(data);
    })

  });

  async function getDataItem(data) {
    const url = '{{ route("getData.masterItem") }}';
    console.log(data);
    const response = await globalResponse(data, url);
    hideLoading();
    if (response.ok) {
      const hasil = await response.json();
      if (hasil.success) {
        $('#addItemModal #unitItem').val(hasil.data.unit);
        $('#addItemModal #formulaItem').val(hasil.data.formula);
        $('#addItemModal #remarkItem').val(hasil.data.remark);
      }else{
        errorHasil(hasil);
        return;
      }
    }else{
      errorResponse(response);
      return;
    }
  }
  
    // Formula
    function addToFormula(value) {
        const input = document.getElementById('formulaItem');
        input.value += (input.value && !isOperator(value)) ? ' ' + value : value;
        checkFields();
    }

    function clearFormula() {
        document.getElementById('formulaItem').value = '';
        checkFields();
    }

    function isOperator(char) {
        return ['+', '-', '*', '/', '(', ')'].includes(char);
    }

    async function addItem(button) {
      buttonLoading(button);
      $('#addItemModal #mainId').val(button.dataset.main);
      $('#addItemModal').modal('show');
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

</script>

@endsection