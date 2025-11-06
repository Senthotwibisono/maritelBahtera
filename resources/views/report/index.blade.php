@extends('partial.main')

@section('content')

    <h4>{{$title}}</h4>
    <div class="card">
        <div class="card-footer">
            Form Report
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <label for="">Status</label>
                    <select name="" id="status" class="form-select selectSingle">
                        <option value="all" selected>All</option>
                        <option value="Y">Di Setujui</option>
                        <option value="N">Dalam Pengajuan</option>
                        <option value="C">Cancel</option>
                    </select>
                </div>
                <div class="col-3">
                    <label for="">Start Date</label>
                    <input type="date" name="" id="start" value="{{$start}}" class="form-control">
                </div>
                <div class="col-3">
                    <label for="">End Date</label>
                    <input type="date" name="" id="end" value="{{$end}}" class="form-control">
                </div>
                <div class="col-3 d-flex align-items-end">
                    <button type="button" class="btn btn-info" id="search"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="button-container">
                <button class="btn btn-success" id="btnExcel">
                    <i class="fas fa-file-excel"></i> Cetak Excel
                </button>
                <button type="button" class="btn btn-success" id="btnPrint">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table">
                <table class="table table-hover" id ="tableReport">
                    <thead style="white-space: nowrap;">
                        <tr>
                            <th>Reference No</th>
                            <th>Vessel</th>
                            <th>Voy</th>
                            <th>Arrival Date</th>
                            <th>Departure Date</th>
                            <th>Port</th>
                            <th>Status Kapal</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th>User</th>
                            <th>Edit</th>
                            <th>Print</th>
                            <th>Cancel</th>
                            <th>Update Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-6">
                    <h4>Total Idr Amount: <span id="totalSum">Rp 0</span></h4>
                    <h4>Total Idr Fund: <span id="totalFund">Rp 0</span></h4>
                    <h4>Total Idr Balance Due: <span id="totalDue">Rp 0</span></h4>
                </div>
                <div class="col-6">
                    <h4>Total USD Amount: <span id="totalSumUsd">$ 0</span></h4>
                    <h4>Total USD Fund: <span id="totalFundUsd">$ 0</span></h4>
                    <h4>Total USD Balance Due: <span id="totalDueUsd">$ 0</span></h4>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('custom_js')
<script>
$(document).ready(function() {
    $('#btnExcel').on('click', function() {
        let status = $('#status').val() || 'all';
        let start = $('#start').val() || '';
        let end = $('#end').val() || '';

        let url = "{{ route('report.excel') }}" + "?status=" + status + "&start=" + start + "&end=" + end;
        window.open(url, '_blank'); // buka di tab baru / langsung download
    });
});
</script>
<script>
$(document).ready(function() {

    let table = $('#tableReport').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: '{{ route('report.data') }}',
            data: function (d) {
                d.status = $('#status').val();
                d.start = $('#start').val();
                d.end = $('#end').val();
            }
        },
        scrollX: true,
        scrollY: '50vh',
        columns: [
            {data:'reference_no', className:'text-center'},
            {data:'ves_name', className:'text-center'},
            {data:'voy', className:'text-center'},
            {data:'arrival', className:'text-center'},
            {data:'departure', className:'text-center'},
            {data:'port', className:'text-center'},
            {data:'statusKapal', className:'text-center'},
            {data:'purpose_of_call', className:'text-center'},
            {data:'status', className:'text-center'},
            {data:'user', className:'text-center'},
            {data:'edit', className:'text-center'},
            {data:'print', className:'text-center'},
            {data:'cancel', className:'text-center'},
            {data:'updateStatus', className:'text-center'},
        ]
    });

    // Saat tombol Search ditekan
    $('#search').click(function() {
        table.ajax.reload();
        refreshAllTotals();
    });

    // Saat tabel selesai reload (misalnya pagination)
    table.on('draw.dt', function() {
        refreshAllTotals();
    });

    // === FUNGSI PEMANGGIL SEMUA TOTAL ===
    function refreshAllTotals() {
        loadTotal();
        loadFund();
        loadDue();
        loadTotalUsd();
        loadFundUsd();
        loadDueUsd();
    }

    // === TOTAL IDR ===
    function loadTotal() {
        $.ajax({
            url: '{{ route('report.total') }}',
            data: baseParams(),
            success: res => $('#totalSum').text(res.total)
        });
    }

    function loadFund() {
        $.ajax({
            url: '{{ route('report.fund') }}',
            data: baseParams(),
            success: res => $('#totalFund').text(res.total)
        });
    }

    function loadDue() {
        $.ajax({
            url: '{{ route('report.due') }}',
            data: baseParams(),
            success: res => $('#totalDue').text(res.total)
        });
    }

    // === TOTAL USD ===
    function loadTotalUsd() {
        $.ajax({
            url: '{{ route('report.totalUsd') }}',
            data: baseParams(),
            success: res => $('#totalSumUsd').text(res.total)
        });
    }

    function loadFundUsd() {
        $.ajax({
            url: '{{ route('report.fundUsd') }}',
            data: baseParams(),
            success: res => $('#totalFundUsd').text(res.total)
        });
    }

    function loadDueUsd() {
        $.ajax({
            url: '{{ route('report.dueUsd') }}',
            data: baseParams(),
            success: res => $('#totalDueUsd').text(res.total)
        });
    }

    // === PARAMETER BAWAAN ===
    function baseParams() {
        return {
            status: $('#status').val(),
            start: $('#start').val(),
            end: $('#end').val()
        };
    }

    // Load awal
    refreshAllTotals();

    $('#btnPrint').click(function() {
        let status = $('#status').val();
        let start = $('#start').val();
        let end = $('#end').val();

        // Bentuk URL ke route print dengan parameter
        let url = "{{ route('report.print', ['status' => ':status', 'start' => ':start', 'end' => ':end']) }}";
        url = url.replace(':status', status || 'all')
                 .replace(':start', start || '')
                 .replace(':end', end || '');

        // Buka di tab baru
        window.open(url, '_blank');
    });

});
</script>


@endsection

