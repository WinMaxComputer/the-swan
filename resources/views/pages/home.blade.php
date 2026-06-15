@extends('layouts.default')
@section('meta')
    <title>The Swand - Room Booking, Travel and Management Hospitality</title>
    <meta content="we are company that acomodate room booking, tour travel and also property management" name="description">
    <meta content="bali room booking, car rental bali, bali management property" name="keywords">
@endsection

@section('media')
<style>
    .timeline-item {
        height: auto;
        padding-bottom: 40px;
    }
    .timeline-content {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.1);
        transition: 0.3s;
        height: 100%;
    }
    .timeline-info h4 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--color-secondary);
    }
    .timeline-info p {
        font-size: 0.85rem;
        margin-bottom: 0;
    }
    .timeline-content:hover {
        transform: translateY(-10px);
    }
    .timeline-img {
        position: relative;
        height: 160px;
        overflow: hidden;
    }
    .timeline-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .date-badge {
        position: absolute;
        bottom: 0;
        right: 0;
        background: #ce1212;
        color: #fff;
        padding: 3px 10px;
        font-size: 0.75rem;
        font-weight: 600;
        border-top-left-radius: 10px;
    }

    .search-panel {
        background: #fff;
        padding: 10px 15px;
        border-radius: 12px;
        box-shadow: 0px 5px 25px rgba(0, 0, 0, 0.08);
        margin-bottom: 40px;
    }
    .search-panel .input-group-text {
        background: transparent;
        border: none;
        color: #ce1212;
        padding-right: 0;
    }
    .search-panel .form-control, .search-panel .form-select {
        border: none;
        font-size: 0.9rem;
    }
    .search-panel .form-control:focus, .search-panel .form-select:focus {
        box-shadow: none;
    }
    .btn-search {
        background: #ce1212;
        color: #fff;
        border-radius: 8px;
        padding: 6px 18px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: 0.3s;
    }
    .btn-search:hover {
        background: #e02a2a;
        color: #fff;
    }
    .form-note {
        font-size: 0.8rem;
        color: #777;
        margin-top: 10px;
    }

    .destination-card {
        transition: 0.3s;
        text-decoration: none;
    }
    .destination-card:hover {
        transform: translateY(-5px);
    }
    .destination-img-container {
        height: 160px;
        position: relative;
    }
    .destination-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .popular-areas .areas-swiper {
        padding: 10px 5px;
    }
    .popular-areas .area-card {
        text-decoration: none;
        transition: 0.3s;
    }
    .popular-areas .area-card:hover {
        transform: translateY(-5px);
    }
    .popular-areas .area-img-container {
        height: 140px;
        position: relative;
    }
    .popular-areas .area-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .popular-areas .area-card:hover .area-img-container img {
        transform: scale(1.08);
    }
    .popular-areas .area-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .popular-areas .area-card:hover .area-overlay {
        opacity: 1;
    }
    .popular-areas .area-count {
        color: #fff;
        font-size: 0.85rem;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.2);
        padding: 6px 14px;
        border-radius: 20px;
        backdrop-filter: blur(4px);
    }
    .popular-areas .btn-view-all {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 12px 32px;
        border-radius: 50px;
        background: var(--color-primary);
        color: #fff;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 2px solid var(--color-primary);
    }
    .popular-areas .btn-view-all:hover {
        background: #fff;
        color: var(--color-primary);
    }
    .popular-areas .btn-view-all .btn-arrow {
        transition: transform 0.3s ease;
    }
    .popular-areas .btn-view-all:hover .btn-arrow {
        transform: translateX(5px);
    }

    .products-slider .products-swiper {
        padding: 30px 10px 50px;
    }
    .products-slider .product-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;
    }
    .products-slider .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    }
    .products-slider .product-thumb {
        height: 180px;
        overflow: hidden;
    }
    .products-slider .product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .products-slider .product-card:hover .product-thumb img {
        transform: scale(1.1);
    }
    .products-slider .product-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: var(--color-primary);
        color: #fff;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        text-transform: uppercase;
    }
    .products-slider .product-info {
        padding: 18px 20px;
    }
    .products-slider .product-title {
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 0.6rem;
        color: var(--color-secondary);
    }
    .products-slider .product-desc {
        font-size: 0.85rem;
        color: rgba(55, 55, 63, 0.75);
        margin-bottom: 1rem;
        line-height: 1.5;
    }
    .products-slider .btn-product {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 8px 20px;
        border-radius: 50px;
        background: var(--color-primary);
        color: #fff;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid var(--color-primary);
    }
    .products-slider .btn-product:hover {
        background: #fff;
        color: var(--color-primary);
    }
    .products-slider .btn-product .product-arrow {
        transition: transform 0.3s ease;
    }
    .products-slider .btn-product:hover .product-arrow {
        transform: translateX(5px);
    }
    .products-slider .products-nav {
        color: var(--color-primary);
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    .products-slider .products-nav::after {
        font-size: 18px;
    }
    .products-slider .products-nav:hover {
        background: var(--color-primary);
    }
    .products-slider .products-nav:hover::after {
        color: #fff;
    }

    /* Enhanced visibility for inner-card carousel controls */
    .card-carousel .carousel-control-prev,
    .card-carousel .carousel-control-next {
        z-index: 5 !important;
        width: 30px;
        height: 30px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.5) !important;
        border-radius: 50%;
        opacity: 0; /* Hidden until hover */
        transition: all 0.3s ease;
    }

    .card-carousel .carousel-control-prev { left: 8px; }
    .card-carousel .carousel-control-next { right: 8px; }

    .product-card:hover .carousel-control-prev,
    .product-card:hover .carousel-control-next,
    .service-card:hover .carousel-control-prev,
    .service-card:hover .carousel-control-next {
        opacity: 1;
    }
</style>
@endsection

@section('content')
   
    <!-- ======= News Timeline Slider Section ======= -->
    <section id="hero" class="hero d-flex align-items-center section-bg">
      <div class="container">
        <div class="section-header">
          <h5>Our Journey</h5>
          <p>The Swand <span>Latest Updates</span></p>
        </div>

        <div class="timeline-swiper swiper">
          <div class="swiper-wrapper">

            @foreach ($artikel as $item)
            <div class="swiper-slide timeline-item">
              <div class="timeline-content">
                <div class="timeline-img">
                  @php
                    $fotos = explode(';', $item->foto);
                    $imagePath = 'assets/img/news/' . ($fotos[0] ?? '');
                    $formattedDate = \Carbon\Carbon::parse($item->created_at)->format('M Y');
                  @endphp
                  <img src="{{ asset($imagePath) }}" class="img-fluid" alt="{{ $item->judul }}">
                  <div class="date-badge">{{ $formattedDate }}</div>
                </div>
                <div class="timeline-info p-3">
                  <h4>{{ $item->judul }}</h4>
                  <p>{{ Str::limit(strip_tags($item->isi), 80) }}</p>
                </div>
              </div>
            </div>
            @endforeach

          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </section><!-- End Hero Section (Timeline) -->

    <!-- ======= Why Us Section ======= -->
    <section id="why-us" class="why-us section-bg">
      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-4">
            <div class="why-box">
              <h4>{{ __('whyus.why') }}</h4>
              <p>{{ __('whyus.why_desc') }}</p>
              <div class="text-center">
                <a href="#" class="more-btn">Learn More <i class="bx bx-chevron-right"></i></a>
              </div>
            </div>
          </div><!-- End Why Box -->

          <div class="col-lg-8 d-flex align-items-center">
            <div class="row gy-4">

              <div class="col-xl-4">
                <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                  <i class="bi bi-clipboard-data"></i>
                  <h4>{{ __('whyus.prop_growth_title') }}</h4>
                  <p>{{ __('whyus.prop_growth_desc') }}</p>
                </div>
              </div><!-- End Icon Box -->

              <div class="col-xl-4">
                <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                  <i class="bi bi-gem"></i>
                  <h4>{{ __('whyus.vacation_rental_title') }}</h4>
                  <p>{{ __('whyus.vacation_rental_desc') }}</p>
                </div>
              </div><!-- End Icon Box -->

              <div class="col-xl-4">
                <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                  <i class="bi bi-inboxes"></i>
                  <h4>{{ __('whyus.tours_title') }}</h4>
                  <p>{{ __('whyus.tours_desc') }}</p>
                </div>
              </div><!-- End Icon Box -->

            </div>
          </div>

        </div>

      </div>
    </section><!-- End Why Us Section -->

    <!-- ======= Stats Counter Section ======= -->
    <section id="stats-counter" class="stats-counter">
      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-3 col-md-6">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="232" data-purecounter-duration="1" class="purecounter"></span>
              <p>Clients</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="521" data-purecounter-duration="1" class="purecounter"></span>
              <p>Projects</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="1453" data-purecounter-duration="1" class="purecounter"></span>
              <p>Hours Of Support</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="32" data-purecounter-duration="1" class="purecounter"></span>
              <p>Workers</p>
            </div>
          </div><!-- End Stats Item -->

        </div>

      </div>
    </section><!-- End Stats Counter Section -->

    <!-- ======= Destinations Section ======= -->
    <!-- <section id="popular-destinations" class="destinations section-bg pt-0">
      <div class="container">
        <div class="section-header">
          <p>Explore <span>Bali Destinations</span></p>
        </div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">
          @foreach($destination->take(10) as $desti)
            @php $fotos = explode(';', $desti->foto); @endphp
            <div class="col">
              <a href="/destinations/{{$desti->slug}}" class="destination-card d-block">
                <div class="destination-img-container rounded-3 overflow-hidden shadow-sm">
                  <img src="{{ asset('assets/img/destinasi/' . ($fotos[0] ?? '')) }}" alt="{{ $desti->name }}">
                  <span class="badge bg-danger position-absolute top-0 end-0 m-2" style="font-size: 0.65rem; border-radius: 20px;">
                    <i class="bi bi-geo-alt-fill me-1"></i>{{ ucfirst($desti->type) }}
                  </span>
                </div>
                <div class="mt-2 text-center">
                  <h6 class="fw-bold text-dark text-truncate mb-0 px-1" style="font-size: 0.9rem;">{{ $desti->name }}</h6>
                </div>
              </a>
            </div>
          @endforeach
        </div>
      </div>
    </section> -->
    <!-- End Destinations Section -->

    

    <!-- ======= Menu Section ======= -->
    <section id="menu" class="menu">
      <div class="container">

        <div class="section-header">
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

            <div class="tab-header text-center">
            </div>

            <div class="row gy-5">
              <!-- {{$kamar}} -->
              <div class="col-12">
                <div class="search-panel">
                  <form action="{{ url('/hotels') }}" method="GET">
                    <div class="row align-items-center g-2">
                      <div class="col-lg-5 col-md-5">
                        <div class="input-group">
                          <span class="input-group-text"><i class="bi bi-calendar-range"></i></span>
                          <input type="text" class="form-control" name="cekin" placeholder="Select dates">
                        </div>
                      </div>
                      <div class="col-lg-5 col-md-5">
                        <div class="input-group">
                          <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                          <select class="form-select" id="area" name="area">
                            <option value="" selected>All Area</option>
                            @foreach($areas as $area)
                              <option value="{{ $area->name }}">{{ $area->name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-2 col-md-2 text-center">
                        <button type="submit" class="btn-search">Search</button>
                      </div>
                    </div>
                    <p class="form-note mb-0 text-center">Find the best rooms, transport and tour packages in one place.</p>
                  </form>
                </div>
              </div>

            <div class="row gy-4">
              @foreach ($kamar as $detail)
                @php $gmbr = array_filter(explode(";", $detail->foto)); @endphp
                <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                  <div class="chef-member service-card">
                    <div class="member-img">
                      <div id="carouselKamar{{ $detail->id }}" class="carousel slide card-carousel" data-bs-ride="false" data-bs-interval="false" data-bs-touch="true">
                        <div class="carousel-inner">
                          @foreach($gmbr as $index => $foto)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                              <img src="{{ asset('assets/img/rooms/' . $foto) }}" class="img-fluid" alt="{{ $detail->title }}" style="width: 100%; height: 180px; object-fit: cover;">
                            </div>
                          @endforeach
                        </div>
                        @if(count($gmbr) > 1)
                          <button class="carousel-control-prev" type="button" data-bs-target="#carouselKamar{{ $detail->id }}" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true" style="width: 0.8rem; height: 0.8rem;"></span>
                            <span class="visually-hidden">Previous</span>
                          </button>
                          <button class="carousel-control-next" type="button" data-bs-target="#carouselKamar{{ $detail->id }}" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true" style="width: 0.8rem; height: 0.8rem;"></span>
                            <span class="visually-hidden">Next</span>
                          </button>
                        @endif
                      </div>
                      <div class="position-absolute top-0 start-0 m-3" style="z-index: 2;">
                        <span class="badge bg-danger text-uppercase fw-bold shadow-sm" style="font-size: 0.65rem;">Hot Deal</span>
                      </div>
                      <button type="button" class="btn btn-sm btn-light rounded-circle position-absolute top-0 end-0 m-3 shadow-sm border-0 d-flex align-items-center justify-content-center" style="z-index: 2; width: 30px; height: 30px;" title="Add to Wishlist">
                        <i class="bi bi-heart text-danger" style="font-size: 0.9rem;"></i>
                      </button>
                    </div>
                    <div class="member-info">
                      <a href="/bookings/{{$detail->slug}}"><h4>{{ $detail->title }}</h4></a>
                      @php
                        $review = DB::table('review_ratings')->where('product_code', $detail->code)->sum('star_rating');
                        $count = DB::table('review_ratings')->where('product_code', $detail->code)->count('star_rating');
                        $rating = $count ? round($review / $count) : 0;
                      @endphp
                      <div class="d-flex align-items-center mb-2" style="font-size: 0.65rem;">
                        <div class="d-flex align-items-center me-3">
                          <div class="text-warning me-1">
                            @for($i = 1; $i <= 5; $i++)
                              <i class="bi {{ $i <= $rating ? 'bi-star-fill' : 'bi-star' }}" style="font-size: 0.65rem;"></i>
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
                              <span class="facility-badge">
                                {!! $fas->icon !!}
                                <span>{{ $fas->fas_name }}</span>
                              </span>
                            @endif
                          @endforeach
                        @endfor
                      </div>

                      @foreach ($rate as $rat)
                        @if($detail->code == $rat->kode_kamar)
                          <div class="d-flex justify-content-between align-items-center service-actions">
                            <div>
                              <strong>IDR {{ number_format($rat->harga, 2) }}</strong><br>
                              <small class="text-muted">{{ $rat->stok }} room available</small>
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

            </div>
          </div><!-- End Starter Menu Content -->

          <div class="tab-pane fade" id="menu-transport">

            <div class="tab-header text-center">
            </div>

            <div class="row gy-4">

            @foreach ($transport as $tr)
              <!-- Menu Item -->
              <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
                <div class="chef-member">
                  <div class="member-img">
                    @php $gmbr = explode(";",$tr->foto) ; @endphp
                    
                    <img src="assets/img/transport/{{ $gmbr[0] }}" class="img-fluid" alt="{{$tr->slug}}">
                    <!-- {{ $gmbr[0] }} -->
                    
                    <div class="social">
                      <a href="" data-toggle="modal" data-target="#trModal{{$tr->id}}" alt="Preview"><i class="bi bi-eye"></i></a>
                    </div>
                  </div>
                  
                  <div class="member-info">
                    <h4>{{ $tr->nama}}</h4>
                    <p>{{ substr($tr->deskripsi, 0, 200)}}</p>
                    
                    @php $fs = explode(",",$tr->fasilitas) ; @endphp
                    @foreach ($fs as $fas)
                      <i class="bi bi-check2-all"></i> {{$fas}}<br>
                    @endforeach
                    IDR {{ number_format($tr->harga, 2) }} for {{ $tr->waktu }} Hours
                    <a href="https://api.whatsapp.com/send?phone=+62818688114&text=Halo" target="_blank" class="btn-book-a-table">
                      <img src="assets/img/wa.png" >Book Now</a>
                  </div>
                </div>
              </div><!-- End Chefs Member -->
            @endforeach

            </div>
          </div><!-- End Breakfast Menu Content -->

          <div class="tab-pane fade" id="menu-tour">

            <div class="tab-header text-center">
              <h6>Our best tour</h6>
            </div>
            

            <div class="row gy-5">

              @foreach ($tour as $tur)
                <!-- Menu Item -->
                <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
                  <div class="chef-member">
                    <div class="member-img">
                      @php $gmbr = explode(";",$tur->foto) ; @endphp
                      <img src="assets/img/tour/{{ $gmbr[0] }}" class="img-fluid" alt="{{ $gmbr[0] }}">
                      <div class="social">
                        <a href="" data-toggle="modal" data-target="#trModal{{$tr->id}}" alt="Preview"><i class="bi bi-eye"></i></a>
                      </div>
                    </div>
                    
                    <div class="member-info">
                      <h4>{{ $tur->tour_name}}</h4>
                    </div>
                    <p class="price">
                      <a href="/tour_packages/{{$tur->slug}}" class="btn-book-a-table">Detail</a>
                    </p>
                    
                  </div>
                </div><!-- End Chefs Member -->
              @endforeach

              
            </div>
          </div><!-- End Lunch Menu Content -->

          <div class="tab-pane fade" id="menu-package">

            <div class="tab-header text-center">
            </div>

            <div class="row gy-5">
            @foreach ($paket as $paket)
              <div class="col-lg-4 menu-item">
                <a href="" class="glightbox"><img src="" class="menu-img img-fluid" alt=""></a>
                <h4>{{$paket->name}}</h4>
                <p class="ingredients">
                  @php $desa = explode("</p>", $paket->deskripsi) ; @endphp
                  {!! cutText($desa[0], 300, 1) !!}
                </p>
                <p class="price">
                IDR {{ number_format($paket->price, 2) }}
                </p>
              </div><!-- Menu Item -->
            @endforeach
              
            </div>
          </div>
        </div>

      </div>
    </section>
    
    <!-- End Menu Section -->

    <!-- ======= Products Slider Section ======= -->
    <section id="products-slider" class="products-slider section-bg">
      <div class="container">
        <div class="section-header">
          <p>Featured <span>Activity</span></p>
        </div>

        <div class="products-swiper swiper">
          <div class="swiper-wrapper">
            @foreach ($product as $item)
              @php $thumbs = array_filter(explode(";", $item->product_foto ?? '')); @endphp
              <div class="swiper-slide">
                <div class="product-card h-100 d-flex flex-column">
                  <div class="product-thumb position-relative">
                    <div id="carouselProduct{{ $item->id }}" class="carousel slide card-carousel" data-bs-ride="false" data-bs-interval="false" data-bs-touch="true">
                      <div class="carousel-inner">
                        @foreach($thumbs as $index => $foto)
                          <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <img src="{{ asset('assets/img/products/' . $foto) }}" class="img-fluid" alt="{{ $item->product_name }}" style="width: 100%; height: 180px; object-fit: cover;">
                          </div>
                        @endforeach
                      </div>
                      @if(count($thumbs) > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselProduct{{ $item->id }}" data-bs-slide="prev">
                          <span class="carousel-control-prev-icon" aria-hidden="true" style="width: 0.8rem; height: 0.8rem;"></span>
                          <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselProduct{{ $item->id }}" data-bs-slide="next">
                          <span class="carousel-control-next-icon" aria-hidden="true" style="width: 0.8rem; height: 0.8rem;"></span>
                          <span class="visually-hidden">Next</span>
                        </button>
                      @endif
                    </div>
                    <div class="position-absolute top-0 start-0 m-3" style="z-index: 2;">
                      <span class="badge bg-warning text-dark text-uppercase fw-bold shadow-sm" style="font-size: 0.65rem;">Top Pick</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-light rounded-circle position-absolute top-0 end-0 m-3 shadow-sm border-0 d-flex align-items-center justify-content-center" style="z-index: 2; width: 30px; height: 30px;" title="Add to Wishlist">
                      <i class="bi bi-heart text-danger" style="font-size: 0.9rem;"></i>
                    </button>
                    <span class="product-badge" style="top: auto; bottom: 12px; z-index: 2;">{{ $item->price ? 'IDR ' . number_format($item->price, 0, ',', '.') : 'NEW' }}</span>
                  </div>
                  @php
                    $review = DB::table('review_ratings')->where('product_code', $item->product_code)->sum('star_rating');
                    $count = DB::table('review_ratings')->where('product_code', $item->product_code)->count('star_rating');
                    $rating = $count ? round($review / $count) : 0;
                  @endphp
                  <div class="product-info flex-grow-1 d-flex flex-column">
                    <a href="/products/{{ $item->slug ?? $item->product_code }}"><h5 class="product-title">{{ $item->product_name }}</h5></a>
                    <div class="d-flex align-items-center mb-2" style="font-size: 0.65rem;">
                      <div class="d-flex align-items-center me-3">
                        <div class="text-warning me-1">
                          @for($i = 1; $i <= 5; $i++)
                            <i class="bi {{ $i <= $rating ? 'bi-star-fill' : 'bi-star' }}" style="font-size: 0.65rem;"></i>
                          @endfor
                        </div>
                        <small class="text-muted">({{$count}} Reviews)</small>
                      </div>
                      <div class="d-flex align-items-center border-start ps-3" style="border-color: #ddd !important;">
                        <i class="bi bi-geo-alt-fill me-1" style="color: #ce1212; font-size: 0.9rem;"></i>
                        <small class="text-muted">{{ $item->area_names }}</small>
                      </div>
                    </div>
                    <p class="product-desc flex-grow-1">{{ Str::limit(strip_tags($item->product_des ?? ''), 80) }}</p>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
          
          <div class="swiper-button-next products-nav"></div>
          <div class="swiper-button-prev products-nav"></div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </section><!-- End Products Slider Section -->

    <!-- ======= Areas Section ======= -->
    <section id="popular-areas" class="popular-areas section-bg pt-0">
      <div class="container">
        <div class="section-header">
          <p>Explore <span>Bali Areas</span></p>
        </div>
        
        <div class="areas-swiper swiper">
          <div class="swiper-wrapper">
            @foreach($areas as $area)
            <div class="swiper-slide">
              <a href="/destinations?area={{ $area->name }}" class="area-card d-block text-center">
                <div class="area-img-container rounded-4 overflow-hidden shadow-sm">
                  <img src="{{ asset('assets/img/areas/' . ($area->image ?? 'default.jpg')) }}" alt="{{ $area->name }}">
                  <div class="area-overlay">
                    <span class="area-count">{{ $area->hotels_count ?? 0 }} Properties</span>
                  </div>
                </div>
                <h6 class="fw-bold text-dark mt-2 mb-0">{{ $area->name }}</h6>
              </a>
            </div>
            @endforeach
          </div>
          <div class="swiper-pagination"></div>
        </div>
        
        <div class="text-center mt-4">
          <a href="/hotels" class="btn-view-all">
            View All Areas <i class="bx bx-right-arrow-alt btn-arrow"></i>
          </a>
        </div>
      </div>
    </section><!-- End Areas Section -->

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
        <div class="container">

          <div class="section-header">
            <h2>{{ __('headmenu.about_us') }}</h2>
            <p>{{ __('headmenu.about_des') }} <span></span></p>
          </div>

          <div class="row gy-5 align-items-center">
            <div class="col-lg-6">
              <div class="about-img" style="background-image: url(assets/img/about.webp);"></div>
            </div>
            <div class="col-lg-6">
              <div class="about-content">
                <p class="about-intro">
                  We are a company dedicated to managing properties and providing exceptional accommodation experiences.
                </p>

                <div class="about-cards">
                  <div class="about-card">
                    <div class="card-icon">
                      <i class="bi bi-backpack2"></i>
                    </div>
                    <h4>For Travelers</h4>
                    <ul class="about-list">
                      <li><i class="bi bi-check2"></i> Your friend, family and guide</li>
                      <li><i class="bi bi-check2"></i> Unforgettable holidays</li>
                      <li><i class="bi bi-check2"></i> Rooms, transport & tours as you wish</li>
                    </ul>
                    <p class="about-contact">Contact us 24/7 on WhatsApp</p>
                    <a href="https://api.whatsapp.com/send?phone=+6282340064488&text=Halo" target="_blank" class="btn-book-a-table">
                      <i class="bi bi-whatsapp"></i> +62 8234 006 4488
                    </a>
                  </div>

                  <div class="about-card">
                    <div class="card-icon">
                      <i class="bi bi-building"></i>
                    </div>
                    <h4>For Property Owners</h4>
                    <ul class="about-list">
                      <li><i class="bi bi-check2"></i> Increase bookings from OTA</li>
                      <li><i class="bi bi-check2"></i> Maximize revenue</li>
                      <li><i class="bi bi-check2"></i> Complete property management</li>
                    </ul>
                    <p class="about-contact">Let us handle your property</p>
                    <a href="https://api.whatsapp.com/send?phone=+6282340064488&text=Halo" target="_blank" class="btn-book-a-table">
                      <i class="bi bi-whatsapp"></i> +62 8234 006 4488
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </section><!-- End About Section -->

    

    <!-- ======= Events Section ======= -->
    <section id="events" class="events">
      <div class="container-fluid">

        <div class="section-header">
          <h2>Events</h2>
          <p>Bali's <span>Event and ceremony</span></p>
        </div>

        <div class="slides-3 swiper">
          <div class="swiper-wrapper">

          @foreach ($artikel as $art)

            <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(assets/img/artikel/{{$art->foto}})">
              <h3>{{ $art->judul }}</h3>
              <!-- <div class="price align-self-start">$99</div> -->
              <span class="desc-event">
              {!! substr($art->isi, 0, 100) !!}
              </span>
            </div><!-- End Event item -->

          @endforeach

            

          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>
    </section><!-- End Events Section -->

   

@stop

@section('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // 1. Initialize Bootstrap Carousels
    document.querySelectorAll('.carousel.slide').forEach(carouselElement => {
        // Force initialization
        const carousel = new bootstrap.Carousel(carouselElement, {
            interval: false,
            ride: false
        });
        console.log('Initialized carousel:', carouselElement.id);
    });

    // 2. Initialize DateRangePicker
    if (typeof $ !== 'undefined' && $.fn.daterangepicker) {
      $('input[name="cekin"]').daterangepicker({
        "autoApply": true,
        "locale": {
            "format": "MMM DD, YYYY",
            "separator": " - ",
            "firstDay": 1
        },
        "minDate": new Date(),
        "startDate": new Date(),
        "endDate": new Date(Date.now() + (3600 * 1000 * 24)),
        "opens": "center",
        "drops": "auto"
      }, function(start, end) {
        console.log("Date selected: " + start.format('YYYY-MM-DD'));
      });
    }

    // 3. Initialize Swipers
    initSwipers();
  });

  function initSwipers() {
    if (typeof Swiper === 'undefined') return;

    new Swiper('.timeline-swiper', {
      loop: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false
      },
      slidesPerView: 1,
      pagination: {
        el: '.swiper-pagination',
        type: 'bullets',
        clickable: true
      },
      breakpoints: {
        320: { slidesPerView: 1, spaceBetween: 20 },
        768: { slidesPerView: 2, spaceBetween: 20 },
        1200: { slidesPerView: 5, spaceBetween: 20 }
      }
    });
    
    new Swiper('.areas-swiper', {
      speed: 600,
      loop: true,
      autoplay: {
        delay: 4000,
        disableOnInteraction: false
      },
      slidesPerView: 2,
      spaceBetween: 20,
      pagination: {
        el: '.areas-swiper .swiper-pagination',
        clickable: true
      },
      breakpoints: {
        576: { slidesPerView: 3, spaceBetween: 20 },
        768: { slidesPerView: 4, spaceBetween: 20 },
        992: { slidesPerView: 5, spaceBetween: 20 },
        1200: { slidesPerView: 6, spaceBetween: 20 }
      }
    });

    new Swiper('.products-swiper', {
      speed: 600,
      loop: false,
      autoplay: false,
      slidesPerView: 1,
      spaceBetween: 20,
      navigation: {
        nextEl: '.products-swiper .swiper-button-next',
        prevEl: '.products-swiper .swiper-button-prev'
      },
      pagination: {
        el: '.products-swiper .swiper-pagination',
        clickable: true
      },
      breakpoints: {
        320: { slidesPerView: 2, spaceBetween: 10 },
        576: { slidesPerView: 3, spaceBetween: 15 },
        768: { slidesPerView: 4, spaceBetween: 20 },
        992: { slidesPerView: 4, spaceBetween: 20 },
        1200: { slidesPerView: 5, spaceBetween: 20 }
      }
    });
  }
</script>
@endsection