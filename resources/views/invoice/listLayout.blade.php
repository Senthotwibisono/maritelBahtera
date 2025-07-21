<div class="row" id="layoutList">
  @forelse($layouts as $layout)
    <div class="col-md-4 mb-4 border p-3 rounded">
      <a href="javascript:void(0)" data-id="{{ $layout->id }}" onclick="layoutUrl(this)" class="card h-100 text-center" style="cursor: pointer;">
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