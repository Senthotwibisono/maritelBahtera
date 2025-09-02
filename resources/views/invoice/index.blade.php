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
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item" role="presentation">
                      <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">List Penawaran All</a>
                  </li>
                  <li class="nav-item" role="presentation">
                      <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">List Penawaran Kapal Belum Tiba</a>
                  </li>
                  <li class="nav-item" role="presentation">
                      <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">List Penawaran Kapal Sandar</a>
                  </li>
                  <li class="nav-item" role="presentation">
                      <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#done" role="tab" aria-controls="done" aria-selected="false">List Penawaran Kapal Sudah Berangkat</a>
                  </li>
              </ul>
              <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                      <div class="table">
                          <table class="table table-hover" id ="tableInvoice">
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
                  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                      <div class="table">
                          <table class="table table-hover" id ="tableArrival">
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
                  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                      <div class="table">
                          <table class="table table-hover" id ="tableSandar">
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
                  <div class="tab-pane fade" id="done" role="tabpanel" aria-labelledby="contact-tab">
                      <div class="table">
                          <table class="table table-hover" id ="tableDone">
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
            ajax: {
                url: '{{ route('invoice.dataTable') }}',
                data: {
                    type: 'all'
                }
            },
            scrollX: true,
            scrollY: '50vh',
            columns: [
                {data:'reference_no', name:'reference_no', className:'text-center'},
                {data:'ves_name', name:'ves_name', className:'text-center'},
                {data:'voy', name:'voy', className:'text-center'},
                {data:'arrival', name:'arrival', className:'text-center'},
                {data:'departure', name:'departure', className:'text-center'},
                {data:'port', name:'port', className:'text-center'},
                {data:'statusKapal', name:'statusKapal', className:'text-center'},
                {data:'purpose_of_call', name:'purpose_of_call', className:'text-center'},
                {data:'status', name:'status', className:'text-center'},
                {data:'user', name:'user', className:'text-center'},
                {data:'edit', name:'edit', className:'text-center'},
                {data:'print', name:'print', className:'text-center'},
                {data:'cancel', name:'cancel', className:'text-center'},
                {data:'updateStatus', name:'updateStatus', className:'text-center'},
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
            ]
        });


        $('#tableArrival').dataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: '{{ route('invoice.dataTable') }}',
                data: {
                    type: 'arrival'
                }
            },
            scrollX: true,
            scrollY: '50vh',
            columns: [
                {data:'reference_no', name:'reference_no', className:'text-center'},
                {data:'ves_name', name:'ves_name', className:'text-center'},
                {data:'voy', name:'voy', className:'text-center'},
                {data:'arrival', name:'arrival', className:'text-center'},
                {data:'departure', name:'departure', className:'text-center'},
                {data:'port', name:'port', className:'text-center'},
                {data:'statusKapal', name:'statusKapal', className:'text-center'},
                {data:'purpose_of_call', name:'purpose_of_call', className:'text-center'},
                {data:'status', name:'status', className:'text-center'},
                {data:'user', name:'user', className:'text-center'},
                {data:'edit', name:'edit', className:'text-center'},
                {data:'print', name:'print', className:'text-center'},
                {data:'cancel', name:'cancel', className:'text-center'},
                {data:'updateStatus', name:'updateStatus', className:'text-center'},
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
            ]
        });

        $('#tableSandar').dataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: '{{ route('invoice.dataTable') }}',
                data: {
                    type: 'sandar'
                }
            },
            scrollX: true,
            scrollY: '50vh',
            columns: [
                {data:'reference_no', name:'reference_no', className:'text-center'},
                {data:'ves_name', name:'ves_name', className:'text-center'},
                {data:'voy', name:'voy', className:'text-center'},
                {data:'arrival', name:'arrival', className:'text-center'},
                {data:'departure', name:'departure', className:'text-center'},
                {data:'port', name:'port', className:'text-center'},
                {data:'statusKapal', name:'statusKapal', className:'text-center'},
                {data:'purpose_of_call', name:'purpose_of_call', className:'text-center'},
                {data:'status', name:'status', className:'text-center'},
                {data:'user', name:'user', className:'text-center'},
                {data:'edit', name:'edit', className:'text-center'},
                {data:'print', name:'print', className:'text-center'},
                {data:'cancel', name:'cancel', className:'text-center'},
                {data:'updateStatus', name:'updateStatus', className:'text-center'},
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
            ]
        });

        $('#tableDone').dataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: '{{ route('invoice.dataTable') }}',
                data: {
                    type: 'done'
                }
            },
            scrollX: true,
            scrollY: '50vh',
            columns: [
                {data:'reference_no', name:'reference_no', className:'text-center'},
                {data:'ves_name', name:'ves_name', className:'text-center'},
                {data:'voy', name:'voy', className:'text-center'},
                {data:'arrival', name:'arrival', className:'text-center'},
                {data:'departure', name:'departure', className:'text-center'},
                {data:'port', name:'port', className:'text-center'},
                {data:'statusKapal', name:'statusKapal', className:'text-center'},
                {data:'purpose_of_call', name:'purpose_of_call', className:'text-center'},
                {data:'status', name:'status', className:'text-center'},
                {data:'user', name:'user', className:'text-center'},
                {data:'edit', name:'edit', className:'text-center'},
                {data:'print', name:'print', className:'text-center'},
                {data:'cancel', name:'cancel', className:'text-center'},
                {data:'updateStatus', name:'updateStatus', className:'text-center'},
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
            ]
        });
    });
</script>
@endsection