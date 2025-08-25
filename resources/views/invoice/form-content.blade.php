
<title>PT. Maritel Bahtera Abadi</title>
<style>
  .logo-container {
    display: flex;
    justify-content: center;
    margin-bottom: 4px;
  }
  .logo {
    width: 400px;
    height: 150px;
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
    font-size: 12px;
    margin-top: 0;
    margin-bottom: 8px;
    padding-left: 20px;
  }
  .bank-details {
    font-size: 14px;
  }
  .bank-details strong {
    font-weight: 700;
  }
</style>
</head>
<body>
  <div class="container">
    <div class="logo-container">
      <img class="logo" src="{{asset('logo/logoAsli.webp')}}"/>
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
          <td class="bold">
            <select id="vessel" class="selectSingle" style="heigth: 50px; width: 100%">
                <option disabled selected value>Pilih Satu</option>
              @foreach($vessels as $vessel)
                <option value="{{$vessel->id}}" {{$vessel->id == $header->voy_id ? 'selected' : ''}}>{{$vessel->Master->name}} / {{$vessel->voy_no ?? ''}}</option>
              @endforeach
            </select>
          </td>
          <td>CREATED DATED</td>
          <td>
            <input type="datetime-local" id="invoice_date" value="{{$header->invoice_date ? $header->invoice_date : \Carbon\Carbon::now()}}" class="datetime form-control">
          </td>
        </tr>
        <tr>
          <td>DWT</td>
          <td>
            <input type="number" step="any" value="{{$header->dwt ?? 0}}" id="dwt" class="form-control">
            <input type="hidden" value="{{$header->id}}" id="headerId" class="form-control">
            <input type="hidden" value="{{$header->layout_id}}" id="layoutId" class="form-control">
            <input type="hidden" value="{{$header->ves_id}}" id="ves_id" class="form-control">
          </td>
          <td>PORT OF CALL</td>
          <td>
            <select id="port" class="selectSingle form-select" style="height: 50px; width: 100%">
              <option disabled selected value>Pilih Satu</option>
              @foreach($ports as $port)
                <option value="{{ $port->id }}" {{$port->id == $header->port_of_call ? 'selected' : ''}}>{{ sprintf('%s - %s', $port->code, $port->description) }}</option>
              @endforeach
            </select>
          </td>
        </tr>
        <tr>
          <td>GRT / NRT</td>
          <td>
            <div class="row">
                <div class="col-auto">
                    <label for="">GRT</label>
                    <input type="number" step="any" value="{{$header->grt ?? 0}}" id="grt" class="form-control">
                </div>
                <div class="col-auto">
                    <label for="">NRT</label>
                    <input type="number" step="any" value="{{$header->nrt ?? 0}}" id="nrt" class="form-control">
                </div>
            </div>
          </td>
          <td>PURPOSES OF CALL</td>
          <td>
            <input type="text" value="{{$header->purpose_of_call}}" id="poc" class="form-control" >
          </td>
        </tr>
        <tr>
          <td>LOA / BREADTH</td>
          <td>
            <div class="row">
                <div class="col-auto">
                    <label for="">LOA</label>
                    <input type="number" step="any" value="{{$header->loa ?? 0}}" id="loa" class="form-control">
                </div>
                <div class="col-auto">
                    <label for="">BREADTH</label>
                    <input type="number" step="any" value="{{$header->breadth ?? 0}}" id="breadth" class="form-control">
                </div>
            </div>
          </td>
          <td>ACTIVITY</td>
          <td>
            <input type="text" value="{{$header->activity}}" id="activity" class="form-control">
          </td>
        </tr>
        <tr>
          <td>FLAG</td>
          <td>
            <select id="country" class="selectSingle form-select" style="height: 50px; width: 100%;">
              <option disabled selected value>Pilih Satu</option>
              @foreach($countries as $country)
                <option value="{{$country->id}}" {{$country->id == $header->country_id ? 'selected' : ''}}>{{$country->name}}</option>
              @endforeach
            </select>
          </td>
          <td>CARGO</td>
          <td>
            <input type="text" value="{{$header->cargo}}" id="cargo" class="form-control">
          </td>
        </tr>
        <tr>
          <td>VOYAGE NUMBER</td>
          <td>
            <input type="text" value="{{$header->voy}}" id="voy" class="form-control">
          </td>
          <td>VOLUME</td>
          <td>
            <div class="input-group">
              <input type="number" step="any" id="volume" value="{{$header->volume}}" class="form-control">
              <div class="col mb-3">
                  MT
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <td>EXCHANGE RATE</td>
          <td>
            <input type="number" step="any" value="{{$header->exchange_rate}}" id="exchange_rate" class="form-control">
          </td>
          <td>EST PORT STAY</td>
          <td>
            <input type="number" value="{{$header->est_port_stay}}" id="est_port_stay" class="form-control">
          </td>
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
                        $inputTag = '<input type="number" step="any" data-key="'.$detail->key.'" data-table="'.$detail->source_table.'" data-field="'.$detail->source_field.'" id="detilAmount" data-main="'.$main->id.'" data-item="'.$item->id.'" data-detil="'.$detail->id.'" value="'.$value.'" class="form-control form-control-sm d-inline-block formula-input" style="width: 100px;">';                  

                        $inputFormula = preg_replace('/\b' . preg_quote($var, '/') . '\b/', $inputTag, $inputFormula);
                    }
                  @endphp
                  <td rowspan="">{{ $label }}</td>
                  <td rowspan="2">
                    @php
                      $itemAmount = $item->formula;
                      foreach ($variables as $var) {
                          $value = optional($itemDetails->firstWhere('key', $var))->amount ?? 0;
                          // Ganti variabel dalam formula dengan nilainya
                          $itemAmount = preg_replace('/\b' . preg_quote($var, '/') . '\b/', $value, $itemAmount);
                      }
                    
                      // Evaluasi formula
                      $evaluatedAmount = 0;
                      try {
                          eval('$evaluatedAmount = ' . $itemAmount . ';');
                      } catch (\Throwable $e) {
                          $evaluatedAmount = 0; // fallback kalau formula salah
                      }
                    @endphp
                    @if($main->currency_flag == 'idr')
                      <input type="number" data-currency="idr" step="any" name="" data-main="{{$main->id}}" data-item="{{$item->id}}" data-formula="{{$item->formula}}" id="itemAmount" class="form-control formula-result" value="{{$evaluatedAmount}}">
                    @else
                      0
                    @endif
                  </td>
                  <td rowspan="2">
                    @if($main->currency_flag == 'usd')
                      <input type="number" data-currency="usd" step="any" name="" data-main="{{$main->id}}" data-item="{{$item->id}}" data-formula="{{$item->formula}}" id="itemAmount" class="form-control formula-result" value="{{$evaluatedAmount}}">
                    @else
                      0
                    @endif
                  </td>
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
                    @php
                      $amountAdditional = $detail->amount;
                      if ($item->name == 'VAT') {
                         $total = $item->where('layout_main_id', $main->id)->sum('amount');
                         $vat = ($total * 11) / 100;
                         $amountAdditional = $vat;
                      }
                      
                    @endphp
                    @if($main->currency_flag == 'idr')
                    <input type="number" step="any" data-vat="{{$main->vat}}" data-key="{{$detail->key}}" data-table="{{$detail->source_table}}" data-field="'.$detail->source_field.'" id="detilAmount" data-main="{{$main->id}}" data-item="{{$item->id}}" data-detil="{{$detail->id}}" value="{{$detail->amount}}" class="form-control form-control-sm d-inline-block formula-input" style="width: 100px;">                  
                    <input type="hidden" data-currency="idr" step="any" data-key="{{$detail->key}}" name="" data-main="{{$main->id}}" data-item="{{$item->id}}" data-formula="{{$item->formula}}" id="itemAmount" class="form-control formula-result" value="{{$item->amount ?? $evaluatedAmount}}">
                    @else
                    0
                    @endif
                  </td>
                  <td colspan="1">
                    @if($main->currency_flag == 'usd')
                    <input type="number" step="any" data-vat="{{$main->vat}}" data-key="{{$detail->key}}" data-table="{{$detail->source_table}}" data-field="'.$detail->source_field.'" id="detilAmount" data-main="{{$main->id}}" data-item="{{$item->id}}" data-detil="{{$detail->id}}" value="{{$detail->amount}}" class="form-control form-control-sm d-inline-block formula-input" style="width: 100px;">                  
                    <input type="hidden" data-currency="usd" step="any" data-key="{{$detail->key}}" name="" data-main="{{$main->id}}" data-item="{{$item->id}}" data-formula="{{$item->formula}}" id="itemAmount" class="form-control formula-result" value="{{$item->amount ?? $evaluatedAmount}}">
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
              <td colspan="1" class="text-center">
                @if($main->currency_flag == 'idr')
                  <input type="number" data-currency="idr" step="any" data-main="{{$main->id}}" id="mainAmount" class="form-control main-result">
                @else
                  0
                @endif
              </td>                  
              <td colspan="1" class="text-center">
                @if($main->currency_flag == 'usd')
                  <input type="number" data-currency="usd" step="any" data-main="{{$main->id}}" id="mainAmount" class="form-control main-result">
                @else
                  0
                @endif
              </td>                  
              <td colspan="2" class="text-center">-</td>                  
            </tr>
          </tbody>
        </table>
      @endforeach
    </div>

    <table style="width: 100%; font-size: 14px; font-weight: 700; border-collapse: collapse; margin-bottom: 16px;">
      <colgroup>
        <col style="width: 30px;">
        <col style="width: 100px;">
        <col style="width: 260px;">
        <col style="width: 70px;">
        <col style="width: 70px;">
        <col style="width: 140px;">
        <col style="width: 40px;">
      </colgroup>
      <tbody>
        <tr>
          <td style="border: 0px solid black; text-align: right;"></td>
          <td colspan="2" style="border: 1px solid black; text-align: right;">ESTIMATED PORT DISBURSEMENT</td>
          <td style="border: 1px solid black; text-align: right;">
            <input type="number" step="any" id="portIDR" class="form-control" value="{{$header->idr_amount}}">
          </td>
          <td style="border: 1px solid black; text-align: right;">
            <input type="number" step="any" id="portUSD" class="form-control" value="{{$header->usd_amount}}">
          </td>
        </tr>
        <tr>
          <td style="border: 0px solid black; text-align: right;"></td>
          <td colspan="2" style="border: 1px solid black; text-align: right;">FUND RECEIVED</td>
          <td style="border: 1px solid black; text-align: right;">
            <input type="number" step="any" id="fundIDR" class="form-control" value="{{$header->idr_fund_amount}}">
          </td>
          <td style="border: 1px solid black; text-align: right;">
            <input type="number" step="any" id="fundUSD" class="form-control" value="{{$header->usd_fund_amount}}">
          </td>
        </tr>
        <tr>
          <td style="border: 0px solid black; text-align: right;"></td>
          <td colspan="2" style="border: 1px solid black; text-align: right;">BALANCE DUE</td>
          <td style="border: 1px solid black; text-align: right; color: #c00;">
            <input type="number" step="any" id="totalIDR" class="form-control" value="{{$header->idr_balance__due}}">
          </td>
          <td style="border: 1px solid black; text-align: right; color: #c00;">
            <input type="number" step="any" id="totalUSD" class="form-control" value="{{$header->usd_balance__due}}">
          </td>
        </tr>
      </tbody>
    </table>

    <div style="font-size: 14px; font-weight: 400; max-width: 900px;">
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
          <button type="button" class="btn btn-danger" data-id="{{$header->id}}" onClick="printPDF(this)"><i class="fas fa-print"></i>Print</button>
          <button type="button" class="btn btn-primary" onClick="saveAllHeader(this)">Save</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      initMainDrag();
      initDetilDrag(); // <--- Tambahkan ini agar <tr> bisa didrag
      amountVat();
      mainAmount();
      $('#vessel').on('change', async function() {
        const id = $(this).val();
        const hasil = await dataVessel(id);
        // console.log(data);
        $('#dwt').val(hasil.data.dwt).trigger('change');
        $('#ves_id').val(hasil.data.id).trigger('change');
        $('#grt').val(hasil.data.grt).trigger('change');
        $('#nrt').val(hasil.data.nrt).trigger('change');
        $('#loa').val(hasil.data.loa).trigger('change');
        $('#breadth').val(hasil.data.breadth).trigger('change');
        $('#country').val(hasil.data.country_id).trigger('change');
        $('#voy').val(hasil.voy.voy_no).trigger('change');
        hideLoading();
        successHasil(hasil);
      });

      $('#dwt, #grt, #nrt, #loa, #breadth').on('input change', function () {
          const value = $(this).val();
          const field = this.id; // id-nya akan jadi 'dwt', 'grt', dst.
          const data = {
              value: value,
              table: 'master_kapal',
              field: field
          };
          detilAmountVal(data);
      });

      $('#volume').on('input change', function () {
        const value = $(this).val();
        const key = 'cargo';
        const data = {
          value,
          key
        };

        cargoChange(data);
      });

      $('.formula-input').on('input change', function () {
        showLoading();
        updateFormula();
        hideLoading();
      });

      $('.formula-result').on('input change', function () {
        showLoading();
        amountVat();
        mainAmount();
        hideLoading();
      });

      $('.main-result').on('input change', function() {
        showLoading();
        idrAmount();
        hideLoading();
      })

      $('#fundIDR, #fundUSD').on('input change', function() {
        showLoading();
        idrTotal();
        usdTotal();
        hideLoading();
      });

    });

    async function detilAmountVal(data) {
        const inputs = document.querySelectorAll('#detilAmount');

        inputs.forEach(input => {
            if (input.dataset.table === data.table && input.dataset.field === data.field) {
                input.value = data.value;
                input.dispatchEvent(new Event('change'));
            }
        });
    }

    async function cargoChange(data) {
        const inputs = document.querySelectorAll('#detilAmount');

        inputs.forEach(input => {
            if (input.dataset.key === data.key) {
                input.value = data.value;
                input.dispatchEvent(new Event('change'));
            }
        });
    }

    function updateFormula() {
        const formulas = document.querySelectorAll('.formula-result');

        formulas.forEach(formulaEl => {
            const itemId = formulaEl.dataset.item;

            // Ambil semua input terkait item ini
            const allInputs = document.querySelectorAll('.formula-input');
            const relatedInputs = Array.from(allInputs).filter(input => input.dataset.item === itemId);

            // console.log(relatedInputs);

            let formula = formulaEl.dataset.formula; // simpan formula mentahnya di data-formula
            relatedInputs.forEach(input => {
                const key = input.dataset.key;
                const val = parseFloat(input.value) || 0;
                const re = new RegExp(`\\b${key}\\b`, 'g');
                formula = formula.replace(re, val);
            });

            try {
                const result = eval(formula);
                // console.log(result);
                formulaEl.value = result.toFixed(4);
            } catch (e) {
                formulaEl.value = '0.00';
            }
            amountVat();
            mainAmount();
        });
    }

    async function amountVat() {
      const vatForm = document.querySelectorAll('.formula-input');
      const vatResult = Array.from(vatForm).filter(input => input.dataset.key === 'vat');
      // console.log(vatResult);
      vatResult.forEach(vatR => {
        const mainId = vatR.dataset.main;
        const vat = vatR.dataset.vat;
        console.log(mainId);
        const allInputs = document.querySelectorAll('.formula-result');
        const relatedInputs = Array.from(allInputs).filter(input => input.dataset.main === mainId).filter(input => input.dataset.key !== 'vat');
        // console.log(allInputs, relatedInputs);
        const total = relatedInputs.reduce((sum, input) => {
          const val = parseFloat(input.value) || 0;
          return sum + val;
        }, 0);
        // console.log(total);

        // console.log(`Main ID: ${mainId}, Total: ${total}, VAT %: ${vat}`);

        const vatAmount = total * (vat / 100);
        vatR.value = vatAmount.toFixed(4);
      });
    }

    async function mainAmount() {
      const inputResults = document.querySelectorAll('.main-result');
      inputResults.forEach(result => {
        const mainId = result.dataset.main;
        const itemInputs = Array.from(document.querySelectorAll('.formula-result')).filter(input => input.dataset.main === mainId);
        const total = itemInputs.reduce((sum, input) => {
          const val = parseFloat(input.value) || 0;
          return sum + val;
        }, 0);

        result.value = total.toFixed(4);
        idrAmount();
        usdAmount();
      });
    }

    async function idrAmount() {
      const inputs = Array.from(document.querySelectorAll('.main-result')).filter(input => input.dataset.currency === 'idr');
      const total = inputs.reduce((sum, input) => {
        const val = parseFloat(input.value) || 0;
        return sum + val;
      }, 0);

      const output = document.getElementById('portIDR');
      output.value = total.toFixed(4);
      idrTotal();
      usdTotal();
    }

    async function usdAmount() {
      const inputs = Array.from(document.querySelectorAll('.main-result')).filter(input => input.dataset.currency === 'usd');
      const total = inputs.reduce((sum, input) => {
        const val = parseFloat(input.value) || 0;
        return sum + val;
      }, 0);

      const output = document.getElementById('portUSD');
      output.value = total.toFixed(4);
    }

    async function idrTotal() {
      const amount = document.getElementById('portIDR').value;
      const fund = document.getElementById('fundIDR').value;
      
      const total = amount - fund;
      const output = document.getElementById('totalIDR');

      output.value = total;
    }

    async function usdTotal() {
      const amount = document.getElementById('portUSD').value;
      const fund = document.getElementById('fundUSD').value;
      
      const total = amount - fund;
      const output = document.getElementById('totalUSD');

      output.value = total;
    }

  
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
