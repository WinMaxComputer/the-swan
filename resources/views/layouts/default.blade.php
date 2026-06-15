<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
@include('includes.head')
@yield('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@yield('media')
<link rel="alternate" hreflang="id" href="{{ url('id') }}" />
<link rel="alternate" hreflang="en" href="{{ url('en') }}" />
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-YGQ91MNY00"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-YGQ91MNY00');
</script>
</head>

<body>

  <!-- ======= Header ======= -->
  @include('includes.headmenu')

  <main id="main">
    @include('pages.notification')
    <!-- ======= About Section ======= -->
    @yield('content')
    <div class="popup-container" id="popupContainer">
        <a href="https://api.whatsapp.com/send?phone=62818688114&text=&source=&data=" class="whatsApp" target="_blank"><i class="fa fa-whatsapp my-whatsApp"></i></a>
        <div class="popup-content" id="popupContent">
            <img src="{{asset('assets/img/wa.webp')}}" /><br />
            Scan Me
        </div>
    </div>
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
  @include('includes.footer')

  </footer><!-- End Footer -->
  <!-- End Footer -->

  @include('includes.footerjs')
  @yield('scripts')

</body>

</html>