@extends('layouts.app', ['page' => __('News'), 'pageSlug' => 'news'])

@section('content')
<style>
    /* Custom Pagination Styling for Black Dashboard */
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
    /* Hide pagination arrow icons entirely to keep only text */
    .pagination .page-link svg {
        display: none !important;
    }
</style>
<div class="row">
  <div class="col-md-12">
    <div class="card ">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
          <h4 class="card-title">News Articles</h4>
          <a href="{{ route('pages.news_add') }}" class="btn btn-primary btn-sm">Add New News</a>
        </div>
      </div>
      <div class="card-body">
        <form action="{{ route('pages.news') }}" method="GET" class="mb-4">
          <div class="row">
            <div class="col-md-4">
              <div class="input-group">
                <input type="text" name="search" class="form-control text-white" placeholder="Search news..." value="{{ request('search') }}">
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
            <div class="col-md-3">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-transparent border-right-0 text-muted" style="font-size: 0.8rem;">Lang</span>
                </div>
                <select name="lang" class="form-control text-info border-left-0" onchange="this.form.submit()" style="height: calc(2.25rem + 2px);">
                  <option value="en" {{ request('lang', config('app.locale')) == 'en' ? 'selected' : '' }}>English</option>
                  <option value="id" {{ request('lang', config('app.locale')) == 'id' ? 'selected' : '' }}>Indonesia</option>
                </select>
              </div>
            </div>
            
            @if(request('search') || request('perPage') || request('lang'))
              <div class="col-md-2">
                <a href="{{ route('pages.news') }}" class="btn btn-secondary btn-sm mt-1">Clear</a>
              </div>
            @endif
          </div>
        </form>

        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="text-primary">
              <tr>
                <th class="text-center">#</th>
                <th>Code</th>
                <th>Title</th>
                <th>Content Preview</th>
                <th class="text-center">Language</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($news as $item)
                <tr>
                  <td class="text-center">{{ $loop->iteration + ($news->currentPage() - 1) * $news->perPage() }}</td>
                  <td><span class="badge badge-info">{{ $item->code }}</span></td>
                  <td><strong>{{ $item->judul }}</strong></td>
                  <td title="{{ strip_tags($item->isi) }}">{{ Str::limit(strip_tags($item->isi), 80) }}</td>
                  <td class="text-center"><span class="text-uppercase badge badge-default">{{ $item->lang }}</span></td>
                  <td class="text-center">
                    <a href="{{ route('news.edit', ['news_code' => $item->id]) }}" class="btn btn-link btn-icon btn-sm" title="Edit">
                      <i class="tim-icons icon-pencil text-info"></i>
                    </a>
                    {{-- Delete button triggers the modal --}}
                    <a href="#" class="btn btn-link btn-icon btn-sm" data-toggle="modal" data-target="#deleteModal"
                       data-news-id="{{ $item->id }}" data-news-title="{{ $item->judul }}" data-news-code="{{ $item->code }}"
                      title="Delete">
                      <i class="tim-icons icon-trash-simple text-danger"></i>
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-muted py-5">No news articles found matching your criteria.</td>
                </tr>
              @endforelse  
            </tbody>
          </table>
        </div>
        <div class="card-footer py-4 d-flex justify-content-end">
          {{ $news->links() }}
        </div>
      </div>
    </div>
  </div>
  
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <i class="tim-icons icon-simple-remove"></i>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete "<span id="newsTitleToDelete"></span>"? This action cannot be undone.
        <input type="hidden" id="newsIdToDelete">
        <input type="hidden" id="newsCodeToDelete">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>

@push('js')
<script>
    $(document).ready(function() {
        $('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var newsId = button.data('news-id'); // Extract info from data-* attributes
            var newsTitle = button.data('news-title');
            var newsCode = button.data('news-code');

            var modal = $(this);
            modal.find('#newsTitleToDelete').text(newsTitle);
            modal.find('#newsIdToDelete').val(newsId);
            modal.find('#newsCodeToDelete').val(newsCode);
        });
    });
    $('#confirmDeleteBtn').on('click', function() {
        var newsId = $('#newsIdToDelete').val();
        var newsCode = $('#newsCodeToDelete').val();
        $.ajax({
            url: '/news-delete/', // Adjust URL as needed
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}', // Include CSRF token
                news_id: newsId,
                code: newsCode,
            },
            success: function(result) {
                $('#deleteModal').modal('hide');
                location.reload(); // Reload the page to reflect changes
            },
            error: function(xhr, status, error) {
                alert('An error occurred while trying to delete the news article. Please try again.');
            }
        });
    });
</script>
@endpush
@endsection
