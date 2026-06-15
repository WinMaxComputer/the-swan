@extends('layouts.default')
@section('meta')
    <title>Bali Best Place To join with us - The Swand</title>
    <meta content="we are company that acomodate room booking, tour travel and also property management, visit bali instereted place" name="description">
    <meta content="bali room booking, car rental bali, bali management property, ubud tour, bali tour" name="keywords">
@endsection

@section('media')
<style>
    .nav-tabs.desti-tabs {
        border-bottom: none;
        gap: 12px;
        margin-bottom: 30px;
    }
    .nav-tabs.desti-tabs .nav-item {
        margin-bottom: 0;
    }
    .nav-tabs.desti-tabs .nav-link {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 50px;
        color: #444;
        padding: 8px 25px;
        transition: 0.3s;
        box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.05);
    }
    .nav-tabs.desti-tabs .nav-link h4 {
        margin-bottom: 0;
        font-size: 0.95rem;
        font-weight: 600;
    }
    .nav-tabs.desti-tabs .nav-link.active {
        background: #ce1212;
        color: #fff;
        border-color: #ce1212;
    }

    .destination-card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        height: 100%;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    .destination-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    .destination-img-container {
        height: 180px; /* Fixed height for images */
        overflow: hidden;
        position: relative; /* Added for badge positioning */
    }
    .destination-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endsection

@section('media')
<style>
    .nav-tabs.desti-tabs {
        border-bottom: none;
        gap: 12px;
        margin-bottom: 30px;
    }
    .nav-tabs.desti-tabs .nav-item {
        margin-bottom: 0;
    }
    .nav-tabs.desti-tabs .nav-link {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 50px;
        color: #444;
        padding: 8px 25px;
        transition: 0.3s;
        box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.05);
    }
    .nav-tabs.desti-tabs .nav-link h4 {
        margin-bottom: 0;
        font-size: 0.95rem;
        font-weight: 600;
    }
    .nav-tabs.desti-tabs .nav-link.active {
        background: #ce1212;
        color: #fff;
        border-color: #ce1212;
    }

    .destination-card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        height: 100%;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    .destination-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    .destination-img-container {
        height: 180px; /* Fixed height for images */
        overflow: hidden;
        position: relative; /* Added for badge positioning */
    }
    .destination-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endsection

@section('content')
    
    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Bali Destinations</h2>
          <ol>
            <li><a href="/">Home</a></li>
            <li>All Bali Destinations</li>
          </ol>
        </div>

      </div>
    </div><!-- End Breadcrumbs -->

    <!-- ======= Menu Section ======= -->
    <section id="menu" class="menu">
      <div class="container">

        <div class="section-header">
          <!-- <h2>Our Menu</h2> -->
          <p>Best <span>Bali destinations place</span></p>
        </div>

        <ul class="nav nav-tabs desti-tabs d-flex justify-content-center">

          <li class="nav-item ">
            <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#menu-ubud">
              <h4>Ubud</h4>
            </a>
          </li><!-- End tab nav item -->

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#menu-south">
              <h4>South Bali Destinations</h4>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#menu-north">
              <h4>North Bali Destinations</h4>
            </a>
          </li><!-- End tab nav item -->

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#menu-east">
              <h4>East Bali Destinations</h4>
            </a>
          </li>
          <!-- End tab nav item -->

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#menu-kintamani">
              <h4>Kintamani Bali Destinations</h4>
            </a>
          </li>
          <!-- End tab nav item -->

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#menu-west">
              <h4>West Bali Destinations</h4>
            </a>
          </li>
          <!-- End tab nav item -->

        </ul>

        <div class="tab-content chefs">
       
          <div class="tab-pane fade active show" id="menu-ubud">
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">
            @forelse ($destinasi->where('type', '1') as $tr)
                <div class="col">
                  <a href="/destinations/{{$tr->slug}}" class="destination-card d-block">
                  <div class="destination-img-container rounded-3 overflow-hidden shadow-sm">
                    @php $gmbr = explode(";",$tr->foto) ; @endphp
                    <img src="{{ asset('assets/img/destinasi/' . ($gmbr[0] ?? '')) }}" alt="{{ $tr->name }}">
                    <span class="badge bg-danger position-absolute top-0 end-0 m-2" style="font-size: 0.65rem; border-radius: 20px;">
                      <i class="bi bi-geo-alt-fill me-1"></i>{{ ucfirst($tr->area_name) }}
                    </span>
                  </div>
                  <div class="mt-2 text-center">
                    <h6 class="fw-bold text-dark text-truncate mb-0 px-1" style="font-size: 0.9rem;">{{ $tr->name }}</h6>
                  </div>
                  </a>
                </div>
            @empty
                <div class="col-12 text-center py-4"><p class="text-muted">No destinations found for Ubud.</p></div>
            @endforelse
            </div>
          </div><!-- End Breakfast Menu Content -->
          <div class="tab-pane fade" id="menu-south">
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">
            @forelse ($destinasi->where('type', '2') as $tr)
                <div class="col">
                  <a href="/destinations/{{$tr->slug}}" class="destination-card d-block">
                  <div class="destination-img-container rounded-3 overflow-hidden shadow-sm">
                    @php $gmbr = explode(";",$tr->foto) ; @endphp
                    <img src="{{ asset('assets/img/destinasi/' . ($gmbr[0] ?? '')) }}" alt="{{ $tr->name }}">
                    <span class="badge bg-danger position-absolute top-0 end-0 m-2" style="font-size: 0.65rem; border-radius: 20px;">
                      <i class="bi bi-geo-alt-fill me-1"></i>{{ ucfirst($tr->area_name) }}
                    </span>
                  </div>
                  <div class="mt-2 text-center">
                    <h6 class="fw-bold text-dark text-truncate mb-0 px-1" style="font-size: 0.9rem;">{{ $tr->name }}</h6>
                  </div>
                  </a>
                </div>
            @empty
                <div class="col-12 text-center py-4"><p class="text-muted">No destinations found for South Bali.</p></div>
            @endforelse
            </div>
          </div><!-- End Breakfast Menu Content -->
          <div class="tab-pane fade" id="menu-north">
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">
            @forelse ($destinasi->where('type', '3') as $tr)
                <div class="col">
                  <a href="/destinations/{{$tr->slug}}" class="destination-card d-block">
                  <div class="destination-img-container rounded-3 overflow-hidden shadow-sm">
                    @php $gmbr = explode(";",$tr->foto) ; @endphp
                    <img src="{{ asset('assets/img/destinasi/' . ($gmbr[0] ?? '')) }}" alt="{{ $tr->name }}">
                    <span class="badge bg-danger position-absolute top-0 end-0 m-2" style="font-size: 0.65rem; border-radius: 20px;">
                      <i class="bi bi-geo-alt-fill me-1"></i>{{ ucfirst($tr->area_name) }}
                    </span>
                  </div>
                  <div class="mt-2 text-center">
                    <h6 class="fw-bold text-dark text-truncate mb-0 px-1" style="font-size: 0.9rem;">{{ $tr->name }}</h6>
                  </div>
                  </a>
                </div>
            @empty
                <div class="col-12 text-center py-4"><p class="text-muted">No destinations found for North Bali.</p></div>
            @endforelse
            </div>
          </div><!-- End Breakfast Menu Content -->
          <div class="tab-pane fade" id="menu-east">
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">
            @forelse ($destinasi->where('type', '4') as $tr)
                <div class="col">
                  <a href="/destinations/{{$tr->slug}}" class="destination-card d-block">
                  <div class="destination-img-container rounded-3 overflow-hidden shadow-sm">
                    @php $gmbr = explode(";",$tr->foto) ; @endphp
                    <img src="{{ asset('assets/img/destinasi/' . ($gmbr[0] ?? '')) }}" alt="{{ $tr->name }}">
                    <span class="badge bg-danger position-absolute top-0 end-0 m-2" style="font-size: 0.65rem; border-radius: 20px;">
                      <i class="bi bi-geo-alt-fill me-1"></i>{{ ucfirst($tr->area_name) }}
                    </span>
                  </div>
                  <div class="mt-2 text-center">
                    <h6 class="fw-bold text-dark text-truncate mb-0 px-1" style="font-size: 0.9rem;">{{ $tr->name }}</h6>
                  </div>
                  </a>
                </div>
            @empty
                <div class="col-12 text-center py-4"><p class="text-muted">No destinations found for East Bali.</p></div>
            @endforelse
            </div>
          </div><!-- End Breakfast Menu Content -->
          <div class="tab-pane fade" id="menu-kintamani">
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">
            @forelse ($destinasi->where('type', '5') as $tr)
                <div class="col">
                  <a href="/destinations/{{$tr->slug}}" class="destination-card d-block">
                  <div class="destination-img-container rounded-3 overflow-hidden shadow-sm">
                    @php $gmbr = explode(";",$tr->foto) ; @endphp
                    <img src="{{ asset('assets/img/destinasi/' . ($gmbr[0] ?? '')) }}" alt="{{ $tr->name }}">
                    <span class="badge bg-danger position-absolute top-0 end-0 m-2" style="font-size: 0.65rem; border-radius: 20px;">
                      <i class="bi bi-geo-alt-fill me-1"></i>{{ ucfirst($tr->area_name) }}
                    </span>
                  </div>
                  <div class="mt-2 text-center">
                    <h6 class="fw-bold text-dark text-truncate mb-0 px-1" style="font-size: 0.9rem;">{{ $tr->name }}</h6>
                  </div>
                  </a>
                </div>
            @empty
                <div class="col-12 text-center py-4"><p class="text-muted">No destinations found for Kintamani.</p></div>
            @endforelse
            </div>
          </div><!-- End Breakfast Menu Content -->
          <div class="tab-pane fade" id="menu-west">
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">
            @forelse ($destinasi->where('type', '6') as $tr)
                <div class="col">
                  <a href="/destinations/{{$tr->slug}}" class="destination-card d-block">
                  <div class="destination-img-container rounded-3 overflow-hidden shadow-sm">
                    @php $gmbr = explode(";",$tr->foto) ; @endphp
                    <img src="{{ asset('assets/img/destinasi/' . ($gmbr[0] ?? '')) }}" alt="{{ $tr->name }}">
                    <span class="badge bg-danger position-absolute top-0 end-0 m-2" style="font-size: 0.65rem; border-radius: 20px;">
                      <i class="bi bi-geo-alt-fill me-1"></i>{{ ucfirst($tr->area_name) }}
                    </span>
                  </div>
                  <div class="mt-2 text-center">
                    <h6 class="fw-bold text-dark text-truncate mb-0 px-1" style="font-size: 0.9rem;">{{ $tr->name }}</h6>
                  </div>
                  </a>
                </div>
            @empty
                <div class="col-12 text-center py-4"><p class="text-muted">No destinations found for West Bali.</p></div>
            @endforelse
            </div>
          </div><!-- End Breakfast Menu Content -->

          

          

        </div>

      </div>
    </section>
    
    <!-- End Menu Section -->

    <!-- Button trigger modal -->


    


    

    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
      <div class="container">

        <div class="section-header">
          <h2>Contact</h2>
          <p>Need Help? <span>Contact Us</span></p>
        </div>

        <!-- End Google Maps -->

        <div class="row gy-4">

          <!-- <div class="col-md-6">
            <div class="info-item  d-flex align-items-center">
              <i class="icon bi bi-map flex-shrink-0"></i>
              <div>
                <h3>Our Office Address</h3>
                <p>AJalan Genta Sari Blok IV Gang Amerta Sari No 7 <br>
              Dalung Badung Bali- ID 80361</p>
              </div>
            </div>
          </div> -->
          <!-- End Info Item -->

          <!-- <div class="col-md-6">
            <div class="info-item d-flex align-items-center">
              <i class="icon bi bi-envelope flex-shrink-0"></i>
              <div>
                <h3>Email Us</h3>
                <p>the.swand26@gmail.com</p>
              </div>
            </div>
          </div> -->
          <!-- End Info Item -->

          <!-- <div class="col-md-6">
            <div class="info-item  d-flex align-items-center">
              <i class="icon bi bi-telephone flex-shrink-0"></i>
              <div>
                <h3>Call Us</h3>
                <p>+62 8234 006 4488</p>
              </div>
            </div>
          </div> -->
          <!-- End Info Item -->

          <!-- <div class="col-md-6">
            <div class="info-item  d-flex align-items-center">
              <i class="icon bi bi-share flex-shrink-0"></i>
              <div>
                <h3>Opening Hours</h3>
                <div><strong>Mon-Sat:</strong> 11AM - 23PM;
                  <strong>Sunday:</strong> Closed
                </div>
              </div>
            </div>
          </div> -->
          <!-- End Info Item -->

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