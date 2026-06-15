@extends('layouts.default')

@section('meta')
    <title>{{ $activityDetail[0]->name }} - The Swand</title>
    <meta content="{!! $activityDetail[0]->deskripsi !!}" name="description">
    <meta content="{{ $activityDetail[0]->slug }}" name="keywords">
@endsection

@section('media')
<style>
    .activity-gallery-main img,
    .activity-gallery-thumbs img {
        border-radius: 8px;
    }
    .activity-gallery {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .activity-gallery-main {
        position: relative;
        width: 100%;
        height: 400px;
    }
    .activity-gallery-main img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .activity-gallery-thumbs {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }
    .activity-gallery-thumb {
        position: relative;
        height: 100px;
    }
    .activity-gallery-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .activity-gallery-badge {
        position: absolute; bottom: 15px; right: 15px; background: rgba(0,0,0,0.7); color: #fff; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem;
    }
    .thumb-overlay {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 1.2rem; border-radius: 8px;
    }
    .product-card {
        transition: 0.3s;
        background: #fff;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
    }
    .sidebar-card {
        position: sticky;
        top: 100px;
    }
    .sidebar-card .product-card .card-body {
        padding: 1rem !important; /* Reduced padding */
    }
</style>
@endsection

@section('content')
@php 
    $activity = $activityDetail[0];
    $des = $activity->deskripsi ; 
    $desk = array_filter(explode("</p>", $des)) ;
    $gmbra = array_filter(explode(";", $activity->foto));
    $imageCount = count($gmbra);
@endphp
    
    <div class="breadcrumbs">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center">
          <h2>Activity Detail</h2>
          <ol>
            <li><a href="/">Home</a></li>
            <li><a href="/activities">Activities</a></li>
            <li>{{ $activity->name }}</li>
          </ol>
        </div>
      </div>
    </div>

    <section id="activity-detail" class="about">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-7">
                    <div class="activity-header mb-4">
                        <span class="badge bg-danger mb-2 text-uppercase">{{ $activity->type }} Activity</span>
                        <h2 class="fw-bold">{{ $activity->name }}</h2>
                        <p class="text-muted mb-0"><i class="bi bi-geo-alt-fill me-1 text-danger"></i> {{ $activity->area_names }}</p>
                    </div>

                    <div class="activity-gallery mb-4">
                        <div class="activity-gallery-main">
                            @if($imageCount > 0)
                                <a href="{{ asset('assets/img/activity/'. $gmbra[0]) }}" class="glightbox" data-gallery="activity-gallery">
                                    <img src="{{ asset('assets/img/activity/'. $gmbra[0]) }}" alt="{{ $activity->name }}">
                                    @if($imageCount == 1)
                                        <span class="activity-gallery-badge">{{ $imageCount }} photos</span>
                                    @endif
                                </a>
                            @endif
                        </div>
                        <div class="activity-gallery-thumbs">
                            @foreach(array_slice($gmbra, 1, 4) as $index => $thumb)
                                <div class="activity-gallery-thumb">
                                    <a href="{{ asset('assets/img/activity/'. $thumb) }}" class="glightbox" data-gallery="activity-gallery">
                                        <img src="{{ asset('assets/img/activity/'. $thumb) }}" alt="Thumbnail {{ $index + 2 }}">
                                        @if($loop->last)
                                            <span class="activity-gallery-badge">{{ $imageCount }} photos</span>
                                        @endif
                                        @if($loop->last && $imageCount > 5)
                                            <div class="thumb-overlay">+{{ $imageCount - 5 }}</div>
                                        @endif
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        @if($imageCount > 5)
                            <div style="display:none">
                                @foreach(array_slice($gmbra, 5) as $hiddenThumb)
                                    <a href="{{ asset('assets/img/activity/'. $hiddenThumb) }}" class="glightbox" data-gallery="activity-gallery"></a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="activity-description mb-5">
                        <h4 class="fw-bold mb-3 border-bottom pb-2">Overview</h4>
                        @foreach($desk as $paragraph)
                            <div class="mb-3">{!! $paragraph !!}</p></div>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="sidebar-card">
                        <div class="card border-0 shadow-sm rounded-4 bg-light p-4 mb-4 text-center">
                            <h5 class="fw-bold mb-3">Need Assistance?</h5>
                            <p class="text-muted small mb-4">Our travel experts are ready to help you plan your perfect Bali activity.</p>
                            <a href="https://api.whatsapp.com/send?phone=+6282340064488&text=Halo, I want to book {{ $activity->name }}" target="_blank" class="btn btn-success w-100 py-2 fw-bold">
                                <i class="bi bi-whatsapp me-2"></i> Chat with us
                            </a>
                        </div>

                        <div class="activity-packages">
                            <h5 class="fw-bold mb-3 border-bottom pb-2">Available Options</h5>
                            <div class="row g-2 g-md-3">
                                @php $code = $activity->code ; @endphp
                                @foreach($products as $prod)
                                    @php $pr = explode(";", $prod->parent_type); @endphp
                                    @if(in_array($code, $pr))
                                        <div class="col-6 col-lg-12">
                                            <div class="product-card card border-0 shadow-sm rounded-4 overflow-hidden">
                                                <div class="position-relative">
                                                    <div class="position-absolute top-0 start-0 m-2" style="z-index: 1;">
                                                        <span class="badge bg-warning text-dark text-uppercase fw-bold shadow-sm" style="font-size: 0.6rem;">Top Pick</span>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-light rounded-circle position-absolute top-0 end-0 m-2 shadow-sm border-0 d-flex align-items-center justify-content-center" style="z-index: 1; width: 28px; height: 28px;" title="Add to Wishlist">
                                                        <i class="bi bi-heart text-danger" style="font-size: 0.8rem;"></i>
                                                    </button>
                                                    @php $p_fotos = explode(";", $prod->product_foto); @endphp
                                                    <img src="{{ asset('assets/img/products/'. ($p_fotos[0] ?? 'default.jpg')) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                                </div>
                                                <div class="card-body p-3">
                                                    <h6 class="fw-bold mb-2" style="font-size: 1rem;">{{ $prod->product_name }}</h6>
                                                    <p class="small text-muted mb-3" style="font-size: 0.75rem;">{{ Str::limit(strip_tags($prod->product_des), 80) }}</p>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="text-primary fw-bold mb-0" style="font-size: 0.9rem;">IDR {{ number_format($prod->price) }}</span>
                                                        <button class="btn btn-sm btn-danger rounded-pill px-3" id="{{ $prod->product_code }}" onclick="bookNow(this)">Book Now</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
   
    <div class="modal fade" id="trModal-booking" tabindex="-1" aria-labelledby="trModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="trModalLabel">Complete Your Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()"></button>
            </div>
            <div class="modal-body p-4">
                <h4 class="text-primary fw-bold mb-4"><span id="judul_product"></span></h4>
                <form autocomplete="off">
                    <div class="row g-3">
                        <div class="col-md-6 form-group">
                            <input type="hidden" name="code" id="code">
                            <label class="form-label small fw-bold">Name</label>
                            <input type="text" name="name_act" class="form-control" id="name_act" placeholder="Full Name" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email Address" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label small fw-bold">Mobile Number</label>
                            <input type="number" class="form-control" name="mobile" id="mobile" placeholder="Phone" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label small fw-bold">Nationality</label>
                            <input type="hidden" name="nationality" id="nationality">
                            <select id="country_name" class="form-select" onchange="getComboA(this)" required>
                                <option value="">Select Country</option>
                                @foreach($country as $count)
                                <option value="{{ $count->country_code }}">{{ $count->country_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="form-label small fw-bold">Guests</label>
                            <input type="number" name="adult" class="form-control" id="adult" value="1" min="1" required>
                        </div>
                        <div class="col-md-9 form-group">
                            <label class="form-label small fw-bold">Travel Date</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                <input class="form-control" name="datefilter" id="datefilter" required>
                            </div>
                            <input type="hidden" name="tgl_reservasi" id="tgl_reservasi">
                            <input type="hidden" name="rate_dolar" id="rate_dolar">
                        </div>
                        <div class="col-12 form-group">
                            <label class="form-label small fw-bold">Payment Type</label>
                            <select name="tipe_bayar" id="tipe_bayar" class="form-select" onchange="getOption()">
                                <option value="deposit">Deposit</option>
                                <option value="full">Full Payment</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="button" class="btn btn-primary w-100 py-3 fw-bold rounded-3">Proceed to Secure Payment</button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
    

    @section('scripts')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded",  
        
        function () { 
            console.log('page loaded');
            // Code to be executed when the DOM is ready
            // document.getElementById('adult').max = 2;
            document.getElementById('tgl_reservasi').value = moment().format('YYYY-MM-DD h:mm:ss'); // new Date(); 
            const tipe = document.getElementById('tipe_bayar').value ;

            var guestData = JSON.parse(localStorage.getItem('guest'));
            if(guestData && guestData.name){
                document.getElementById('name_act').value =  guestData.name;
                document.getElementById('email').value =  guestData.email;
                document.getElementById('mobile').value =  guestData.phone;
                document.querySelector('#nationality').value = guestData.nationality;
                document.querySelector('#country_name').value = guestData.nationality;
            }
            
            // console.log(guestData.name);
        }, false);
        let id;
        function bookNow(selectObject){
            // id = $(this).data("id");
            // console.log(selectObject.id);
            $.ajax({
                type: "POST",
                url: "/get-product",
                data: { 
                    "code": selectObject.id,
                    "_token": "{{ csrf_token() }}"
                },
                error: function (request, error) {
                    // console.log(arguments);
                },
                success: function (result) {
                    // console.log(result.data)
                    $("#judul_product").text(result.data.product_name);
                    $('#trModal-booking').appendTo("body").modal('show');
                },
            });
            
        }
        function closeModal(){
            $('#trModal-booking').modal('hide');
        }
    
    </script>
    @endsection
@stop