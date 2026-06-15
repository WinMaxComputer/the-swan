<?php
    $url = $_SERVER['REQUEST_URI'];
    $ur = explode('/', $url);
    $table = $ur[1];
    $slug = $ur[2]; 
?>
@if(isset($destinationDetail))
@php 
    $destinationDetail = DB::table($table)->where('slug', $slug)
                    ->join('destination_fotos', 'destination_fotos.code', 'destinations.code')
                    ->select('destinations.*', 'destination_fotos.foto')
                    ->get(); 
    $lang = $destinationDetail[0]->lang ;
    App::setLocale($lang);
@endphp
@endif
@php 
    $des = $destinationDetail[0]->deskripsi ; 
    $desk = explode("</p>", $des) ;
@endphp
@extends('layouts.default')
@section('meta')
    <title>{{ $destinationDetail[0]->name }} -The Swand</title>
    <meta content="{!! $desk[0] !!}" name="description">
    <meta content="{{ $destinationDetail[0]->slug }}" name="keywords">
@endsection
@section('content')

    
    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Destinations Detail</h2>
          <ol>
            <li><a href="/">Home</a></li>
            <li>{{ $destinationDetail[0]->name }}</li>
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
            <div class="row">
                <div class="col-lg-12 position-relative mt-0">
                    <h4>{{ $destinationDetail[0]->name }}</h4>
                </div>
                <div class="col-lg-6 position-relative mt-0">
                    <p>{!! $desk[0] !!}</p>
                </div>
                <div class="col-lg-6 position-relative img-blog mt-0">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">

                        @php $gmbra = explode(";",$destinationDetail[0]->foto) ; @endphp
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
                        <!-- {{ $key }} -->
                        <div class="carousel-item {{$key == 0 ? 'active' : ''}}">
                            <img src="{{ asset('assets/img/destinasi/'. $slider) }}" class="d-block w-100" alt="$slider">
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
                    
                </div>
                @php 
                $leng = count($desk) ;
                @endphp
                @for($i = 1;$i < $leng ;$i++)
                <div class="col-lg-12 position-relative mt-0">
                    <p>{!! $desk[$i] !!}</p>
                </div>
                @endfor
            </div>
            

        </div>
    </section><!-- End About Section -->

    

@stop