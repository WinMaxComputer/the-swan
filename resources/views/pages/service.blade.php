@extends('layouts.default')
@section('meta')
    <title>The Swand - Room Booking, Travel and Management Hospitality</title>
    <meta content="we are company that acomodate room booking, tour travel and also property management" name="description">
    <meta content="bali room booking, car rental bali, bali management property" name="keywords">
@endsection
@section('content')
    

    <!-- ======= Menu Section ======= -->
    <section id="menu" class="menu">
      <div class="container">

        <div class="section-header">
          <!-- <h2>Our Menu</h2> -->
          <p>Check Our <span>Best Booking Service</span></p>
        </div>

        <ul class="nav nav-pills service-tabs d-flex justify-content-center" role="tablist">

          <li class="nav-item">
            <a class="nav-link active show" href="#menu-bookings" role="tab" data-bs-toggle="tab" aria-controls="menu-bookings" aria-selected="true">
              <span class="service-tab-icon"><i class="fa-solid fa-hotel"></i></span>
              <span class="service-tab-label">Bookings</span>
            </a>
          </li><!-- End tab nav item -->

          <li class="nav-item">
            <a class="nav-link" href="#menu-transport" role="tab" data-bs-toggle="tab" aria-controls="menu-transport" aria-selected="false">
              <span class="service-tab-icon"><i class="fa-solid fa-car"></i></span>
              <span class="service-tab-label">Transport</span>
            </a>
          </li><!-- End tab nav item -->

          <li class="nav-item">
            <a class="nav-link" href="#menu-tour" role="tab" data-bs-toggle="tab" aria-controls="menu-tour" aria-selected="false">
              <span class="service-tab-icon"><i class="fa-solid fa-person-walking-luggage"></i></span>
              <span class="service-tab-label">Tour</span>
            </a>
          </li><!-- End tab nav item -->

          <li class="nav-item">
            <a class="nav-link" href="#menu-package" role="tab" data-bs-toggle="tab" aria-controls="menu-package" aria-selected="false">
              <span class="service-tab-icon"><i class="fa-solid fa-people-roof"></i></span>
              <span class="service-tab-label">Package</span>
            </a>
          </li>
          <!-- End tab nav item -->

        </ul>

        <div class="tab-content chefs">

          <div class="tab-pane fade active show" id="menu-bookings">

            <div class="tab-header text-center mb-4">
              <h3>Stay in comfort</h3>
              <p>Choose your ideal room and book with confidence.</p>
            </div>

            <div class="row gy-4 mb-4">
              <div class="col-12">
                <div class="chef-member p-4 bg-white shadow-sm rounded-4">
                  <form action="forms/book-a-table.php" method="post" role="form" class="php-email-form">
                    <div class="row gy-3 align-items-end">
                      <div class="col-lg-5 form-group">
                        <label class="form-label text-muted">Check In - Check Out</label>
                        <input type="text" class="form-control" name="cekin" id="cekin" aria-describedby="basic-addon1" placeholder="Select dates">
                      </div>
                      <div class="col-lg-4 form-group">
                        <label class="form-label text-muted">Area</label>
                        <select class="form-control" name="area">
                          <option value="">All Area</option>
                          @foreach($areas as $area)
                            <option value="{{ $area->name }}">{{ $area->name }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-lg-3 text-lg-end">
                        <button type="submit" class="btn-book-a-table">Search Rooms</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <div class="row gy-4">
              @foreach ($kamar as $detail)
                <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                  <div class="chef-member service-card">
                    <div class="member-img">
                      @php $gmbr = explode(";", $detail->foto); @endphp
                      <div class="position-absolute top-0 start-0 m-3" style="z-index: 1;">
                        <span class="badge bg-danger text-uppercase fw-bold shadow-sm" style="font-size: 0.65rem;">Hot Deal</span>
                      </div>
                      <button type="button" class="btn btn-sm btn-light rounded-circle position-absolute top-0 end-0 m-3 shadow-sm border-0 d-flex align-items-center justify-content-center" style="z-index: 1; width: 30px; height: 30px;" title="Add to Wishlist">
                        <i class="bi bi-heart text-danger" style="font-size: 0.9rem;"></i>
                      </button>
                      <img src="assets/img/rooms/{{ $gmbr[1] ?? $gmbr[0] }}" class="img-fluid" alt="{{ $detail->title }}">
                      <div class="social">
                        <a href="" data-toggle="modal" data-target="#exampleModal{{$detail->id}}" alt="Preview"><i class="bi bi-eye"></i></a>
                      </div>
                    </div>
                    <div class="member-info">
                      <h4>{{ $detail->title }}</h4>
                      @php 
                        $review = DB::table('review_ratings')->where('product_code', $detail->code)->sum('star_rating');
                        $count = DB::table('review_ratings')->where('product_code', $detail->code)->count('star_rating');
                        $rating = $count ? round($review / $count) : 0;
                      @endphp
                      <div class="d-flex align-items-center mb-2" style="font-size: 0.85rem;">
                        <div class="d-flex align-items-center me-3">
                          <div class="text-warning me-1">
                            @for($i = 1; $i <= 5; $i++)
                              <i class="bi {{ $i <= $rating ? 'bi-star-fill' : 'bi-star' }}" style="font-size: 0.75rem;"></i>
                            @endfor
                          </div>
                          <small class="text-muted">({{$count}} Reviews)</small>
                        </div>
                        <div class="d-flex align-items-center border-start ps-3" style="border-color: #ddd !important;">
                          <i class="bi bi-geo-alt-fill me-1" style="color: #ce1212; font-size: 0.9rem;"></i>
                          <small class="text-muted">{{ $detail->area_name ?? 'Bali' }}</small>
                        </div>
                      </div>
                      <p class="fst-italic mb-3">{!! strip_tags(substr($detail->desc, 0, 120)) !!}...</p>

                      <div class="service-tags mb-3">
                        @php $fasi = explode(";", $detail->facility); @endphp
                        @for ($i = 1; $i < 4; $i++)
                          @foreach ($fasilitas as $fas)
                            @if(isset($fasi[$i]) && $fasi[$i] == $fas->id)
                              <span class="badge bg-light text-dark me-1 mb-1">{!! $fas->icon !!}</span>
                            @endif
                          @endforeach
                        @endfor
                      </div>

                      @foreach ($rate as $rat)
                        @if($detail->code == $rat->kode_kamar)
                          <div class="d-flex justify-content-between align-items-center service-actions">
                            <div>
                              <strong>IDR {{ number_format($rat->harga, 2) }}</strong><br>
                              <small class="text-muted">{{ $rat->stok }} room(s) available</small>
                            </div>
                            <a href="/bookings/{{$detail->slug}}" class="btn-book-a-table">Book Now</a>
                          </div>
                        @endif
                      @endforeach
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

            <!-- Modal -->
            @foreach ($kamar as $detail)
            <div class="modal fade" id="exampleModal{{$detail->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                  
                    <div class="row gy-4">
                      <div class="col-md-6">
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                          <ol class="carousel-indicators">

                            @php $gmbra = explode(";",$detail->foto) ; @endphp
                            @php $gmbr = array_slice($gmbra, 0, -1) ; @endphp
                            @foreach($gmbr as $value)
                            <li data-target=".carouselExampleCaptions" data-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"></li>
                            @endforeach
                            <!-- <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li> -->
                            
                          </ol>
                          <div class="carousel-inner">
                            
                            @foreach($gmbr as $key => $slider)
                            <div class="carousel-item {{$key == 0 ? 'active' : ''}}">
                              <img src="assets/img/rooms/{{ $slider }}" class="d-block w-100" alt="">
                            </div>
                            @endforeach
                            
                          </div>
                          <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                          </a>
                          <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                          </a>
                        </div>
                      </div><!-- End Info Item -->

                      <div class="col-md-6">
                        <div class="info-item d-flex align-items-left">
                          <div>
                            <p>{!! $detail->desc !!}</p>
                          </div>
                        </div>
                      </div><!-- End Info Item -->
                    </div>

                    
                  </div>
                  <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                  </div> -->
                </div>
              </div>
            </div>
            @endforeach

              

            </div>
          </div><!-- End Starter Menu Content -->

          <div class="tab-pane fade" id="menu-transport">

            <div class="tab-header text-center mb-4">
              <h3>Transport Services</h3>
              <p>Travel in style with private cars and professional drivers.</p>
            </div>

            <div class="row gy-4">
              @foreach ($transport as $tr)
                <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
                  <div class="chef-member service-card">
                    <div class="member-img">
                      @php $gmbr = explode(";", $tr->foto); @endphp
                      <img src="assets/img/transport/{{ $gmbr[0] ?? '' }}" class="img-fluid" alt="{{ $tr->nama }}">
                    </div>
                    <div class="member-info">
                      <h4>{{ $tr->nama }}</h4>
                      <p class="fst-italic">{{ substr($tr->deskripsi, 0, 140) }}</p>
                      <div class="service-tags mb-3">
                        @php $fs = explode(",", $tr->fasilitas); @endphp
                        @foreach ($fs as $fas)
                          <span class="badge bg-light text-dark me-1 mb-1"><i class="bi bi-check2-all"></i> {{ trim($fas) }}</span>
                        @endforeach
                      </div>
                      <div class="d-flex justify-content-between align-items-center service-actions">
                        <div>
                          <strong>IDR {{ number_format($tr->harga, 2) }}</strong><br>
                          <small class="text-muted">for {{ $tr->waktu }} Hours</small>
                        </div>
                        <a href="https://api.whatsapp.com/send?phone=+62818688114&text=Halo" target="_blank" class="btn-book-a-table">Book Now</a>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div><!-- End Breakfast Menu Content -->

          <div class="tab-pane fade" id="menu-tour">

            <div class="tab-header text-center mb-4">
              <h3>Tour Experiences</h3>
              <p>Discover Bali with our curated tour packages.</p>
            </div>
            <div class="row gy-5">

              @foreach ($tour as $tur)
                <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
                  <div class="chef-member service-card">
                    <div class="member-img">
                      @php $gmbr = explode(";", $tur->foto); @endphp
                      <img src="assets/img/tour/{{ $gmbr[0] ?? '' }}" class="img-fluid" alt="{{ $tur->tour_name }}">
                    </div>
                    <div class="member-info">
                      <h4>{{ $tur->tour_name }}</h4>
                      <p class="fst-italic">{!! strip_tags(substr($tur->deskripsi, 0, 120)) !!}...</p>
                      <div class="d-flex justify-content-between align-items-center service-actions">
                        <a href="/tour_packages/{{$tur->slug}}" class="btn-book-a-table">View Details</a>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach

            </div>
          </div><!-- End Lunch Menu Content -->

          <div class="tab-pane fade" id="menu-package">

            <div class="tab-header text-center mb-4">
              <h3>Holiday Packages</h3>
              <p>Get the best value with our full packages.</p>
            </div>

            <div class="row gy-4">
              @foreach ($paket as $paket)
                <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
                  <div class="chef-member service-card px-3 py-4">
                    <div class="member-info">
                      <h4 class="mb-3">{{ $paket->name }}</h4>
                      <p class="mb-3">{!! strip_tags(substr($paket->deskripsi, 0, 120)) !!}...</p>
                      <div class="service-actions">
                        <div>
                          <strong>IDR {{ number_format($paket->price, 2) }}</strong>
                        </div>
                        <a href="#" class="btn-book-a-table">More Info</a>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div><!-- End Lunch Menu Content -->

          

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

        <div class="mb-3">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d986.1957654155755!2d115.17408542285924!3d-8.6168148017417!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd239e1f895d5b3%3A0xad8aa8fe6cf83d1d!2sPerumahan%20Pesona%20gaji%20Dalung%20Block%204!5e0!3m2!1sid!2sid!4v1710597857163!5m2!1sid!2sid" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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