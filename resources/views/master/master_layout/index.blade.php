@extends('partial.main')
@section('custom_styles')

@endsection

@section('content')

<div class="page-header">
    <h1>{{$title}}</h1>
</div>

<section>
    <form method="GET" action="{{ route('master.layout.index') }}" class="mb-4">
        <div class="input-group" style="width: 400px;">
            <input type="text" name="search" class="form-control" placeholder="Search layouts..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>
    <div class="row">
        <div class="col-auto mb-3">
            <div class="card text-center" onclick="addNewLayout(this)" style="cursor: pointer; width: 400px">
                <div class="card-content">
                    <div class="card-header"></div>
                    <div class="card-body">
                        <i class="fas fa-plus" style="font-size: 50px; color: lightblue;"></i>
                    </div>
                    <div class="card-footer">
                        <h4>Add new Layout...</h4>
                    </div>
                </div>
            </div>
        </div>
        @foreach($layouts as $layout)
            <div class="col-auto mb-3">
                <a href="javascript:void(0)" data-id="{{$layout->id}}" onclick="layoutUrl(this)" class="card text-center" style="cursor: pointer; width: 400px">
                    <div class="card-content">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <img src="{{ asset('logo/logoM.png') }}" alt="Logo" style="width: 150px; height: 50px;">
                        </div>
                        <div class="card-footer">
                            <div class="mb-3">
                                <h4>{{$layout->name}}</h4>
                            </div>
                            <div class="mb-3">
                                Creator : {{$layout->UserCreated->name ?? '-'}} at {{$layout->created_at ?? '-'}}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
        <!-- Pagination links -->
    </div>
    
</section>
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        {{-- Previous Page Link --}}
        @if ($layouts->onFirstPage())
            <li class="page-item disabled"><a class="page-link">Previous</a></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $layouts->previousPageUrl() }}">Previous</a></li>
        @endif

        {{-- Pagination Elements --}}
        @for ($i = 1; $i <= $layouts->lastPage(); $i++)
            <li class="page-item {{ $layouts->currentPage() == $i ? 'active' : '' }}">
                <a class="page-link" href="{{ $layouts->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        {{-- Next Page Link --}}
        @if ($layouts->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $layouts->nextPageUrl() }}">Next</a></li>
        @else
            <li class="page-item disabled"><a class="page-link">Next</a></li>
        @endif
    </ul>
</nav>

@endsection

@section('custom_js')

<script>
    async function addNewLayout(button) {
        buttonLoading(button);
        const data = { };

        const url = '{{route('master.layout.create')}}';
        const response = await globalResponse(data, url);

        hideButton(button);
        if (response.ok) {
            const hasil = await response.json();
            if (hasil.success) {
                await successHasil(hasil).then(() => {
                    showLoading();
                    const redirectUrl = `{{ route('master.layout.indexDetil', ['id' => '__ID__']) }}`.replace('__ID__', hasil.data.id);
                    window.location.href = redirectUrl;
                });
            }else{
                errorHasil(hasil);
                return;
            }
        }else{
            errorResponse(response);
            return;
        }
    }

    async function layoutUrl(button) {
        buttonLoading(button);
        const id = button.dataset.id;
        // console.log(id);
        const redirectUrl = `{{ route('master.layout.indexDetil', ['id' => '__ID__']) }}`.replace('__ID__', id);
        window.location.href = redirectUrl;
    }
</script>

@endsection
