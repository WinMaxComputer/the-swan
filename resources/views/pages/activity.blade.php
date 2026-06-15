@extends('layouts.default')
@section('meta')
    <title>Bali Best Place To join with us - The Swand</title>
    <meta content="bali activities, all things to do in bali with the swand" name="description">
    <meta content="rafting, car rental bali,waterfall, ubud tour, bali tour" name="keywords">
@endsection
@section('content')
    
    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Bali Activities</h2>
          <ol>
            <li><a href="/">Home</a></li>
            <li>All Bali Activities</li>
          </ol>
        </div>

      </div>
    </div><!-- End Breadcrumbs -->

    <!-- ======= Menu Section ======= -->
    <section id="menu" class="menu">
      <div class="container">

        <div class="section-header">
          <h3>{{ __('activities.title') }}</h3>
          <p><span>{{ __('activities.sub_title') }}</span></p>
        </div>
        <p>{{ __('activities.desc') }}</p>

        <form action="{{ url('/activities') }}" method="GET" class="mb-4">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6">
              <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search activities..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-search"></i>
                </button>
              </div>
            </div>
            @if(request('search'))
              <div class="col-lg-2 col-md-2">
                <a href="{{ url('/activities') }}" class="btn btn-outline-secondary">Clear</a>
              </div>
            @endif
          </div>
        </form>

        <ul class="nav nav-tabs d-flex justify-content-center">
          <li class="nav-item ">
            <a class="nav-link active show" data-bs-toggle="tab" data-bs-target="#menu-land">
              <h4>{{ __('activities.land_activity') }}</h4>
            </a>
          </li><!-- End tab nav item -->
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#menu-water">
              <h4>{{ __('activities.water_activity') }}</h4>
            </a><!-- End tab nav item -->
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#menu-air">
              <h4>{{ __('activities.air_activity') }}</h4>
            </a>
          </li><!-- End tab nav item -->
        </ul>

<div class="tab-content chefs">

          <div class="tab-pane fade active show" id="menu-land">
            <div class="tab-header text-center">
              <!-- <p>Menu</p> -->
              <!-- <h3>Transport</h3> -->
            </div>
            <div class="row gy-3">
            @forelse ($activity as $tr)
            @if($tr->type == 'land')
              <!-- Menu Item -->
              <div class="col-lg-3 col-md-4 d-flex align-items-stretch">
                <div class="chef-member">
                  <div class="member-img">
                    @php $gmbr = explode(";",$tr->foto) ; @endphp
                    <img src="assets/img/activity/{{ $gmbr[0] }}" class="img-fluid" alt="">
                    <div class="social">
                    </div>
                  </div>
                  <div class="member-info">
                    <h4>{{ $tr->name }}</h4>
                    <!-- <span>Cook</span> -->
                    <p>{!! cutText($tr->deskripsi, 300, 3) !!}</p>
                    <a href="/activities/{{$tr->slug}}" class="btn-book-a-table">Detail</a>
                  </div>
                </div>
              </div><!-- End Chefs Member -->
              @endif
            @empty
              <div class="col-12 text-center py-4">
                <p class="text-muted">No land activities found.</p>
              </div>
            @endforelse
            </div>
          </div><!-- End Breakfast Menu Content -->

          <div class="tab-pane fade" id="menu-water">
            <div class="tab-header text-center">
              <!-- <p>Menu</p> -->
              <!-- <h3>Transport</h3> -->
            </div>
            <div class="row gy-3">
            @forelse ($activity as $tr)
            @if($tr->type == 'water')
              <!-- Menu Item -->
              <div class="col-lg-3 col-md-4 d-flex align-items-stretch" >
                <div class="chef-member">
                  <div class="member-img">
                    @php $gmbr = explode(";",$tr->foto) ; @endphp
                    <img src="assets/img/activity/{{ $gmbr[0] }}" class="img-fluid" alt="">
                    <div class="social">
                    </div>
                  </div>
                  <div class="member-info">
                    <h4>{{ $tr->name }}</h4>
                    <!-- <span>Cook</span> -->
                    <p>{!! cutText($tr->deskripsi, 300, 3) !!}</p>
                  </div>
                  <p class="price">
                    <div >
                      <a href="/activities/{{$tr->slug}}" class="btn-book-a-table">Detail</a>
                    </div>
                  </p>
                </div>
              </div><!-- End Chefs Member -->
              @endif
            @empty
              <div class="col-12 text-center py-4">
                <p class="text-muted">No water activities found.</p>
              </div>
            @endforelse
            </div>
          </div><!-- End Breakfast Menu Content -->

          <div class="tab-pane fade" id="menu-air">
            <div class="tab-header text-center">
              <!-- <p>Menu</p> -->
              <!-- <h3>Transport</h3> -->
            </div>
            <div class="row gy-3">
            @forelse ($activity as $tr)
            @if($tr->type == 'air')
              <!-- Menu Item -->
              <div class="col-lg-3 col-md-4 d-flex align-items-stretch" >
                <div class="chef-member">
                  <div class="member-img">
                    @php $gmbr = explode(";",$tr->foto) ; @endphp
                    <img src="assets/img/activity/{{ $gmbr[0] }}" class="img-fluid" alt="">
                    <div class="social">
                    </div>
                  </div>
                  <div class="member-info">
                    <h4>{{ $tr->name }}</h4>
                    <!-- <span>Cook</span> -->
                    <p>{!! cutText($tr->deskripsi, 300, 3) !!}</p>
                  </div>
                  <p class="price">
                    <div >
                      <a href="/activities/{{$tr->slug}}" class="btn-book-a-table">Detail</a>
                    </div>
                  </p>
                </div>
              </div><!-- End Chefs Member -->
              @endif
            @empty
              <div class="col-12 text-center py-4">
                <p class="text-muted">No air activities found.</p>
              </div>
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