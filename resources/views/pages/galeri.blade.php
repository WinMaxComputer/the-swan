@extends('layouts.default')
@section('meta')
    <title>The Swand - Foto Gallery</title>
    <meta content="{!! $hotelDetail[0]->desc !!}" name="description">
    <meta content="{{ $hotelDetail[0]->slug }}" name="keywords">
@endsection
@section('content')

    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Full Gallery</h2>
          <ol>
            <li><a href="/">Home</a></li>
            <li>Gallery</li>
          </ol>
        </div>

      </div>
    </div><!-- End Breadcrumbs -->
   
    <!-- ======= Gallery Section ======= -->
    <section id="gallery" class="gallery section-bg">
      <div class="container">

        <div class="section-header">
          <!-- <h2>gallery</h2> -->
          <p>Check <span>Our Gallery</span></p>
        </div>

        <div class="gallery-slider swiper">
          <div class="swiper-wrapper align-items-center">
            
            @foreach ($galeri as $gal)
            <div class="swiper-slide">
              <a class="glightbox" data-gallery="images-gallery" href="assets/img/gallery/{{ $gal->foto }}">
                <img src="assets/img/gallery/{{ $gal->foto }}" class="img-fluid" alt="">
              </a>
            </div>
            @endforeach

          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>
    </section><!-- End Gallery Section -->



@stop