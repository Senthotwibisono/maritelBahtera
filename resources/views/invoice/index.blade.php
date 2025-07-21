@extends('partial.main')

@section('content')

<div class="page-heading">
    <h1>{{$title}}</h1>
</div>

<section>
    <div class="card">
        <div class="card-content">
            <div class="card-header">
                <div class="button-container">
                    <button type="button" class="btn btn-primary" onClick="openModalCreate(this)"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="table">
                    <table class="table table-hover" id ="tableInvoice">
                        <thead style="white-space: nowrap;">
                            <tr>
                                <th>Reference No</th>
                                <th>Vessel</th>
                                <th>Voy</th>
                                <th>Port</th>
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
        </div>
    </div>
</section>


<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">Pilih Layout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <section>
          {{-- Search Form --}}
            <form id="layoutSearchForm" class="mb-4">
              <div class="input-group" style="max-width: 400px;">
                <input type="text" name="search" id="searchLayout" class="form-control" placeholder="Search layouts...">
                <button type="submit" class="btn btn-primary">Search</button>
              </div>
            </form>
          {{-- Layout Cards --}}
          <div class="row" id="layoutList">
            @forelse($layouts as $layout)
              <div class="col-md-4 mb-4 border p-3 rounded">
                <a href="javascript:void(0)" data-id="{{ $layout->id }}" onclick="generateLayout(this)" class="card h-100 text-center" style="cursor: pointer;">
                  <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <img src="{{ asset('logo/logoM.png') }}" alt="Logo" style="width: 150px; height: 50px;" class="mb-3">
                    <h5 class="card-title">{{ $layout->name }}</h5>
                  </div>
                  <div class="card-footer text-muted small">
                    Creator: {{ $layout->UserCreated->name ?? '-' }}<br>
                    Created at: {{ $layout->created_at ?? '-' }}
                  </div>
                </a>
              </div>
            @empty
              <div class="col-12">
                <p class="text-center text-muted">No layouts found.</p>
              </div>
            @endforelse
          </div>
        </section>
      </div>
      <div class="modal-footer justify-content-center">
        {{-- Pagination --}}
        <nav aria-label="Page navigation">
          <ul class="pagination mb-0">
            {{-- Previous --}}
            @if ($layouts->onFirstPage())
              <li class="page-item disabled"><span class="page-link">Previous</span></li>
            @else
              <li class="page-item"><a class="page-link" href="{{ $layouts->previousPageUrl() }}">Previous</a></li>
            @endif

            {{-- Pages --}}
            @for ($i = 1; $i <= $layouts->lastPage(); $i++)
              <li class="page-item {{ $layouts->currentPage() == $i ? 'active' : '' }}">
                <a class="page-link" href="{{ $layouts->url($i) }}">{{ $i }}</a>
              </li>
            @endfor

            {{-- Next --}}
            @if ($layouts->hasMorePages())
              <li class="page-item"><a class="page-link" href="{{ $layouts->nextPageUrl() }}">Next</a></li>
            @else
              <li class="page-item disabled"><span class="page-link">Next</span></li>
            @endif
          </ul>
        </nav>
      </div>

    </div>
  </div>
</div>

@endsection
@include('invoice.js')
@section('custom_js')
<script>
    function openModalCreate(button) {
        buttonLoading(button);
        $('#addModal').modal('show')
        hideButton(button);
    }

    $('#layoutSearchForm').on('submit', function(e) {
        e.preventDefault(); 
        let keyword = $('#searchLayout').val(); 
        $.ajax({
          url: "{{ route('invoice.index') }}", // pastikan sesuai route yang handle layouts
          method: "GET",
          data: { search: keyword },
            beforeSend: function () {
            $('#layoutList').html(`
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);
            },
          success: function(response) {
            $('#layoutList').html(response); // langsung replace
          },
          error: function(xhr) {
            alert('Terjadi kesalahan saat mencari layout.');
          }
        });
    });

    $(document).ready(function() {
        $('#tableInvoice').dataTable({
            serverSide: true,
            processing: true,
            ajax: '{{route('invoice.dataTable')}}',
            scrollX: true,
            scrollY: '50vh',
            columns: [
                {data:'reference_no', name:'reference_no', className:'text-center'},
                {data:'ves_name', name:'ves_name', className:'text-center'},
                {data:'voy', name:'voy', className:'text-center'},
                {data:'port', name:'port', className:'text-center'},
                {data:'purpose_of_call', name:'purpose_of_call', className:'text-center'},
                {data:'status', name:'status', className:'text-center'},
                {data:'user', name:'user', className:'text-center'},
                {data:'edit', name:'edit', className:'text-center'},
                {data:'print', name:'print', className:'text-center'},
                {data:'cancel', name:'cancel', className:'text-center'},
                {data:'updateStatus', name:'updateStatus', className:'text-center'}
            ]
        });
    });
</script>
@endsection