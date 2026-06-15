@extends('layouts.default')
@section('meta')
    <title>The Swand - Everything In Bali</title>
    <meta content="We are company that manage property and also rent for acomodation." name="description">
    <meta content="room booking, manage hotel, manage villa, transport, travel, tour" name="keywords">
@endsection
@section('content')

   
    <!-- ======= Events Section ======= -->
    <section id="events" class="events">
      <div class="container-fluid">

        <div class="section-header">
          <!-- <h2>Events</h2> -->
          <p>Whats <span>In Bali</span> </p>
        </div>

        <div class="slides-3 swiper">
          <div class="swiper-wrapper">

          @foreach ($artikel as $art)

            <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(assets/img/artikel/{{$art->foto}})">
              <h3>{{ $art->judul }}</h3>
              <!-- <div class="price align-self-start">$99</div> -->
              <div class="description">
              {!! substr($art->isi, 0, 100) !!}
              </div>
            </div><!-- End Event item -->

          @endforeach

            

          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>
    </section><!-- End Events Section -->



@stop