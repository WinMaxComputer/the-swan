@extends('layouts.app', ['page' => __('Add Tour'), 'pageSlug' => 'tour_add'])

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card ">
      <div class="card-header">
        <h4 class="card-title">Tour</h4>
        
      </div>
      <div class="card-body">

      <form action="{{ route('tour.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
        <div class="row">
          <div class="form-group col-lg-6">
            <label>Code</label>
            <input type="hidden" name="id" value="{{ $tourDetail->id ?? '' }}" >
            <input type="text" name="code" class="form-control text-white" placeholder="Tour Code" value="{{ $tourDetail->code ?? 'TOUR-' . date('YmdHis') }}" required>
          </div>
          <div class="form-group col-lg-6">
              <label>Name</label>
              <input type="text" name="tour_name" id="tour_name" class="form-control text-white" placeholder="Tour Name" value="{{ $tourDetail->tour_name ?? '' }}" required>
          </div>
        </div>
        <div class="row">
          <div class="form-group col-lg-6">
            <label>Language</label>
            <select name="lang" class="form-control text-info">
                <option value="en" {{ (isset($tourDetail) && $tourDetail->lang == 'en') || !isset($tourDetail) ? 'selected' : '' }}>English</option>
                <option value="id" {{ (isset($tourDetail) && $tourDetail->lang == 'id') ? 'selected' : '' }}>Indonesia</option>
            </select>
          </div>
          <div class="form-group col-lg-6">
              <label>Type</label>
              <input type="text" name="type" class="form-control text-white" placeholder="Tour Type" value="{{ $tourDetail->type ?? '' }}">
          </div>
        </div>
        <div class="row">
          <div class="form-group col-lg-6">
            <label>Slug</label>
            <input type="text" name="slug" id="slug" class="form-control text-white" placeholder="URL Slug" value="{{ $tourDetail->slug ?? '' }}">
          </div>
          <div class="form-group col-lg-6">
            <label>Area</label>
            <select name="area_tour" class="form-control text-info">
                <option value="" disabled {{ !isset($tourDetail) ? 'selected' : '' }}>Select Tour Area</option>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}" {{ (isset($tourDetail) && $tourDetail->area_tour == $area->id) ? 'selected' : '' }}>{{ ucfirst($area->name) }}</option>
                @endforeach
            </select>
          </div>
        </div>
        <div class="form-group">
            <label>Itinerary</label>
            <textarea class="form-control" id="itinerary" name="itinerary" >{{ $tourDetail->itinerary ?? '' }}</textarea>
        </div>
        <div class="form-group">
            <label>Price</label>
            <textarea class="form-control" id="price" name="price" >{{ $tourDetail->price ?? '' }}</textarea>
        </div>
        <div class="form-group">
            <label>Pickup</label>
            <textarea class="form-control" id="pickup" name="pickup" >{{ $tourDetail->pickup ?? '' }}</textarea>
        </div>
        <div class="form-group">
            <label>Payment</label>
            <textarea class="form-control" id="payment" name="payment" >{{ $tourDetail->payment ?? '' }}</textarea>
        </div>
        <div class="form-group">
            <label>Note</label>
            <textarea class="form-control" id="note" name="note" >{{ $tourDetail->note ?? '' }}</textarea>
        </div>
        <div class="form-group">
            <label>Destinations</label>
            <div class="row px-3">
                @php $selectedDestinations = isset($tourDetail) ? explode(';', $tourDetail->destination) : []; @endphp
                @foreach($destinasi as $desti)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="destination[]" value="{{ $desti->id }}" 
                                    {{ in_array($desti->id, $selectedDestinations) ? 'checked' : '' }}>
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
            <label>Photo Gallery</label>
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
    CKEDITOR.replace('itinerary', options);
    CKEDITOR.replace('price', options);
    CKEDITOR.replace('pickup', options);
    CKEDITOR.replace('payment', options);
    CKEDITOR.replace('note', options);

    // Slug auto-generation
    function generateSlug(text) {
        return text.toLowerCase().trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    $('#tour_name').on('keyup', function() {
        @if(!isset($tourDetail))
        const title = $(this).val();
        const slug = generateSlug(title);
        $('#slug').val(slug);
        @endif
    });

    var uploadedDocumentMap = {}
    Dropzone.options.documentDropzone = {
      url: '{{ route('tour.storeMedia') }}',
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
            url:'/tour/media/delete',
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

        @if(isset($tourDetail) && $tourDetail->foto)
          var filess = {!! json_encode($tourDetail->foto) !!}
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
            this.emit("thumbnail", mockFile, "/assets/img/tour/" + file.file_name)
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