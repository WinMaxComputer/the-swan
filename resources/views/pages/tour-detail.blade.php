@extends('layouts.default')
<?php
    $url = $_SERVER['REQUEST_URI'];
    $ur = explode('/', $url);
    $table = $ur[1];
    $slug = $ur[2]; 
?>
@if(isset($tourDetail))
@php 
    $tourDetail = DB::table($table)->where('slug', $slug)
            ->join('tour_fotos', 'tour_fotos.code', 'tour_packages.code')
            ->select('tour_packages.*', 'tour_fotos.foto')
            ->get(); 
    $lang = $tourDetail[0]->lang ;
    App::setLocale($lang);
@endphp
@php 
    $destinasi = DB::table('destinations')->where('lang', $lang)
                ->join('destination_fotos', 'destination_fotos.code', 'destinations.code')
                ->select('destinations.*', 'destination_fotos.foto')
                ->get(); 
    $activities = DB::table('activities')->where('lang', $lang)
                ->join('activity_fotos', 'activity_fotos.code', 'activities.code')
                ->select('activities.*', 'activity_fotos.foto')
                ->get(); 
@endphp
@endif

@section('meta')
    <title>The Swand - {{ $tourDetail[0]->tour_name }}</title>
    <meta content="{!! $tourDetail[0]->itinerary !!}" name="description">
    <meta content="{{ $tourDetail[0]->slug }}" name="keywords">
@endsection
@section('media')
<style>
    .hotel-gallery {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .hotel-gallery-main {
        position: relative;
        width: 100%;
        height: 400px;
    }
    .hotel-gallery-main img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
    }
    .hotel-gallery-thumbs {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }
    .hotel-gallery-thumb {
        position: relative;
        height: 100px;
    }
    .hotel-gallery-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
    }
    .hotel-gallery-badge {
        position: absolute; bottom: 15px; right: 15px; background: rgba(0,0,0,0.7); color: #fff; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem;
    }
    .thumb-overlay {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 1.2rem; border-radius: 8px;
    }
</style>
@endsection
@section('content')
    
    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Tour Detail</h2>
          <ol>
            <li><a href="/">Home</a></li>
            <li>{{ $tourDetail[0]->tour_name }}</li>
          </ol>
        </div>

      </div>
    </div><!-- End Breadcrumbs -->

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
        <div class="container">

            <!-- <div class="section-header"> -->
            <!-- <h2>About Us</h2> -->
            <!-- <p>Tour Detail </p> -->
            <!-- </div> -->
            
            <div class="row gy-4">
                <div class="col-lg-6 position-relative about-img">
                    
                    <div class="position-relative mt-4">
                        <h4>{{ $tourDetail[0]->tour_name }}</h4>
                        <p>{!! $tourDetail[0]->itinerary !!}</p>
                        <p>{!! $tourDetail[0]->price !!}</p>
                        <p>{!! $tourDetail[0]->pickup !!}</p>
                        <p>{!! $tourDetail[0]->payment !!}</p>
                    </div>
                </div>
                <div class="col-lg-6 position-relative about-img">
                    <p>
                        <h4>Make Booking</h4>
                        <!-- <img src="/assets/img/about.jpg" class="img-fluid" alt=""> -->
                        <a href="https://api.whatsapp.com/send?phone=+6282340064488&text=Halo" target="_blank" class="btn-book-a-table">
                            <img src="{{ asset('assets/img/wa.png')}}" class="img-fluid">+62 8234 006 4488
                        </a>
                    </p>
                    @php 
                        $gmbr = array_filter(explode(";", $tourDetail[0]->foto)); 
                        $imageCount = count($gmbr);
                    @endphp
                    <div class="hotel-gallery mb-4">
                        <div class="hotel-gallery-main">
                            @if($imageCount > 0)
                                <a href="{{ asset('assets/img/tour/'. $gmbr[0]) }}" class="glightbox" data-gallery="tour-gallery">
                                    <img src="{{ asset('assets/img/tour/'. $gmbr[0]) }}" alt="{{ $tourDetail[0]->tour_name }}">
                                    @if($imageCount == 1)
                                        <span class="hotel-gallery-badge">{{ $imageCount }} photos</span>
                                    @endif
                                </a>
                            @endif
                        </div>
                        <div class="hotel-gallery-thumbs">
                            @foreach(array_slice($gmbr, 1, 4) as $index => $thumb)
                                <div class="hotel-gallery-thumb">
                                    <a href="{{ asset('assets/img/tour/'. $thumb) }}" class="glightbox" data-gallery="tour-gallery">
                                        <img src="{{ asset('assets/img/tour/'. $thumb) }}" alt="Thumbnail {{ $index + 2 }}">
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
                        @if($imageCount > 5)
                            <div style="display:none">
                                @foreach(array_slice($gmbr, 5) as $hiddenThumb)
                                    <a href="{{ asset('assets/img/tour/'. $hiddenThumb) }}" class="glightbox" data-gallery="tour-gallery"></a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <p>{!! $tourDetail[0]->note !!}</p>

                    <p>
                    <h5>Destinations Can be Visit</h5>
                    <!-- <p>Tour Detail </p> -->
                    </p>
                    
                    @php $fasi = explode(";",$tourDetail[0]->destination) ; @endphp
                    <div class="row">
                    @foreach($destinasi as $desti)
                        @if(in_array($desti->id, $fasi))
                            @php $gmbr = explode(";",$desti->foto) ; @endphp
                            <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
                                <div class="chef-member">
                                    <a href="/destinations/{{$desti->slug}}">
                                        <div class="member-img">
                                            <img src="{{ asset('assets/img/destinasi/'.$gmbr[0] )}}" class="img-fluid" alt="{{ $desti->name }}">
                                        </div>
                                        <div class="member-info">
                                            <small>{{$desti->name}}</small>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    </div>

                    <p>
                    <h5>Some Activity</h5>
                    <!-- <p>Tour Detail </p> -->
                    </p>
                    
                    @php $area = $tourDetail[0]->area_tour ; @endphp
                    
                    <div class="row">
                    @foreach($activities as $actv)
                    @php $kawasan = explode(";",$actv->area) ; @endphp
                        @if(in_array($area, $kawasan))
                            @php $gmbr = explode(";",$actv->foto) ; @endphp
                            <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
                                <div class="chef-member">
                                    <a href="/activities/{{$actv->slug}}">
                                        <div class="member-img">
                                            <img src="{{ asset('assets/img/activity/'.$gmbr[0] )}}" class="img-fluid" alt="{{ $actv->name }}">
                                        </div>
                                        <div class="member-info">
                                            <small>{{$actv->name}} </small>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    </div>
             
                    
                </div>
            </div>
            <!-- <div class="col-lg-5 d-flex align-items-end" data-aos="fade-up" data-aos-delay="300">
                <div class="content ps-0 ps-lg-5">

                <div class="position-relative mt-4">
                    <img src="assets/img/about-2.jpg" class="img-fluid" alt="">
                    <a href="https://www.youtube.com/watch?v=LXb3EKWsInQ" class="glightbox play-btn"></a>
                </div>


                <p class="fst-italic">
                    
                    Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                    velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident
                    Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
                    Excepteur sint occaecat cupidatat non proident 
                    Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate 
                    velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident 
                </p>
                <ul>
                    <li><i class="bi bi-check2-all"></i> Ullamco laboris nisi ut aliquip ex ea commodo consequat.</li>
                    <li><i class="bi bi-check2-all"></i> Duis aute irure dolor in reprehenderit in voluptate velit.</li>
                    <li><i class="bi bi-check2-all"></i> Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate trideta storacalaperda mastiro dolore eu fugiat nulla pariatur.</li>
                </ul>
                

                
                
                </div>
            </div>
            </div> -->

        </div>
    </section><!-- End About Section -->

    

@stop