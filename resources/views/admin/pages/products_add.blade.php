@extends('layouts.app', ['page' => __('Add Products'), 'pageSlug' => 'products_add'])

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card ">
      <div class="card-header">
        <h4 class="card-title">Products</h4>
      </div>
      <div class="card-body">
        @if (\Session::has('success'))
          <div class="alert alert-warning">
              <ul>
                  <li>{!! \Session::get('success') !!}</li>
              </ul>
          </div>
        @endif

      <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
        <div class="row">
          <div class="form-group col-lg-6">
            <label>Code</label>
            <input type="hidden" name="id" class="form-control" placeholder="code" value="{{ $productsDetail->id ?? '' }}" >
            <input type="text" name="product_code" class="form-control" placeholder="product_code" value="{{ $productsDetail->product_code ?? '' }}" id="product_code">
            <small class="text-muted">Leave empty for auto-generation</small>
          </div>
          <div class="form-group col-lg-6">
              <label>Name</label>
              <input type="text" name="product_name" class="form-control" placeholder="product_name" value="{{ $productsDetail->product_name ?? '' }}">
          </div>
        </div>
        <div class="row">
          <div class="form-group col-lg-6">
            <label>Slug</label>
            <input type="text" name="slug" class="form-control" placeholder="slug" value="{{ $productsDetail->slug ?? '' }}">
          </div>
          <div class="form-group col-lg-6">
            <label>Price</label>
            <input type="number" name="price" class="form-control" placeholder="Price" value="{{ $productsDetail->price ?? '' }}">
          </div>
        </div>
        <div class="row">
          <div class="form-group col-lg-6">
            <label>Lang</label>
            <select name="lang" class="form-control">
              <option value="en" {{ (isset($productsDetail) && $productsDetail->lang == 'en') || !isset($productsDetail) ? 'selected' : '' }}>English</option>
              <option value="id" {{ isset($productsDetail) && $productsDetail->lang == 'id' ? 'selected' : '' }}>Indonesia</option>
            </select>
          </div>
        </div>
        <div class="form-group">
            <label>Des</label>
            <textarea class="form-control" id="product_des" name="product_des" >{{ $productsDetail->product_des ?? '' }}</textarea>
        </div>
        <div class="form-group">
            <label>Parent Activity</label>
            <div class="row">
              @php $selectedTypes = isset($productsDetail) ? explode(';',$productsDetail->parent_type) : []; @endphp
              @foreach($activities as $desti)
                @php $isChecked = in_array($desti->code, $selectedTypes); @endphp
                <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="form-check-input" type="checkbox" name="parent_type[]" value="{{ $desti->code }}" {{ $isChecked ? 'checked' : '' }}>
                      <span class="form-check-sign">
                        <span class="check"></span>
                      </span>
                      {{ $desti->name }} <small class="text-muted">({{ $desti->lang }})</small>
                    </label>
                  </div>
                </div>
              @endforeach
            </div>
        </div>
        <div class="form-group">
            <div class="needsclick dropzone" id="document-dropzone"></div>
        </div>
        <button type="submit" class="btn btn-fill btn-primary">Simpan</button>
      </form>

      </div>
    </div>
  </div>
</div>

@push('js')
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
<script>
  var options = {
    filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
    filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
    filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
    filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='
  };
  CKEDITOR.replace('product_des', options);

  // Auto-generate code if empty
  document.querySelector('form').addEventListener('submit', function(e) {
    var codeInput = document.getElementById('product_code');
    if (!codeInput.value) {
      var timestamp = Date.now().toString().slice(-6);
      codeInput.value = 'PRD-' + timestamp;
    }
  });

  var uploadedDocumentMap = {}
  Dropzone.options.documentDropzone = {
    url: '{{ route('products.storeMedia') }}',
    maxFilesize: 10,
    acceptedFiles: '.png, .jpg, .webp, .jpeg',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    success: function (file, response) {
      console.log(file);
      $('form').append('<input type="hidden" name="document[]" value="' + response.name + '">')
      uploadedDocumentMap[file.name] = response.name
    },
    removedfile: function (file) {
      $.ajax({
          headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
          type:'POST',
          url:'/products/media/delete',
          data : {"filetodelete" : file.name},
          success : function (data) {
          }
      });
      file.previewElement.remove()
      var name = ''
      if (typeof file.name !== 'undefined') {
        name = file.name
      } else {
        name = uploadedDocumentMap[file.name]
      }
      $('form').find('input[name="document[]"][value="' + name + '"]').remove()
    },
    init: function () {
      @if(isset($productsDetail) && $productsDetail->product_foto)
        var filess = {!! json_encode($productsDetail->product_foto) !!}
        var filesa = filess.split(';');

        var files = [];
        for (let a = 0; a < filesa.length -1; a++) {
            files.push ({
                'file_name': filesa[a],
            });
        }

        for (var i in files) {
          var file = files[i]
          let mockFile = { name: file.file_name, size: 12345, type: 'image/jpg', accepted: true };
          this.emit("addedfile", mockFile);
          this.emit("thumbnail", mockFile, "/assets/img/products/" + file.file_name)
          {
              $('[data-dz-thumbnail]').css('height', '120');
              $('[data-dz-thumbnail]').css('width', '120');
              $('[data-dz-thumbnail]').css('object-fit', 'cover');
          };              
          this.emit('complete', mockFile);
          this.files.push(mockFile);

          $('form').append('<input type="hidden" name="document[]" value="' + file.file_name + '">')
        }
      @endif
    }
  }
  
</script>

@endpush
@endsection
