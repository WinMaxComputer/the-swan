@extends('layouts.default')
@section('meta')
    <title>Bali Accommodation Rental- The Swand</title>
    <meta content="bali vacation, bali accommodation, bali hotels, hotels bali, trip to bali, luxury vacation homes, tour bali, bali tour package, luxury rental homes, bali holiday packages 
" name="description">
    <meta content="bali vacation, bali accommodation, bali hotels, hotels bali, trip to bali, luxury vacation homes, tour bali, bali tour package, luxury rental homes, bali holiday packages 
" name="keywords">
@endsection

@section('media')
<style>
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
        border: none;
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
</style>
@endsection

@section('content')
    
    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Bali Hotels</h2>
          <ol>
            <li><a href="/">Home</a></li>
            <li>All Hotels</li>
          </ol>
        </div>

      </div>
    </div><!-- End Breadcrumbs -->

    <!-- ======= Menu Section ======= -->
    <section id="menu" class="menu">
      <div class="container">

        <div class="section-header">
          <!-- <h2>Our Menu</h2> -->
          <p>Rooms   <span>Bookings</span></p>
        </div>

        <div class="row justify-content-center">
          <div class="col-12">
            <div class="search-panel">
              <form action="{{ url('/hotels') }}" method="GET">
                <div class="row align-items-center g-2">
                  <div class="col-lg-5 col-md-5">
                    <div class="input-group">
                      <span class="input-group-text"><i class="bi bi-calendar-range"></i></span>
                      <input type="text" class="form-control" id="cekin" name="cekin" placeholder="Select dates" value="{{ request('cekin') }}" required>
                    </div>
                  </div>
                  <div class="col-lg-5 col-md-5">
                    <div class="input-group">
                      <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                      <select class="form-select" name="area">
                        <option value="" {{ request('area') == '' ? 'selected' : '' }}>All Area</option>
                        @foreach($areas as $area)
                          <option value="{{ $area->name }}" {{ request('area') == $area->name ? 'selected' : '' }}>{{ $area->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-2 col-md-2 text-center">
                    <button type="submit" class="btn-search">Search</button>
                  </div>
                </div>
                <p class="form-note mb-0 text-center">Find the best rooms and villas for your stay in Bali.</p>
              </form>
            </div>
          </div>
        </div>

        <div class="tab-content chefs">
         
            <div class="tab-header text-center">
              <!-- <p>Menu</p> -->
              <!-- <h3>Transport</h3> -->
            </div>
            <div class="row gy-4">
              @foreach ($hotels as $ht)
              <!-- Menu Item -->
              <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                <div class="chef-member service-card">
                  <div class="member-img">
                      @php $gmbr = explode(";",$ht->foto) ; @endphp
                      <div class="position-absolute top-0 start-0 m-3" style="z-index: 1;">
                        <span class="badge bg-danger text-uppercase fw-bold shadow-sm" style="font-size: 0.65rem;">Hot Deal</span>
                      </div>
                      <button type="button" class="btn btn-sm btn-light rounded-circle position-absolute top-0 end-0 m-3 shadow-sm border-0 d-flex align-items-center justify-content-center" style="z-index: 1; width: 30px; height: 30px;" title="Add to Wishlist">
                        <i class="bi bi-heart text-danger" style="font-size: 0.9rem;"></i>
                      </button>
                      
                      <img src="assets/img/rooms/{{ $gmbr[0] ?? '' }}" class="img-fluid" alt="{{ $ht->title }}">
                      
                    </div>
                    <div class="member-info">
                      <a href="/bookings/{{$ht->slug}}"><h4>{{ $ht->title}}</h4></a>
                          @php 
                              $review = DB::table('review_ratings')->where('product_code', $ht->code)->sum('star_rating');
                              $count = DB::table('review_ratings')->where('product_code', $ht->code)->count('star_rating');
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
                              <small class="text-muted">{{ $ht->area_name ?? 'Bali' }}</small>
                            </div>
                          </div>
                      <p class="fst-italic mb-3">{!! strip_tags(substr($ht->desc, 0, 120)) !!}...</p>

                      <div class="service-tags mb-3">
                        @php $fasi = explode(";",$ht->facility) ; @endphp
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
                        @if($ht->code == $rat->kode_kamar)
                          @php
                            $roomDiscount = max(0, min(100, (int) ($ht->discount ?? 0)));
                            $discountedRate = round($rat->harga * (100 - $roomDiscount) / 100);
                          @endphp
                          <div class="d-flex justify-content-between align-items-center service-actions">
                            <div>
                              @if($roomDiscount > 0)
                                <small class="text-muted text-decoration-line-through">IDR {{ number_format($rat->harga, 2) }}</small><br>
                                <strong class="text-danger">IDR {{ number_format($discountedRate, 2) }}</strong>
                                <small class="text-danger">({{ $roomDiscount }}% off)</small><br>
                              @else
                                <strong>IDR {{ number_format($rat->harga, 2) }}</strong><br>
                              @endif
                              <small class="text-muted">{{ $rat->stok }} room available</small>
                            </div>
                            <a href="/bookings/{{$ht->slug}}" class="btn-book-a-table">Book Now</a>
                          </div>
                        @endif
                      @endforeach
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

@section('scripts')
<script>
$(function() {
  $('input[name="cekin"]').daterangepicker({
    "autoApply": true,
    "locale": {
        "format": "MMM DD, YYYY",
        "separator": " - ",
        "applyLabel": "Apply",
        "cancelLabel": "Cancel",
        "fromLabel": "From",
        "toLabel": "To",
        "customRangeLabel": "Custom",
        "weekLabel": "W",
        "daysOfWeek": ["Su","Mo","Tu","We","Th","Fr","Sa"],
        "monthNames": ["January","February","March","April","May","June","July","August","September","October","November","December"],
        "firstDay": 1
    },
    "minDate": new Date(),
    "startDate": new Date(),
    "endDate": new Date(Date.now() + ( 3600 * 1000 * 24)),
    "opens": "center",
    "drops": "auto"
  }, function(start, end, label) {
    console.log(moment(start).format('YYYY-MM-DD'))
  });
});
</script>
@endsection
