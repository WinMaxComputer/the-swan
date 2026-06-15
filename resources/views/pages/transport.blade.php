@extends('layouts.default')
@section('meta')
    <title>Hire a Car in bali - The Swand</title>
    <meta content="car rental for full day tour and for you avtivities" name="description">
    <meta content="bali car rental, private tour, full day tou bali, bali tour" name="keywords">
@endsection
@section('content')
    
    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Bali Car Rental</h2>
          <ol>
            <li><a href="/">Home</a></li>
            <li>Car Rental</li>
          </ol>
        </div>

      </div>
    </div><!-- End Breadcrumbs -->

    <!-- ======= Menu Section ======= -->
    <section id="menu" class="menu">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <!-- <h2>Our Menu</h2> -->
          <h3>{{ __('activities.title_car') }}</h3>
        </div>
        <p>{{ __('activities.desc_car') }}</p>
          

        <div class="tab-content chefs">
         
            <div class="tab-header text-center">
              <!-- <p>Menu</p> -->
              <!-- <h3>Transport</h3> -->
            </div>
            <div class="row gy-4">
            @foreach ($transport as $tr)
              <!-- Menu Item -->
              <div class="col-lg-4 col-md-6 d-flex align-items-stretch" >
                <div class="chef-member">
                  <div class="member-img">
                    @php $gmbr = explode(";",$tr->foto) ; @endphp
                    <img src="assets/img/transport/{{ $gmbr[0] }}" class="img-fluid" alt="">
                    <div class="social">
                    </div>
                  </div>
                  <div class="member-info">
                    <!-- {{ $gmbr[0] }} -->
                    <h4>{{ $tr->nama}}</h4>
                    <!-- <span>Cook</span> -->
                    <p>{{ substr($tr->deskripsi, 0, 200)}}</p>
                    
                  <!-- </div>
                  <div class="member-info"> -->
                    <!-- <i class="bi bi-wifi"></i>
                    <i class="bi bi-twitter"></i> -->
                    @php $fs = explode(",",$tr->fasilitas) ; @endphp
                    @foreach ($fs as $fas)
                      <i class="bi bi-check2-all"></i> {{$fas}}<br>
                      <!-- <i class="bi bi-check2-all"></i> Shower
                      <i class="bi bi-check2-all"></i> Free Wifi -->
                    @endforeach
                    IDR {{ number_format($tr->harga, 2) }} for {{ $tr->waktu }} Hours
                    <a href="https://api.whatsapp.com/send?phone=+62818688114&text=Halo" target="_blank" class="btn-book-a-table">
                      <img src="assets/img/wa.png" >Book Now</a>
                  </div>
                </div>
              </div><!-- End Chefs Member -->
            @endforeach
            </div>
          

        </div>

      </div>
    </section>
    
    <!-- End Menu Section -->

    <!-- Button trigger modal -->


    


    

    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Contact</h2>
          <p>Need Help? <span>Contact Us</span></p>
        </div>

        <!-- End Google Maps -->

        <div class="row gy-4">



        </div>

        <form action="forms/contact.php" method="post" role="form" class="php-email-form p-3 p-md-4">
          <div class="row">
            <div class="col-xl-6 form-group">
              <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
            </div>
            <div class="col-xl-6 form-group">
              <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required>
            </div>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required>
          </div>
          <div class="form-group">
            <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
          </div>
          <div class="my-3">
            <div class="loading">Loading</div>
            <div class="error-message"></div>
            <div class="sent-message">Your message has been sent. Thank you!</div>
          </div>
          <div class="text-center"><button type="submit">Send Message</button></div>
        </form><!--End Contact Form -->

      </div>
    </section><!-- End Contact Section -->

    

@stop