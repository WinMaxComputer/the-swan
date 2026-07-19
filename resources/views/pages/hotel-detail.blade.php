@extends('layouts.default')
@section('meta')
    <title>{{ $hotelDetail[0]->title }} - The Swand</title>
    <meta content="{!! $hotelDetail[0]->desc !!}" name="description">
    <meta content="{{ $hotelDetail[0]->slug }}" name="keywords">
@endsection
@section('media')
        <link rel="stylesheet" type="text/css" href="/css/store.css" />
        <style>
        .room-detail-thumb img {
            max-height: 420px;
            width: 100%;
            object-fit: cover;
            border-radius: 6px;
        }
        .room-detail-area .single-rooms-area .price-from {
            position: static;
            transform: none;
            display: inline-block;
            margin-bottom: 16px;
            left: auto;
            top: auto;
        }
        .room-map-area {
            margin-top: 20px;
        }
        .room-map-area h6 {
            margin-bottom: 15px;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .gmp-map-container {
            width: 100%;
            min-height: 420px;
            height: 420px;
            border-radius: 6px;
            overflow: hidden;
            background: #f3f3f3;
        }
        gmp-map {
            display: block;
            width: 100%;
            height: 100%;
            min-height: 420px;
            border-radius: 6px;
        }
        .room-gallery-thumbs > div {
            width: calc(24% - 8px) !important;
            max-width: 170px;
        }
        .room-gallery-thumbs .popup-gallery img {
            height: 100%;
            object-fit: cover;
        }
        .hotel-gallery-main img,
        .hotel-gallery-thumbs img {
            border-radius: 8px;
        }
        .booking-panel .card-body {
            padding: 0.9rem !important;
        }
        .booking-panel .form-label {
            margin-bottom: 0.25rem;
        }
        .booking-panel .form-control,
        .booking-panel .form-select {
            min-height: 30px;
            padding-top: 0.2rem;
            padding-bottom: 0.2rem;
        }
        .booking-panel .price-summary {
            padding: 0.75rem !important;
        }
        .booking-panel .booking-header,
        .booking-panel .price-summary,
        .booking-panel .booking-actions {
            margin-top: 0.75rem !important;
            margin-bottom: 0.75rem !important;
        }
        /* Keep booking in the normal content flow. */
        .booking-panel {
            position: static;
        }
        @media (max-width: 575.98px) {
            .booking-panel .card-body {
                padding: 0.75rem !important;
            }
            .booking-panel .booking-header small {
                display: block;
                line-height: 1.35;
            }
            .booking-panel .form-control,
            .booking-panel .form-select {
                font-size: 16px;
            }
        }
    </style>
    <script>
        // prettier-ignore
        (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
            key: "AIzaSyAV9SQMHgIgA-lQv0IqWlAm6K4NNKrzYb0"
        });
    </script>
@endsection
@section('content')
    
    @php 
        $hotel = $hotelDetail[0];
        $hotelReviews = $review->where('product_code', $hotel->code);
        $reviewCount = $hotelReviews->count();
        $averageRating = $reviewCount ? round($hotelReviews->avg('star_rating')) : 0;

        // Parse map coordinates (Expected format: "lat, lng")
        $coords = explode(',', $hotel->map ?? '-8.654660753320636, 115.13039176192534');
        $lat = trim($coords[0]);
        $lng = trim($coords[1]);
    @endphp

    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Hotel Detail</h2>
          <ol>
            <li><a href="/">Home</a></li>
            <li>{{ $hotel->title }}</li>
          </ol>
        </div>

      </div>
    </div><!-- End Breadcrumbs -->

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
        <div class="container">

            <div class="section-header">
            <!-- <h2>About Us</h2> -->
            <!-- <p>Learn More </p> -->
            </div>

            <div class="row gy-4">
                <div class="col-lg-7 position-relative about-img">
                    
                    <div class="hotel-detail-header mb-4">
                        <div class="hotel-detail-intro">
                            <h4>{{ $hotel->title }}</h4>
                            <div class="hotel-detail-meta mt-2">
                                <span class="badge bg-danger me-2"><i class="bi bi-geo-alt me-1"></i>{{ $hotel->area_name ?? 'Bali' }}</span>
                                <div class="hotel-rating d-inline-block">
                                    <div class="rated">
                                        @for($i=1; $i<=$averageRating; $i++)                                                      
                                            <label class="star-rating-complete" title="text">{{$i}} stars</label>
                                        @endfor
                                    </div>
                                </div>
                                <span class="hotel-review-count">{{ $reviewCount }} Reviews</span>
                            </div>
                        </div>
                    </div>

                    @php 
                        $gmbr = array_filter(explode(";", $hotel->foto)); 
                        $imageCount = count($gmbr);
                    @endphp

                    <div class="hotel-gallery">
                        <div class="hotel-gallery-main">
                            @if($imageCount > 0)
                                <a href="{{ asset('assets/img/rooms/'. $gmbr[0]) }}" class="glightbox" data-gallery="hotel-gallery">
                                    <img src="{{ asset('assets/img/rooms/'. $gmbr[0]) }}" alt="{{ $hotel->title }}">
                                    @if($imageCount == 1)
                                        <span class="hotel-gallery-badge">{{ $imageCount }} photos</span>
                                    @endif
                                </a>
                            @else
                                <div class="hotel-gallery-empty">
                                    <p>No photos available</p>
                                </div>
                            @endif
                        </div>
                        <div class="hotel-gallery-thumbs">
                            @foreach(array_slice($gmbr, 1, 4) as $index => $thumb)
                                <div class="hotel-gallery-thumb">
                                    <a href="{{ asset('assets/img/rooms/'. $thumb) }}" class="glightbox" data-gallery="hotel-gallery">
                                        <img src="{{ asset('assets/img/rooms/'. $thumb) }}" alt="Thumbnail {{ $index + 2 }}">
                                        @if($loop->last)
                                            <span class="hotel-gallery-badge">{{ $imageCount }} photos</span>
                                        @endif
                                        @if($loop->last && $imageCount > 5)
                                            <div class="thumb-overlay">+{{ $imageCount - 5 }}</div>
                                        @endif
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        {{-- Render hidden links for the rest of the images so GLightbox can find them --}}
                        @if($imageCount > 5)
                            <div style="display:none">
                                @foreach(array_slice($gmbr, 5) as $hiddenThumb)
                                    <a href="{{ asset('assets/img/rooms/'. $hiddenThumb) }}" class="glightbox" data-gallery="hotel-gallery"></a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="hotel-detail-summary mt-5">
                        <div class="d-flex flex-column flex-md-row align-items-start gap-3 justify-content-between">
                            <h5>Facility</h5>
                            <span class="text-muted">Enjoy the most popular amenities for your stay.</span>
                        </div>
                        <div class="hotel-facility-list row g-3 mt-3">
                            @php $roomFasi = array_filter(explode(";", $hotel->facility)); @endphp
                            @foreach ($fasilitas->whereIn('id', $roomFasi) as $fas)
                                <div class="col-6 col-md-4">
                                    <div class="hotel-facility-item">
                                        <span class="facility-icon">{!! $fas->icon !!}</span>
                                        <span>{{ $fas->fas_name }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="hotel-description mt-4">
                            <p>{!! $hotel->desc !!}</p>
                        </div>
                    </div>

                    <div class="room-map-area">
                        <h6>Maps</h6>
                        <div class="gmp-map-container">
                            <gmp-map
                                center="{{ $lat }}, {{ $lng }}"
                                zoom="13"
                                map-id="DEMO_MAP_ID">
                                
                            </gmp-map>
                        </div>
                    </div>

                    <div class="hotel-suggestions mt-5">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h5 class="mb-0">Suggested Accommodations</h5>
                                <small class="text-muted">Other popular places to stay in Bali.</small>
                            </div>
                        </div>
                        <div class="row g-3">
                            {{-- Assuming $hotels variable contains other available properties --}}
                            @isset($hotels)
                                @foreach($hotels->where('code', '!=', $hotel->code)->take(3) as $ht)
                                    @php $htGmbr = array_filter(explode(";", $ht->foto)); @endphp
                                    <div class="col-md-4">
                                        <div class="chef-member h-100 shadow-none border">
                                            <a href="/bookings/{{$ht->slug}}">
                                                <div class="member-img rounded-3 overflow-hidden">
                                                    <img src="{{ asset('assets/img/rooms/'. ($htGmbr[0] ?? '')) }}" class="img-fluid" alt="{{ $ht->title }}">
                                                </div>
                                                <div class="member-info py-2">
                                                    <small class="d-block text-truncate fw-bold text-dark">{{$ht->title}}</small>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @endisset
                        </div>
                    </div>

                    <div class="hotel-review-section mt-5">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h5 class="mb-0">Guest Reviews</h5>
                                <small class="text-muted">Real feedback from recent guests.</small>
                            </div>
                            <span class="text-primary">{{ $reviewCount }} Reviews</span>
                        </div>
                        <div class="hotel-review-list">
                            @foreach($hotelReviews as $rev)
                                <div class="hotel-review-card">
                                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                        <div>
                                            <strong>{{ $rev->guest_name }}</strong>
                                        </div>
                                        <div class="rated review-stars">
                                            @for($i = 1; $i <= $rev->star_rating; $i++)
                                                <label class="star-rating-complete" title="text">{{$i}} stars</label>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="mb-2">{{ $rev->comments }}</p>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($rev->created_at)->format('M d, Y') }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                <div class="col-lg-5">
                    <div class="">
                        <div class="booking-panel card shadow-sm border-0 rounded-4">
                            <div class="card-body p-4">
                                <div class="booking-header mb-3">
                                    <h4 class="mb-1">Guest Details</h4>
                                    <small class="text-muted">Tell us a little about yourself to reserve your stay at {{ $hotel->title }}.</small>
                                </div>
                                
                                <form id="form-configure" autocomplete="off">
                                    <div class="row g-2">
                                        <div class="col-md-6 form-group">
                                            <input type="hidden" name="code" id="code" value="{{ $hotel->code }}">
                                            <input type="hidden" name="nama_kamar" id="nama_kamar" value="{{ $hotel->title }}">
                                            <input type="hidden" name="hari" id="hari" value="1">
                                            <label class="form-label small fw-bold" for="name">Full name</label>
                                            <input type="text" name="name" class="form-control form-control-sm" id="name" placeholder="As shown on your ID" autocomplete="name" required>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="form-label small fw-bold" for="email">Email address</label>
                                            <input type="email" class="form-control form-control-sm" name="email" id="email" placeholder="you@example.com" autocomplete="email" required>
                                        </div>

                                        <div class="col-md-6 form-group">
                                            <label class="form-label small fw-bold" for="mobile">Phone number</label>
                                            <input type="tel" class="form-control form-control-sm" name="mobile" id="mobile" placeholder="Include country code" autocomplete="tel" inputmode="tel" required>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="form-label small fw-bold" for="country_name">Country / region</label>
                                            <input type="hidden" name="nationality" id="nationality" required>
                                            <select id="country_name" class="form-select form-select-sm" onchange="getComboA(this)" required>
                                                <option value="">Select your country or region</option>
                                                @foreach($country as $count)
                                                    <option value="{{ $count->country_code }}">{{ $count->country_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 form-group">
                                            <label class="form-label small fw-bold" for="adult">Number of guests</label>
                                            <input type="number" class="form-control form-control-sm" name="adult" id="adult" value="1" min="1" required>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <input type="hidden" name="qty" id="qty" value="1">
                                            <label class="form-label small fw-bold" for="tipe_bayar">How would you like to pay?</label>
                                            <select name="tipe_bayar" id="tipe_bayar" class="form-select form-select-sm" onchange="getOption()">
                                                <option value="deposit">30% Deposit</option>
                                                <option value="full">Full Payment</option>
                                            </select>
                                        </div>

                                        <div class="col-12 form-group">
                                            <label class="form-label small fw-bold">Your stay dates</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text"><i class="bi bi-calendar-range"></i></span>
                                                <input class="form-control" name="datefilter" id="datefilter" placeholder="Choose check-in and check-out dates" required>
                                            </div>
                                            <input type="hidden" name="cek_in" id="cek_in">
                                            <input type="hidden" name="cek_out" id="cek_out">
                                            <input type="hidden" name="tgl_reservasi" id="tgl_reservasi">
                                            <input type="hidden" name="room_no" id="room_no">
                                            <input type="hidden" name="rate_dolar" id="rate_dolar">
                                        </div>
                                    </div>

                                    <div class="price-summary mt-3 p-3 bg-light rounded-3">
                                        <table class="table table-borderless table-sm mb-0">
                                            <tbody id="listharga"></tbody>
                                            <tfoot class="border-top">
                                                <tr>
                                                    <td colspan="2" class="pt-2 fw-bold">Total Stay Cost</td>
                                                    <td class="pt-2 text-end fw-bold"><div id="totalorder">IDR 0</div></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <div class="payment-highlight mt-3 d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-dark">Due Now:</span>
                                        <div class="text-end">
                                            <h5 class="text-primary mb-0" id="totalbayar">IDR 0</h5>
                                            <small class="text-muted" id="totalbayardolar">USD 0</small>
                                        </div>
                                    </div>

                                    <input type="hidden" name="total" id="total">
                                    <input type="hidden" name="total_bayar" id="total_bayar">
                                    <input type="hidden" name="bayar_dolar" id="bayar_dolar">

                                    <div class="booking-actions mt-3">
                                        <button type="button" id="pay-button" class="btn btn-primary w-100 mb-2 py-2 fw-bold">
                                            <i class="bi bi-shield-check me-2"></i>Book Without Worry
                                        </button>
                                        <p class="small text-muted text-center mb-2">Secure checkout with Midtrans</p>
                                        <button type="button" onclick="payPal()" class="btn btn-outline-primary w-100 py-2 fw-bold">
                                            <i class="bi bi-paypal me-2"></i>Pay with PayPal
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="row mt-4 gy-4">
                            <div class="col-12">
                                <h5 class="mb-3">Interesting in Bali</h5>
                                <div class="row g-2">
                                    @foreach($destinasi->take(6) as $desti)
                                    @php $gmbr = explode(";",$desti->foto) ; @endphp
                                        <div class="col-4">
                                            <div class="chef-member h-100 shadow-none border p-0">
                                                <a href="/destinations/{{$desti->slug}}">
                                                    <div class="member-img rounded-2 overflow-hidden">
                                                        <img src="{{ asset('assets/img/destinasi/'.$gmbr[0] )}}" class="img-fluid" alt="{{ $desti->name }}">
                                                    </div>
                                                    <div class="member-info p-1 text-center">
                                                        <small class="d-block text-truncate fw-bold text-dark" style="font-size: 0.7rem;">{{$desti->name}}</small>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-12">
                                <h5 class="mb-3">Bali Activities</h5>
                                <div class="row g-2">
                                    @foreach($activities->take(6) as $actv)
                                    @php $gmbr = explode(";",$actv->foto) ; @endphp
                                        <div class="col-4">
                                            <div class="chef-member h-100 shadow-none border p-0">
                                                <a href="/activities/{{$actv->slug}}">
                                                    <div class="member-img rounded-2 overflow-hidden">
                                                        <img src="{{ asset('assets/img/activity/'.$gmbr[0] )}}" class="img-fluid" alt="{{ $actv->name }}">
                                                    </div>
                                                    <div class="member-info p-1 text-center">
                                                        <small class="d-block text-truncate fw-bold text-dark" style="font-size: 0.7rem;">{{$actv->name}}</small>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- End Main Row -->

            @if(session()->has('flash_msg_success'))
                <div class="alert alert-success">
                    {{ session()->get('flash_msg_success') }}
                </div>
            @endif
            
            <div class="container mt-5" id="form_review">
                <div class="row">
                    <div class="col mt-4">
                        <form class="py-2 px-4" action="{{route('review.store')}}" style="box-shadow: 0 0 10px 0 #ddd;" method="POST" autocomplete="off">
                            @csrf
                            <p class="font-weight-bold ">Review</p>

                            <div class="row">
                                <div class="col-xl-6 form-group">
                                <input class="form-control" type="text" name="name" id="name_rev" placeholder="Name" >
                                </div>
                                <div class="col-xl-6 form-group">
                                    <input type="hidden" name="booking_id" id="booking_id">
                                    <input type="hidden" name="product_code" value="{{ $hotel->code }}">
                                    <input class="form-control" type="email" name="email" id="email_rev" placeholder="Email">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col">
                                    <div class="rate">
                                        <input type="radio" id="star5" class="rate" name="rating" value="5"/>
                                        <label for="star5" title="text">5 stars</label>
                                        <input type="radio" checked id="star4" class="rate" name="rating" value="4"/>
                                        <label for="star4" title="text">4 stars</label>
                                        <input type="radio" id="star3" class="rate" name="rating" value="3"/>
                                        <label for="star3" title="text">3 stars</label>
                                        <input type="radio" id="star2" class="rate" name="rating" value="2">
                                        <label for="star2" title="text">2 stars</label>
                                        <input type="radio" id="star1" class="rate" name="rating" value="1"/>
                                        <label for="star1" title="text">1 star</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mt-4">
                            <div class="col">
                                <textarea class="form-control" name="comment" rows="6 " placeholder="Comment" maxlength="200"></textarea>
                            </div>
                            </div>
                            <div class="mt-3 text-right">
                            <button class="btn btn-sm py-2 px-3 btn-info">Submit
                            </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
               
        </div>
    </section><!-- End About Section -->

    @section('scripts')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded",  
        
        function () { 
            $("#loading").hide();
            document.getElementById('adult').max = 2;
            document.getElementById('tgl_reservasi').value = moment().format('YYYY-MM-DD h:mm:ss'); 
            
            const tipe = document.getElementById('tipe_bayar').value ;
            var rate = document.getElementById('rate_dolar').value ;
            
            // Exchange Rate API
            $.ajax({
                type: "GET",
                url: "/api/get-exchange",
                error: function (request, error) { console.log(error); },
                success: function (result) {
                    document.getElementById('rate_dolar').value = result.conversion_rates.IDR || 16000;
                    getOption();
                }
            });

            // LocalStorage Guest Data
            var guestData = JSON.parse(localStorage.getItem('guest'));
            if(guestData && guestData.name !== ""){
                document.getElementById('name').value =  guestData.name;
                document.getElementById('email').value =  guestData.email;
                document.getElementById('mobile').value =  guestData.phone;
                document.querySelector('#nationality').value = guestData.nationality;
                document.querySelector('#country_name').value = guestData.nationality;

                var lastReservation = document.getElementById('last_rev');
                var bookingId = document.getElementById('booking_id');
                if (lastReservation && bookingId) {
                    bookingId.value = lastReservation.value;
                }
                document.getElementById('email_rev').value = guestData.email;
                document.getElementById('name_rev').value = guestData.name;

                document.getElementById("form_review").style.display = "block";
            } else {
                document.getElementById("form_review").style.display = "none";
            }
            
        }, false ); 
        
        function formatIdr(value) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'IDR' }).format(value || 0);
        }

        function formatUsd(value) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value || 0);
        }

        function resetBookingTotals(message) {
            $('#listharga').empty();
            document.getElementById('total').value = "";
            document.getElementById('total_bayar').value = "";
            document.getElementById('bayar_dolar').value = "";
            document.getElementById('room_no').value = "";
            document.getElementById("totalorder").innerHTML = "IDR 0";
            document.getElementById("totalbayar").innerHTML = "IDR 0";
            document.getElementById("totalbayardolar").innerHTML = "USD 0";

            if (message) {
                alert(message);
            }
        }

        function getOption() {
            const tipe = document.getElementById('tipe_bayar').value ; 
            const rate_dolar = parseFloat(document.getElementById('rate_dolar').value) || 16000;
            const total_hidden = document.getElementById('total').value ;

            if (!total_hidden || parseFloat(total_hidden) <= 0) {
                resetBookingTotals();
                return;
            }

            if(tipe === "deposit"){
                var totalbayar = ((total_hidden) * 30) / 100 ;
                var totaldolar = Math.ceil(totalbayar / rate_dolar)
                
                document.getElementById('total_bayar').value = totalbayar;
                document.getElementById('bayar_dolar').value = totaldolar ;
                
                document.getElementById("totalbayar").innerHTML = formatIdr(totalbayar);
                document.getElementById("totalbayardolar").innerHTML = formatUsd(totaldolar);
                document.getElementById("totalorder").innerHTML = formatIdr(total_hidden);
            }else if(tipe === "full"){
                var totald = Math.ceil(total_hidden / rate_dolar);
                document.getElementById('total_bayar').value = total_hidden ;
                document.getElementById('bayar_dolar').value = totald;
                
                document.getElementById("totalorder").innerHTML = formatIdr(total_hidden);
                document.getElementById("totalbayar").innerHTML = formatIdr(total_hidden);
                document.getElementById("totalbayardolar").innerHTML = formatUsd(totald);
            }
        };

        function FungsiHitung(start, end, difference, code, showAlerts = true){
            $('#loading').show();
            var hrg = 0;
            var completedRequests = 0;
            $('#listharga').empty();

            if (difference < 1) {
                resetBookingTotals(showAlerts ? "Please select at least one night." : "");
                $('#loading').hide();
                return;
            }

            for(let i=0 ; i<difference ;i++){
                var dt = moment(start).add(i, 'days').format('YYYY-MM-DD');
                var starte = moment(start).format('YYYY-MM-DD');
                var ende = moment(end).format('YYYY-MM-DD');
                
                $.ajax({
                    type: "POST",
                    url: "/api/get-rate",
                    data: { "code": code, "date": dt, "start" : starte, "end": ende },
                    error: function (request, error) {
                        resetBookingTotals(showAlerts ? "Room not available on this date, please change your selection." : "");
                        $('#loading').hide();
                    },
                    success: function (result) {
                        if (!result[0] || !result[0][0] || !result[1]) {
                            resetBookingTotals(showAlerts ? "Rate is not available for " + moment(dt).format('MMM DD') + "." : "");
                            $('#loading').hide();
                            return;
                        }

                        var qty = document.getElementById('qty').value ;
                        if( qty > (result[1]).length ){
                            resetBookingTotals(showAlerts ? "Insufficient stock for " + moment(dt).format('MMM DD') + "." : "");
                            $('#loading').hide();
                        }else{
                            var originalRate = parseInt(result[0][0].harga_asli || result[0][0].harga);
                            var discountedRate = parseInt(result[0][0].harga);
                            hrg += discountedRate;

                            var room_n = "";
                            for(let r=0 ; r<qty ;r++){
                                room_n += result[1][r].room_no ;
                            }
                            document.getElementById('room_no').value = room_n;
                            
                            var rateDisplay = originalRate !== discountedRate
                                ? '<del class="text-muted me-1">' + formatIdr(originalRate) + '</del><br><strong class="text-danger">' + formatIdr(discountedRate) + '</strong>'
                                : formatIdr(discountedRate);
                            var trHTML = '<tr>' +
                                            '<td>' + (i+1) + '</td>' +
                                            '<td>' + moment(result[0][0].tgl).format('MMM DD') + '</td>' +
                                            '<td class="text-end">' + rateDisplay + '</td>' +
                                         '</tr>';
                            
                            $('#listharga').append(trHTML);
                            document.getElementById('total').value = hrg;
                            getOption();
                        }
                    },
                    complete: function () {
                        completedRequests++;
                        if (completedRequests >= difference) {
                            $('#loading').hide();
                        }
                    }
                });
            }
        }

        function updateStayDates(start, end, showAlerts = true) {
            var code = document.getElementById('code').value;
            var awal = moment(start);
            var akhir = moment(end);
            var difference = akhir.diff(awal, 'days');

            document.getElementById('cek_in').value = moment(start).format('YYYY-MM-DD') ;
            document.getElementById('cek_out').value = moment(end).format('YYYY-MM-DD') ;
            document.getElementById('hari').value = difference ;

            FungsiHitung(start, end, difference, code, showAlerts);
        }

        $(function() {
            if (!$.fn.daterangepicker) {
                resetBookingTotals("Date picker failed to load. Please refresh the page.");
                return;
            }

            var defaultStart = moment();
            var defaultEnd = moment().add(1, 'days');

            $('#datefilter').daterangepicker({
                "autoApply": true,
                "locale": {
                    "format": "MMM DD, YYYY",
                    "separator": " - ",
                },
                "minDate": new Date(),
                "startDate": defaultStart,
                "endDate": defaultEnd,
                "opens": "center",
                "drops": "auto"
            }, function(start, end, label) {
                updateStayDates(start, end);
            });

            updateStayDates(defaultStart, defaultEnd, false);
        });

        $('#pay-button').click(function (event) {
            event.preventDefault();

            if (!document.getElementById('form-configure').checkValidity()) {
                document.getElementById('form-configure').reportValidity();
                return;
            }

            if (!$('#cek_in').val() || !$('#cek_out').val() || !$('#total').val() || !$('#total_bayar').val()) {
                alert("Please select available stay dates before payment.");
                return;
            }
            
            const payload = {
                _method: 'POST',
                _token: '{{ csrf_token() }}',
                kode_product: $('#code').val(),
                name: $('#name').val(),
                email: $('#email').val(),
                mobile: $('#mobile').val(),
                amount: $('#total_bayar').val(),
                cek_in: $('#cek_in').val(),
                cek_out: $('#cek_out').val(),
                type_bayar: $('#tipe_bayar').val(),
                nationality: $('#nationality').val(),
                country_name: $('#country_name option:selected').text(),
                adult: $('#adult').val(),
                tgl_reservasi: $('#tgl_reservasi').val(),
                total: $('#total').val(),
                external_id: $('#code').val() + Date.now(),
                nama_kamar: $('#nama_kamar').val(),
            };

            $.post("{{ route('donation.pay') }}", payload, function (data) {
                if(payload.cek_in !== '' && payload.name !== ''){
                    let arrGuest = {
                        name: payload.name,
                        email: payload.email,
                        nationality: payload.nationality,
                        country_name: payload.country_name,
                        phone: payload.mobile
                    }
                    localStorage.setItem('guest', JSON.stringify(arrGuest));
                };

                snap.pay(data.snap_token.snap_token, {
                    onSuccess: function (result) { location.reload(); },
                    onPending: function (result) { location.reload(); },
                    onError: function (result) { location.reload(); }
                });
            }).fail(function () {
                alert("Payment could not be started. Please check your dates and try again.");
            });
        });

        function payPal(){
            var formElement = document.getElementById("form-configure");
            var formData = new URLSearchParams(new FormData(formElement)).toString();

            var name = $('#name').val();
            var email = $('#email').val();
            var nationality = $("#country_name option:selected").val();
            var cekin = $('#cek_in').val();

            if(cekin !== '' && name !== '' && email !== '' && nationality !== '' && $('#total').val() !== ''){
                let arrGuest = {
                    name: name,
                    email: email,
                    nationality: nationality,
                    country_name: $("#country_name option:selected").text(),
                    phone: $('#mobile').val()
                }
                localStorage.setItem('guest', JSON.stringify(arrGuest));
                window.open("{{ url('/paypal/payment?')}}" + formData);
            }else{
                alert("Please complete the form and select available stay dates before proceeding to payment.");
            }
        }
    </script>
    <script>
        const mapElement = document.querySelector('gmp-map');
        let innerMap;

        async function init() {
            // Request needed libraries.
            const [{ Circle }, { AdvancedMarkerElement }, { event }] =
                await Promise.all([
                    google.maps.importLibrary('maps'),
                    google.maps.importLibrary('marker'),
                    google.maps.importLibrary('core'),
                ]);

            // Set the initial map center point.
            const initialCenter = { lat: {{ $lat }}, lng: {{ $lng }} }; 

            // Get the inner map.
            innerMap = mapElement.innerMap;

            // Get the buttons.
            const buttons = document.querySelectorAll('input[name="radius"]');

            // Create the circle.
            const walkingCircle = new Circle({
                strokeColor: '#ffdd00',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#ffdd00',
                fillOpacity: 0.35,
                map: innerMap,
                center: initialCenter,
                radius: 900,
                draggable: false,
                editable: false,
            });

            // Define a "Crosshair" vector icon
            const parser = new DOMParser();
            const svgString = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="-6 -6 12 12"><path d="M -6,0 L 6,0 M 0,-6 L 0,6" stroke="black" stroke-width="1"/></svg>`;

            const pinSvg = parser.parseFromString(
                svgString,
                'image/svg+xml'
            ).documentElement;

            // const centerMarker = new AdvancedMarkerElement({
            //     position: initialCenter,
            //     title: 'Hotel location',
            //     anchor: { x: 12, y: 12 },
            //     map: innerMap,
            // });
            // centerMarker.append(pinSvg);

            // Wait for the map to finish drawing its tiles.
            event.addListenerOnce(innerMap, 'tilesloaded', () => {
                // Get the controls div
                const controls = document.getElementById('control-panel');

                // Display controls once map is loaded.
                if (controls) {
                    controls.style.display = 'block';
                }
            });

            // Add event listener to update the radius based on user selection.
            buttons.forEach((button) => {
                button.addEventListener('change', (changeEvent) => {
                    const target = changeEvent.target;
                    walkingCircle.setRadius(Number(target.value));
                });
            });

            // Handle user click, reset the map center and position the circle.
            innerMap.addListener('click', (mapsMouseEvent) => {
                const newCenter = mapsMouseEvent.latLng;
                if (!newCenter) return;
                walkingCircle.setCenter(newCenter);
                if (typeof centerMarker !== 'undefined' && centerMarker) {
                    centerMarker.position = newCenter;
                }
                innerMap.panTo(newCenter);
            });

            // Handle user dragging the circle, update the center marker position.
            walkingCircle.addListener('center_changed', () => {
                if (typeof centerMarker !== 'undefined' && centerMarker) {
                    centerMarker.position = walkingCircle.getCenter();
                }
            });
        }

        void init();
    </script>
    @endsection
   
@stop
