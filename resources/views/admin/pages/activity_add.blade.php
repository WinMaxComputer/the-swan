@extends('layouts.app', ['page' => __('Add Activity'), 'pageSlug' => 'activity_add'])

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card ">
      <div class="card-header">
        <h4 class="card-title">Bali Actvities</h4>
        
      </div>
      <div class="card-body">

      <form action="{{ route('activity.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
        <div class="row">
          <div class="form-group col-lg-6">
              <label>Code</label>
              <input type="hidden" name="id" class="form-control" placeholder="code" value="{{ $activityDetail->id ?? '' }}" >
              <input type="text" name="code" class="form-control text-white" placeholder="Activity Code" value="{{ $activityDetail->code ?? 'ACT-' . date('YmdHis') }}" required>
          </div>
          <div class="form-group col-lg-6">
            <label>Name</label>
            <input type="text" name="name" id="name" class="form-control text-white" placeholder="Activity Name" value="{{ $activityDetail->name ?? '' }}" required>
          </div>
        </div>

        <div class="row">
          <div class="form-group col-lg-6">
              <label>slug</label>
              <input type="text" name="slug" id="slug" class="form-control text-white" placeholder="URL Slug" value="{{ $activityDetail->slug ?? '' }}">
          </div>
          <div class="form-group col-lg-6">
              <label>Lang</label>
              <select name="lang" class="form-control text-info">
                  <option value="en" {{ (isset($activityDetail) && $activityDetail->lang == 'en') || !isset($activityDetail) ? 'selected' : '' }}>English</option>
                  <option value="id" {{ (isset($activityDetail) && $activityDetail->lang == 'id') ? 'selected' : '' }}>Indonesia</option>
              </select>
          </div>
          
        </div>
        <div class="row">
          <div class="form-group col-lg-6">
              <label>Type</label>
              <select name="type" class="form-control text-info" required>
                  <option value="" disabled {{ !isset($activityDetail) ? 'selected' : '' }}>Select Type</option>
                  <option value="land" {{ (isset($activityDetail) && $activityDetail->type == 'land') ? 'selected' : '' }}>Land Activity</option>
                  <option value="water" {{ (isset($activityDetail) && $activityDetail->type == 'water') ? 'selected' : '' }}>Water Activity</option>
                  <option value="air" {{ (isset($activityDetail) && $activityDetail->type == 'air') ? 'selected' : '' }}>Air Activity</option>
              </select>
          </div>
          <div class="form-group col-lg-6">
                <label>Areas</label>
                @php
                  $selectedAreas = old('area', isset($activityDetail) ? array_filter(explode(';', $activityDetail->area)) : []);
                @endphp
                <select name="area[]" class="form-control text-info" multiple size="5" required>
                  @foreach($areas ?? [] as $area)
                    <option value="{{ $area->id }}" {{ in_array((string) $area->id, array_map('strval', $selectedAreas), true) ? 'selected' : '' }}>
                      {{ ucfirst($area->name) }}
                    </option>
                  @endforeach
                </select>
                <small class="text-muted">Hold Ctrl (Windows) or Cmd (Mac) to choose more than one area.</small>
            </div>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" >{{ $activityDetail->deskripsi ?? '' }}</textarea>
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
    CKEDITOR.replace('deskripsi', options);

    // Function to generate slug from name
    function generateSlug(text) {
        return text
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '') // Remove invalid chars
            .replace(/[\s_-]+/g, '-')    // Collapse whitespace and replace by -
            .replace(/^-+|-+$/g, '');    // Remove dashes from start and end
    }

    // Listen for changes on the name input to auto-generate slug
    $('#name').on('keyup', function() {
        @if(!isset($activityDetail))
        const name = $(this).val();
        const slug = generateSlug(name);
        $('#slug').val(slug);
        @endif
    });


    var uploadedDocumentMap = {}
    Dropzone.options.documentDropzone = {
      url: '{{ route('activity.storeMedia') }}',
      maxFilesize: 10, // MB
      acceptedFiles: '.png, .jpg',
      addRemoveLinks: true,
      headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
      },
      success: function (file, response) {
        console.log(file);
        $(file.previewElement).closest('form').append('<input type="hidden" name="document[]" value="' + response.name + '">')
        uploadedDocumentMap[file.name] = response.name
      },
      removedfile: function (file) {
        let nama;
        if(file.xhr === undefined){
          nama = file.name ;
        }else{
          let response = JSON.parse(file.xhr.response);
          nama = response.name ;
          console.log(response.name);
        }
        $.ajax({
            headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            type:'POST',
            url:'/activity/media/delete',
            data : { "filetodelete" : nama },
            success : function (data) {              
            }
        }); 
        file.previewElement.remove()
        let name = ''
        if (typeof nama !== 'undefined') {
          name = nama
        } else {
          name = uploadedDocumentMap[nama]
        }
        $('form').find('input[name="document[]"][value="' + name + '"]').remove()     
      
      },
      init: function () {
        // console.log('onload dropzone');

        @if(isset($activityDetail) && $activityDetail->foto)
          var filess = {!! json_encode($activityDetail->foto) !!}
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
            this.emit("thumbnail", mockFile, "/assets/img/activity/" + file.file_name)
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
