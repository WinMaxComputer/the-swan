@extends('layouts.app', ['page' => __('Add News'), 'pageSlug' => 'news_add'])

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card ">
      <div class="card-header">
        <h4 class="card-title">News</h4>
        
      </div>
      <div class="card-body">

      <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
        <div class="row">
            <div class="form-group col-lg-6">
                <label>Code</label>
                <input type="text" name="code" id="code" class="form-control text-white" placeholder="News Code" value="{{ $newsDetail?->code ?? 'NEWS-' . date('YmdHis') }}" required>
            </div>
            <div class="form-group col-lg-6">
                <label>Title</label>
                <input type="hidden" name="id" value="{{ $newsDetail?->id ?? '' }}" >
                <input type="text" name="judul" id="judul" class="form-control text-white" placeholder="News Title" value="{{ $newsDetail?->judul ?? '' }}" required>
            </div>
            <div class="form-group col-lg-6">
                <label>Language</label>
                <select name="lang" class="form-control text-info">
                    <option value="en" {{ (isset($newsDetail) && $newsDetail->lang == 'en') ? 'selected' : '' }}>English</option>
                    <option value="id" {{ (isset($newsDetail) && $newsDetail->lang == 'id') ? 'selected' : '' }}>Indonesia</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-12">
                <label>Slug</label>
                <input type="text" name="slug" id="slug" class="form-control text-white" placeholder="News Slug" value="{{ $newsDetail?->slug ?? '' }}">
            </div>
        </div>
        <div class="form-group">
            <label>Content</label>
            <textarea class="form-control" id="isi" name="isi" >{{ $newsDetail?->isi ?? '' }}</textarea>
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
          <!-- <textarea class="my-edit form-control" id="my-edit" name="wysiwyg-editor"></textarea> -->
          
  @push('js')
  <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
  <script>
    var options = {
      filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
      filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
      filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
      filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='
    };
    CKEDITOR.replace('isi', options);


    // Function to generate slug from title
    function generateSlug(title) {
        return title
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '') // Remove invalid chars
            .replace(/[\s_-]+/g, '-')    // Collapse whitespace and replace by -
            .replace(/^-+|-+$/g, '');    // Remove dashes from start and end
    }

    // Listen for changes on the title input to auto-generate slug
    $('#judul').on('keyup', function() {
        const title = $(this).val();
        const slug = generateSlug(title);
        $('#slug').val(slug);
    });
    var uploadedDocumentMap = {}
    Dropzone.options.documentDropzone = {
      url: '{{ route('news.storeMedia') }}',
      maxFilesize: 10, // MB
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
        // console.log(file.xhr);
        if(file.xhr === undefined){
          nama = file.name ;
        }else{
          var response = JSON.parse(file.xhr.response);
          nama = response.name ;
          console.log(response.name);
        }
        $.ajax({
            headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            type:'POST',
            url:'/news/media/delete',
            data : { "filetodelete" : nama },
            success : function (data) {
              // console.log(data)
              
            }
        }); 
        file.previewElement.remove()
        var name = ''
        if (typeof nama !== 'undefined') {
          name = nama
        } else {
          name = uploadedDocumentMap[nama]
        }
        $('form').find('input[name="document[]"][value="' + name + '"]').remove()     
      
      },
      init: function () {
        // console.log('onload dropzone');

        @if(isset($newsDetail) && $newsDetail->foto)
          var filess = {!! json_encode($newsDetail->foto) !!}
          var filesa = filess.split(';');

          const files = [];
          for (let a = 0; a < filesa.length -1; a++) {
              files.push ({
                  'file_name': filesa[a],
              });
              // console.log(filesa[a])
          }

          // console.log(files);
          for (var i in files) {
            var file = files[i]
            // console.log(this)
            // this.files.push(file);

            // this.options.addedfile.call(this, file)
            // file.previewElement.classList.add('dz-complete')

            // var principal = '@Model.Article.Image'; 
            let mockFile = { name: file.file_name, size: 12345, type: 'image/jpg', accepted: true };
            this.emit("addedfile", mockFile);
            this.emit("thumbnail", mockFile, "/assets/img/news/" + file.file_name)
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
