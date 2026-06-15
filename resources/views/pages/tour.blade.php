@extends('layouts.default')
@section('meta')
    <title>Bali Packages Tour- The Swand</title>
    <meta content="package tour bali withh private service, best package tour in bali" name="description">
    <meta content="tour bali, package tour, bali driver, private driver" name="keywords">
@endsection
@section('content')
    
    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Bali Tour Packages</h2>
          <ol>
            <li><a href="/">Home</a></li>
            <li>Tour Packages</li>
          </ol>
        </div>

      </div>
    </div><!-- End Breadcrumbs -->

    <!-- ======= Menu Section ======= -->
    <section id="menu" class="menu">
      <div class="container">

        <div class="section-header">
          <!-- <h2>Our Menu</h2> -->
          <p>Choose   <span>a Packages</span></p>
        </div>

          

        <div class="tab-content chefs">

            <div class="tab-header text-center">
              <!-- <p>Menu</p> -->
              <!-- <h3>Transport</h3> -->
            </div>
            <div class="row gy-3">
            @foreach ($tour as $tur)
              <div class="col-lg-3 col-md-4 d-flex align-items-stretch">
                <div class="chef-member service-card">
                  <div class="member-img">
                      @php $gmbr = explode(";",$tur->foto) ; @endphp
                      
                      <img src="{{asset('assets/img/tour/'.$gmbr[0] ) }}" class="img-fluid" alt="{{ $gmbr[0] }}">
                      
                      <div class="social">
                        <a href="" data-toggle="modal" data-target="#trModal{{$tur->id}}" alt="Preview"><i class="bi bi-eye"></i></a>
                      </div>
                    </div>
                    
                    <div class="member-info">
                      <h4>{{ $tur->tour_name}}</h4>
                      <p class="fst-italic">{!! strip_tags(substr($tur->itinerary, 0, 120)) !!}...</p>
                      <div class="d-flex justify-content-between align-items-center service-actions">
                        <a href="/tour_packages/{{$tur->slug}}" class="btn-book-a-table">Detail</a>
                      </div>
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


@stop