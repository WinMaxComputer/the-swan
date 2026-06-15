@extends('layouts.default')
@section('meta')
    <title>The Swand - About US</title>
    <meta content="We are company that manage property and also rent for acomodation." name="description">
    <meta content="room booking, manage hotel, manage villa, transport, travel, tour" name="keywords">
@endsection
@section('content')

    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <!-- <h2>Hotel Detail</h2> -->
          <!-- <ol> -->
            <!-- <li><a href="index.html">Home</a></li>
            <li>Sample Inner Page</li> -->
          <!-- </ol> -->
        </div>

      </div>
    </div><!-- End Breadcrumbs -->

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
        <div class="container">

          <div class="section-header">
            <h2>{{ __('headmenu.about_us') }}</h2>
            <p>{{ __('headmenu.about_des') }} <span></span></p>
          </div>

          <div class="row gy-4">
            <div class="col-lg-7 position-relative about-img" style="background-image: url(assets/img/about.webp) ;">
              <div class="call-us position-absolute">
                <h4>{{ __('headmenu.about_book') }}</h4>
                
                <a href="https://api.whatsapp.com/send?phone=+6282340064488&text=Halo" target="_blank" class="btn-book-a-table">
                    <img src="assets/img/wa.png" class="img-fluid">+62 8234 006 4488
                  </a>
                
              </div>
            </div>
            <div class="col-lg-5 d-flex align-items-end">
              <div class="content ps-0 ps-lg-5">
                <p class="fst-italic">
                  We are company that manage property and also rent for acomodation. 
                </p>
                <ul>
                  <li><i class="bi bi-check2-all"></i> As traveller : we are your friend, family and guide as you need.</li>
                  <li><i class="bi bi-check2-all"></i> As Traveller : we make your holly day to be awesome and memoriable,.</li>
                  <li><i class="bi bi-check2-all"></i> As Traveller : rooms, transport, private tour as you wish or our idea.</li>
                </ul>
                <p>dont be hastitate to contact us on mobile or whatsapp number 24H/27.</p>
                <p>Or you have property still confuse to manage, don't worry sit back, take a braath and relax let our team do the best.</p>
                <ul>
                  <li><i class="bi bi-check2-all"></i> As Property Owner : Increase booking from OTA.</li>
                  <li><i class="bi bi-check2-all"></i> As Property Owner : Increase Revenue, neat report, </li>
                  <li><i class="bi bi-check2-all"></i> As Property Owner : and also taking care all about the property.</li>
                </ul>
                <!-- <div class="position-relative mt-4">
                  <img src="assets/img/about-2.jpg" class="img-fluid" alt="">
                  <a href="https://www.youtube.com/watch?v=LXb3EKWsInQ" class="glightbox play-btn"></a>
                </div> -->
              </div>
            </div>
          </div>

        </div>
      </section><!-- End About Section -->


@stop