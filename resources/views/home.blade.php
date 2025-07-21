@extends('partial.main')

@section('custom_styles')

<style>
    #myDonutChart {
        max-width: 300px;
        max-height: 300px;
    }
</style>

@endsection

@section('content')
<div class="page-heading">
    <h4>{{$title}}</h4>
</div>
<section>
    <div class="row mt-0 d-flex align-items-stretch">
        <div class="col-sm-4">
            <div class="card h-100 justify-content-center align-items-center mt-0">
                <div class="card-header text-center">
                    <p><strong>Maritel Bahtera Abadi || Invoice Center</strong></p>
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset('logo/logoAsli.jpg') }}" style="width: 100%;" alt="Logo">
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card h-100 justify-content-center align-items-center mt-0">
                <div class="card-header text-center">
                    <h1><strong>Selamat Datang, <b>{{ \Auth::user()->name}}</b></strong></h1>
                    <p id="real-time-clock">{{ \Carbon\Carbon::now() }}</p>
                </div>
                
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card h-100 justify-content-center align-items-center mt-0">
                <div class="card-header text-center">
                    <p><strong>Jumlah Sampai Invoice</strong></p>
                </div>
                <div class="card-body">
                    <canvas id="myDonutChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</section>
<br>
<section>
@endsection
@section('custom_js')
<script>
    function updateClock() {
        const now = new Date();
        const formattedTime = now.toLocaleTimeString('id-ID', { hour12: false });
        document.getElementById('real-time-clock').textContent = formattedTime;
    }

    setInterval(updateClock, 1000);
    updateClock();
</script>

<script>
    var ctx = document.getElementById('myDonutChart').getContext('2d');

    var donutChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Selesai', 'Dalam Pengjuan', 'Cancel'],
            datasets: [{
                label: 'Jumlah',
                data: [{{ $lunas ?? 0 }}, {{ $piutang ?? 0 }}, {{ $cancel ?? 0 }}], // Menghindari error jika variabel kosong
                backgroundColor: [
                    'rgb(6, 224, 224)',   // Warna 'Lunas'
                    'rgb(255, 255, 99)',  // Warna 'Belum Bayar'
                    'rgb(255, 98, 98)'    // Warna 'Cancel'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    
                }
            }
        }
    });
</script>

@endsection