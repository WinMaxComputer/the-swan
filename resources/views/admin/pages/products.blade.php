@extends('layouts.app', ['page' => __('Products'), 'pageSlug' => 'products'])

@section('content')
<style>
    .pagination .page-item .page-link {
        background-color: #1e1e2f;
        border: 1px solid #2b3553;
        color: rgba(255, 255, 255, 0.7);
        padding: 5px 12px;
        margin: 0 2px;
        border-radius: 4px !important;
        font-size: 0.8125rem;
    }
    .pagination .page-item.active .page-link {
        background: linear-gradient(to bottom left, #e14eca, #ba54f5) !important;
        border-color: #e14eca !important;
        color: #fff !important;
    }
    .pagination .page-item.disabled .page-link {
        background-color: #1e1e2f;
        color: #525f7f;
    }
    .pagination .page-item .page-link:hover:not(.active) {
        background-color: #27293d;
        color: #fff;
    }
    .pagination .page-link svg {
        width: 1rem;
        height: 1rem;
        vertical-align: middle;
    }
</style>
<div class="row">
  <div class="col-md-12">
    <div class="card ">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
          <h4 class="card-title">Products</h4>
          <a href="/products-add" class="btn btn-fill btn-primary">Add</a>
        </div>
      </div>
      <div class="card-body">
        <form action="{{ route('pages.products') }}" method="GET" class="mb-4">
          <div class="row">
            <div class="col-md-4">
              <div class="input-group">
                <input type="text" name="search" class="form-control text-white" placeholder="Search products..." value="{{ request('search') }}">
                <div class="input-group-append">
                  <button type="submit" class="btn btn-info btn-icon btn-sm">
                    <i class="tim-icons icon-zoom-split"></i>
                  </button>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-transparent border-right-0 text-muted" style="font-size: 0.8rem;">Show</span>
                </div>
                <select name="perPage" class="form-control text-info border-left-0" onchange="this.form.submit()" style="height: calc(2.25rem + 2px);">
                  <option value="5" {{ request('perPage') == 5 ? 'selected' : '' }}>5 items</option>
                  <option value="10" {{ request('perPage', 10) == 10 ? 'selected' : '' }}>10 items</option>
                  <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25 items</option>
                  <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50 items</option>
                </select>
              </div>
            </div>
            
            @if(request('search') || request('perPage'))
              <div class="col-md-2">
                <a href="{{ route('pages.products') }}" class="btn btn-secondary btn-sm mt-1">Clear</a>
              </div>
            @endif
          </div>
        </form>

        <div class="table-responsive">
          <table class="table tablesorter " id="">
            <thead class=" text-primary">
              <tr>
                <th class="text-center">Code</th>
                <th>Name</th>
                <th>Type</th>
                <th>Price</th>
                <th>Desc</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
            @forelse ($products as $tur)
              <tr>
                <td>{{$tur->product_code}}</td>
                <td>{{$tur->product_name}}</td>
                <td>{{ $tur->activity_names ?: $tur->parent_type }}</td>
                <td>{{$tur->price}}</td>
                <td>{!! substr($tur->product_des, 0, 60) !!}</td>
                <td>
                  <a href="{{ route('products.edit',['product_code' => $tur->id]) }}"><i class="tim-icons icon-pencil"></i></a>
                  <i class="tim-icons icon-trash-simple"></i>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-5">No products found matching your criteria.</td>
              </tr>
            @endforelse  
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="d-flex justify-content-end mt-3">
    {{ $products->links() }}
  </div>
  
</div>
@endsection
