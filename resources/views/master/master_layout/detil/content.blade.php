
<title>PT. Maritel Bahtera Abadi</title>
<style>
  .logo-container {
    display: flex;
    justify-content: center;
    margin-bottom: 4px;
  }
  .logo {
    width: 60px;
    height: 60px;
  }
  h1.header-font {
    font-family: 'Special Elite', cursive;
    font-size: 24px;
    color: #6DA544;
    text-align: center;
    margin: 0 0 4px 0;
    letter-spacing: 0.1em;
    font-weight: normal;
    line-height: 1.1;
  }
  p.address {
    font-weight: 800;
    font-size: 11px;
    text-align: center;
    margin: 0 0 4px 0;
    line-height: 1.1;
  }
  .red-bar {
    height: 3px;
    background-color: #c00;
    margin-bottom: 4px;
  }
  .green-bar {
    background-color: #3ad400;
    border: 2px solid black;
    color: black;
    font-weight: 700;
    font-size: 12px;
    text-align: center;
    padding: 2px 0;
    margin-bottom: 4px;
  }
  table {
    border-collapse: collapse;
    width: 100%;
    table-layout: fixed;
    margin-bottom: 8px;
  } 

  th, td {
    border: 1px solid black;
    padding: 2px 4px;
    vertical-align: top;
    overflow-wrap: break-word;
  } 

  th {
    font-weight: 700;
    text-align: center;
  } 

  td {
    font-weight: 400;
    text-align: left;
  } 

  * {
    box-sizing: border-box;
  }

  td.bold {
    font-weight: 700;
  }
  td.center {
    text-align: center;
  }
  td.right {
    text-align: right;
  }
  .section-header {
    background-color: #3ad400;
    font-weight: 700;
    color: black;
  }
  .total-row {
    background-color: #00a3e0;
    font-weight: 700;
    color: black;
  }
  .red-text {
    color: #c00;
  }
  .nowrap {
    white-space: nowrap;
  }
  .remarks-list {
    font-size: 9px;
    margin-top: 0;
    margin-bottom: 8px;
    padding-left: 20px;
  }
  .bank-details {
    font-size: 9px;
  }
  .bank-details strong {
    font-weight: 700;
  }
</style>
</head>
<body>
  <div class="container">
    <div class="logo-container">
      <img class="logo" src="https://storage.googleapis.com/a1aa/image/6f085b20-08fa-4904-ba48-e38eaeefe0f8.jpg" alt="Logo circle with green, red, blue arrows surrounding a flag with letter M and text Maritel Group" />
    </div>
    <h1 class="header-font">PT. MARITEL BAHTERA ABADI</h1>
    <p class="address">
      Mahakam Square Blok B. No.36, Jln Untung Suropati - Sungai Kunjang<br />
      Samarinda - East Kalimantan - Indonesia 75123
    </p>
    <div class="red-bar"></div>
    <div class="green-bar">ESTIMATED PORT DISBURSEMENT ACCOUNT</div>

    <table>
      <tbody>
        <tr>
          <td>PRINCIPAL</td>
          <td></td>
          <td>REFERENCE NO</td>
          <td><input type="text" value="-" class="form-control" readonly></td>
        </tr>
        <tr>
          <td>VESSEL NAME</td>
          <td class="bold"><input type="text" value="-" class="form-control" readonly></td>
          <td>CREATED DATED</td>
          <td><input type="text" value="-" class="form-control" readonly></td>
        </tr>
        <tr>
          <td>DWT</td>
          <td><input type="text" value="-" class="form-control" readonly></td>
          <td>PORT OF CALL</td>
          <td><input type="text" value="-" class="form-control" readonly></td>
        </tr>
        <tr>
          <td>GRT / NRT</td>
          <td>
            <div class="row">
                <div class="col-auto">
                    <label for="">GRT</label>
                    <input type="number" step="0.0001" name="" id="grt" class="form-control">
                </div>
                <div class="col-auto">
                    <label for="">NRT</label>
                    <input type="number" step="0.0001" name="" id="nrt" class="form-control">
                </div>
            </div>
          </td>
          <td>PURPOSES OF CALL</td>
          <td><input type="text" value="-" class="form-control" readonly></td>
        </tr>
        <tr>
          <td>LOA / BREADTH</td>
          <td>
            <div class="row">
                <div class="col-auto">
                    <label for="">LOA</label>
                    <input type="number" step="0.0001" name="" id="loa" class="form-control">
                </div>
                <div class="col-auto">
                    <label for="">BREADTH</label>
                    <input type="number" step="0.0001" name="" id="breadth" class="form-control">
                </div>
            </div>
          </td>
          <td>ACTIVITY</td>
          <td><input type="text" value="-" class="form-control" readonly></td>
        </tr>
        <tr>
          <td>FLAG</td>
          <td><input type="text" value="-" class="form-control" readonly></td>
          <td>CARGO</td>
          <td><input type="text" value="-" class="form-control" readonly></td>
        </tr>
        <tr>
          <td>VOYAGE NUMBER</td>
          <td><input type="text" value="-" class="form-control" readonly></td>
          <td>VOLUME</td>
          <td><input type="text" value="-" class="form-control" readonly></td>
        </tr>
        <tr>
          <td>EXCHANGE RATE</td>
          <td><input type="text" value="-" class="form-control" readonly></td>
          <td>EST PORT STAY</td>
          <td><input type="text" value="-" class="form-control" readonly></td>
        </tr>
      </tbody>
    </table>

    <table>
      <colgroup>
        <col style="width: 30px;">
        <col style="width: 100px;">
        <col style="width: 260px;">
        <col style="width: 70px;">
        <col style="width: 70px;">
        <col style="width: 140px;">
        <col style="width: 40px;">
      </colgroup>
      <thead>
        <tr class="section-header">
          <th>NO</th>
          <th>DESCRIPTION</th>
          <th>BASIS OF CALCULATION<br />(RATE / TARIFF)</th>
          <th>AMOUNT<br />IDR</th>
          <th>AMOUNT<br />USD</th>
          <th>REMARKS</th>
          <th>Action</th>
        </tr>
        <tr>
          <th style="border: 0px;">
            <button type="button" class="btn btn-outline-primary" onClick="addMain(this)">
              <i class="fas fa-plus"></i>
            </button>
          </th>
        </tr>
      </thead>
    </table>
    
    @php
      function getLetter($number) {
          $letter = '';
          while ($number > 0) {
              $number--;
              $letter = chr(65 + ($number % 26)) . $letter;
              $number = intdiv($number, 26);
          }
          return $letter;
      }
    @endphp
    <div id="main-container">
      @foreach($mains as $main)
        <table class="main-table" data-id="{{ $main->id }}" draggable="true">
          <colgroup>
            <col style="width: 30px;">
            <col style="width: 100px;">
            <col style="width: 260px;">
            <col style="width: 70px;">
            <col style="width: 70px;">
            <col style="width: 140px;">
            <col style="width: 40px;">
          </colgroup>
          <thead>
            <tr class="section-header">
              <th colspan="1"><strong>{{ getLetter($loop->iteration) }}</strong></th>
              <th colspan="4">{{$main->name}}</th>
              <th colspan="2"> 
                <div class="button-container">
                  <button type="button" class="btn btn-warning" data-id="{{$main->id}}" onClick="editMain(this)">
                    <i class="fas fa-pencil"></i>
                  </button>
                  <button type="button" class="btn btn-danger" data-id="{{$main->id}}" onClick="deleteMain(this)">
                    <i class="fas fa-trash"></i>
                  </button>
                  <button type="button" class="btn btn-info" data-main="{{$main->id}}" onClick="addItem(this)"><i class="fas fa-plus"></i> Tambah Detil</button>
                </div>
              </th>
            </tr>
          </thead>
          <tbody class="inner-sortable">
            @foreach($items as $item)
              @if($item->layout_main_id == $main->id)
                @if (!in_array($item->name, ['VAT', 'IT Administration', 'Nota Administration']))
                <tr class="row-detil group-{{ $item->id }}" data-id="{{ $item->id }}" draggable="true">
                  <td rowspan="2">{{$item->order}}</td>
                  <td rowspan="2">{{ $item->name }}</td>
                  @php
                    // Baris atas: label
                    $label = $item->formula;
                    $itemDetails = $detils->where('layout_item_id', $item->id);
                    $variables = array_filter(array_map('trim', preg_split('/\W+/', $label)));                  

                    foreach ($variables as $var) {
                        $value = optional($itemDetails->firstWhere('key', $var))->label ?? $var;
                        $label = preg_replace('/\b' . preg_quote($var, '/') . '\b/', $value, $label);
                    }                 

                    // Baris bawah: input sejajar
                    $inputFormula = $item->formula;
                    foreach ($variables as $var) {
                        $detail = $itemDetails->firstWhere('key', $var);
                        $value = optional($detail)->amount ?? 0;                  

                        // Ganti variabel dengan input HTML
                        $inputTag = '<input type="number" step="any" id="detilAmount" data-main="'.$main->id.'" data-item="'.$item->id.'" data-detil="'.$detail->id.'" value="'.$value.'" class="form-control form-control-sm d-inline-block" style="width: 100px;">';                  

                        $inputFormula = preg_replace('/\b' . preg_quote($var, '/') . '\b/', $inputTag, $inputFormula);
                    }
                  @endphp
                  <td rowspan="">{{ $label }}</td>
                  <td rowspan="2">{{$item->amount}}</td>
                  <td rowspan="2">{{$item->amount}}</td>
                  <td rowspan="2">{{$item->remark}}</td>
                  <td rowspan="2">
                    <div class="col-auto">
                      <!-- <button type="button" class="btn btn-warning" data-itemId="{{$item->id}}" data-mainId="{{$item->layout_main_id}}" data-layoutId="{{$item->layout_id}}" onClick="eidtLayoutItem(this)"><i class="fas fa-pencil"></i></button> -->
                      <button type="button" class="btn btn-danger" data-item="{{$item->id}}" data-main="{{$item->layout_main_id}}" data-id="{{$item->id}}" onClick="deleteLayoutItem(this)"><i class="fas fa-trash"></i></button>
                    </div>
                  </td>
                </tr>
                <tr class="row-input group-{{ $item->id }}">
                  <td>
                    {!! $inputFormula !!}
                  </td>
                </tr>
                @endif
              @endif
            @endforeach
          </tbody>
          @php
            $additionalItems = $items->where('layout_main_id', $main->id)->whereIn('name', ['VAT', 'IT Administration', 'Nota Administration'])->sortBy('name');
          @endphp
          @if(!empty($additionalItems))
            <tbody>
              @foreach($additionalItems as $item)
                    @php
                      $detail = $detils->where('layout_main_id', $main->id)->where('layout_item_id', $item->id)->where('label', $item->name)->first();
                    @endphp
                <tr class="text-center text-bold row-detil" data-id="{{ $item->id }}" draggable="false">
                  <td colspan="3">
                    <strong>
                      {{$item->name}} {{ $item->name == 'VAT' ? "({$main->vat}%)" : '' }}
                    </strong>
                  </td>
                  <td colspan="1">
                    @if($main->currency_flag == 'idr')
                    <input type="number" step="any" class="form-control" id="detilAmount" data-main="{{$main->id}}" data-item="{{$item->id}}" data-detil="{{$detail->id}}" value="{{$detail->amount}}">
                    @else
                    0
                    @endif
                  </td>
                  <td colspan="1">
                    @if($main->currency_flag == 'usd')
                    <input type="number" step="any" class="form-control" id="detilAmount" data-main="{{$main->id}}" data-item="{{$item->id}}" data-detil="{{$detail->id}}" value="{{$detail->amount}}">
                    @else
                    0
                    @endif
                  </td>
                  <td colspan="2"></td>
                </tr>
              @endforeach
            </tbody>
          @endif
          <tbody>
            <tr class="text-center text-bold row-detil text-center" data-id="{{ $item->id }}" draggable="false" style="background-color: lightblue;">
              <td colspan="3" class="text-center"><strong>Total {{$main->name}}</strong></td>                  
              <td colspan="1" class="text-center">0</td>                  
              <td colspan="1" class="text-center">0</td>                  
              <td colspan="2" class="text-center">-</td>                  
            </tr>
          </tbody>
        </table>
      @endforeach
    </div>

    <table style="width: 100%; font-size: 9px; font-weight: 700; border-collapse: collapse; margin-bottom: 16px;">
      <tbody>
        <tr>
          <td style="border: 1px solid black; text-align: right; width: 60%;">ESTIMATED PORT DISBURSEMENT</td>
          <td style="border: 1px solid black; text-align: right; width: 20%;">-</td>
          <td style="border: 1px solid black; text-align: right; width: 20%;">-</td>
        </tr>
        <tr>
          <td style="border: 1px solid black; text-align: right;">FUND RECEIVED</td>
          <td style="border: 1px solid black; text-align: right;">-</td>
          <td style="border: 1px solid black; text-align: right;">-</td>
        </tr>
        <tr>
          <td style="border: 1px solid black; text-align: right;">BALANCE DUE</td>
          <td style="border: 1px solid black; text-align: right; color: #c00;">-</td>
          <td style="border: 1px solid black; text-align: right; color: #c00;">-</td>
        </tr>
      </tbody>
    </table>

    <div style="font-size: 9px; font-weight: 400; max-width: 900px;">
      <p style="margin-bottom: 4px;">Remarks:</p>
      <ol class="remarks-list" style="margin-bottom: 8px;">
        <li>Prefunding remit at least 3 (three) working days prior vessel arrival</li>
        <li>Indonesian port tariff rate and implementation are being reviewed by Government.<br />
          Tariff costs may change at short notice, Kingshin charge back to back as per original tariff receipts
        </li>
        <li>Usage time of Tug as per operator actual time.</li>
        <li>All Agency fees and costs are subject to 11% VAT according to Indonesia Government Regulation</li>
      </ol>
      <p style="font-weight: 700; margin-bottom: 4px;">Our nominated bank details as below:</p>
      <p><strong>Bankers</strong> : Bank Mandiri (Persero) Tbk, Samarinda Sudirman Branch</p>
      <p><strong>Bank Address</strong> : Jalan Jenderal Sudirman</p>
      <p><strong>Bank Acc No</strong> : 148-00-10510587</p>
      <p><strong>Currency</strong> : USD</p>
      <p><strong>Bank SWIFT code</strong> : BMRIIDJA</p>
    </div>

    <div class="footer p-3 border-top">
      <div class="d-flex justify-content-end">
        <div class="button-container">
          <button type="button" class="btn btn-warning" onClick="backIndex(this)">Back</button>
          <button type="button" class="btn btn-danger" onClick="deleteLayout(this)">Delete</button>
          <button type="button" class="btn btn-primary" onClick="saveDetilAmount(this)">Save</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      initMainDrag();
      initDetilDrag(); // <--- Tambahkan ini agar <tr> bisa didrag
    });

  
    function getLetter(number) {
      let letter = '';
      while (number > 0) {
        number--;
        letter = String.fromCharCode(65 + (number % 26)) + letter;
        number = Math.floor(number / 26);
      }
      return letter;
    }   

    function initMainDrag() {
      const container = document.getElementById('main-container');
      let draggedTable = null;    

      container.addEventListener('dragstart', function (e) {
        const table = e.target.closest('.main-table');
        if (!table) return;
        draggedTable = table;
        e.dataTransfer.effectAllowed = 'move';
      });   

      container.addEventListener('dragover', function (e) {
        e.preventDefault();
        const targetTable = e.target.closest('.main-table');
        if (!targetTable || draggedTable === targetTable) return;   

        const bounding = targetTable.getBoundingClientRect();
        const offset = e.clientY - bounding.top;    

        if (offset > bounding.height / 2) {
          container.insertBefore(draggedTable, targetTable.nextSibling);
        } else {
          container.insertBefore(draggedTable, targetTable);
        }
      });   

      container.addEventListener('drop', function () {
        updateLetters();
        saveMainOrder(); // optional: kirim urutan ke server
      });   

      function updateLetters() {
        container.querySelectorAll('.main-table').forEach((table, i) => {
          const cell = table.querySelector('thead tr th strong');
          if (cell) cell.innerText = getLetter(i + 1);
        });
      }
    }

    function initDetilDrag() {
      document.querySelectorAll('.inner-sortable').forEach(tbody => {
        let draggedId = null;    

        // Hanya baris atas (row-detil) yang bisa di-drag
        tbody.querySelectorAll('.row-detil').forEach(row => {
          row.setAttribute('draggable', 'true');     

          row.addEventListener('dragstart', function (e) {
            draggedId = row.dataset.id;
            e.dataTransfer.effectAllowed = 'move';
          });
        });    

        tbody.addEventListener('dragover', function (e) {
          e.preventDefault();
          const targetRow = e.target.closest('.row-detil');
          if (!targetRow || draggedId === targetRow.dataset.id) return;    

          const draggedRows = tbody.querySelectorAll(`.group-${draggedId}`);
          const targetId = targetRow.dataset.id;
          const targetRows = tbody.querySelectorAll(`.group-${targetId}`);     

          // Hitung posisi drop
          const bounding = targetRow.getBoundingClientRect();
          const offset = e.clientY - bounding.top;     

          const insertBefore = offset < bounding.height / 2;     

          // Hapus dari posisi lama
          draggedRows.forEach(row => row.remove());    

          // Sisipkan di posisi baru
          const referenceRow = insertBefore ? targetRows[0] : targetRows[targetRows.length - 1].nextSibling;
          draggedRows.forEach(row => tbody.insertBefore(row, referenceRow));
        });    

        tbody.addEventListener('drop', function () {
          if (draggedId) {
            saveDetilOrder(tbody);
            draggedId = null;
          }
        });
      });
    }


    async function saveMainOrder() {
      showLoading();
      const tables = document.querySelectorAll('.main-table');
      const order = [];   

      tables.forEach((table, index) => {
        order.push({
          mainId: table.dataset.id,
          position: index + 1,
          layoutId: {{$layout->id}}
        });
      });   

      const response = await fetch('{{ route("master.layout.updateMainOrder") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ order })
      });
      hideLoading();
      if (response.ok) {
        const hasil = await response.json();
        if (hasil.success) {
          return;
        }else{
          errorHasil(hasil);
        }
      }else{
        errorResponse(response);
      }
    }

    async function saveDetilOrder(tbody) {
      showLoading();    
      const mainTable = tbody.closest('.main-table');
      const mainId = mainTable.dataset.id;
      const layoutId = {{ $layout->id }};
      const order = [];   
      const rows = tbody.querySelectorAll('tr.row-detil');    

      rows.forEach((row, index) => {
        order.push({
          mainId: mainId,
          itemId: row.dataset.id,
          position: index + 1,
          layoutId: layoutId
        });
      });   

      const response = await fetch('{{ route("master.layout.updateDetilOrder") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ order })
      });   

      hideLoading();    

      if (response.ok) {
        const hasil = await response.json();
        if (!hasil.success) {
          errorHasil(hasil);
        }
      } else {
        errorResponse(response);
      }
    }
  </script>
</body>
