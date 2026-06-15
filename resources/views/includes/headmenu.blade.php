<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">

      <style>
        body.modal-open > *:not(.modal) {
          filter: blur(5px);
          transition: filter 0.3s ease-in-out;
        }
      </style>


      <a href="/" class="logo d-flex align-items-center me-auto me-lg-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="{{asset('assets/img/swan_logo.jpg')}}" alt="">
        <!-- <h1>The Swand<span>.</span></h1> -->
      </a>

      <nav id="navbar" class="navbar">
        <?php  
            if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
                $url = "https://";   
            }else{  
                $url = "http://";   
            }
            // Append the host(domain name, ip) to the URL.   
            $url.= $_SERVER['HTTP_HOST'];   
            // Append the requested resource location to the URL   
            $urla = $_SERVER['REQUEST_URI'];    
            $data = explode("/", $urla) ;
            if(count($data) > 2 ){
              $tabel = $data[1];
              $slug = $data[2];
            }else{
              $tabel = '';
              $slug = '';
            };
        ?> 
        <ul>
          <li><a href="/">Home</a></li>
          <li class="dropdown"><a href="#"><span>{{ __('headmenu.service') }}</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
            <ul>
              <li><a href="/hotels">{{ __('headmenu.booking') }}</a></li>
              <li><a href="/transport">{{ __('headmenu.hire_car') }}</a></li>
              <li><a href="/tour_packages">{{ __('headmenu.tour_package') }}</a></li>
              <li><a href="/destinations">{{ __('headmenu.destinations') }}</a></li>
              <li><a href="/activities">{{ __('headmenu.activity') }}</a></li>
              <!-- <li><a href="#">Drop Down 4</a></li> -->
            </ul>
          </li>
          <!-- <li><a href="/destinations">{{ __('headmenu.destinations') }}</a></li> -->
          <!-- <li><a href="/activities">{{ __('headmenu.activity') }}</a></li> -->
          <!-- <li><a href="/events">{{ __('headmenu.event') }}</a></li> -->
          <li><a href="/partnership">{{ __('headmenu.partnership') }}</a></li>
          <!-- <li><a href="/gallery">{{ __('headmenu.galleri') }}</a></li> -->
          
          <li><a href="/contact_us">{{ __('headmenu.contact') }}</a></li>
          <li class="dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{ strtoupper(Lang::locale()) }}
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item" href="{!! route('lang', ['lang'=>'id', 'tabel'=>$tabel, 'slug' => $slug]) !!}">ID</a></li>
                  <li><a class="dropdown-item" href="{!! route('lang', ['lang'=>'en', 'tabel'=>$tabel, 'slug' => $slug]) !!}">EN</a></li>
              </ul>
          </li>
        </ul>
      </nav><!-- .navbar -->

      <a href="#" class="btn-book-a-table" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="bi bi-search"></i></a>

      

      <!-- <a class="btn-book-a-table" href="/service">Bookings</a> -->
      <!-- <a class="btn-book-a-table" href="/tour">Transport</a> -->
      <div id="masuk">
        <a href="" class="btn-book-a-table" data-bs-toggle="modal" data-bs-target="#authModal">{{ __('headmenu.signin') }}</a>
      </div>
      <div id="akun">
        <a href="" class="btn-book-a-table" data-bs-toggle="modal" data-bs-target="#trModal-akun">{{ __('headmenu.myakun') }}</a>
      </div>
      

      <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
      <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>

    </div>
    
  </header><!-- End Header -->
  
<!-- Auth Modal (Login/Register) -->
<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="authModalLabel">Welcome to The Swand</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs mb-3" id="authTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-tab-pane" type="button" role="tab" aria-controls="login-tab-pane" aria-selected="true">Login</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register-tab-pane" type="button" role="tab" aria-controls="register-tab-pane" aria-selected="false">Register</button>
          </li>
        </ul>
        <div class="tab-content" id="authTabContent">
          <div class="tab-pane fade show active" id="login-tab-pane" role="tabpanel" aria-labelledby="login-tab" tabindex="0">
            <form id="loginForm" autocomplete="off">
              <div class="mb-3">
                <label for="loginEmail" class="form-label">Email address</label>
                <input type="email" class="form-control" id="loginEmail" name="email" placeholder="name@example.com" required>
              </div>
              <div class="mb-3">
                <label for="loginPassword" class="form-label">Password</label>
                <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Password" required>
              </div>
              <div class="d-grid gap-2 mb-3">
                <button type="button" onclick="login()" class="btn btn-primary btn-book-a-table">Log In</button>
                <button type="button" class="btn btn-outline-danger">
                  <i class="bi bi-google me-2"></i> Sign in with Google
                </button>
              </div>
              <p class="text-center text-muted">Don't have an account? <a href="#" data-bs-toggle="tab" data-bs-target="#register-tab-pane">Register here</a></p>
            </form>
          </div>
          <div class="tab-pane fade" id="register-tab-pane" role="tabpanel" aria-labelledby="register-tab" tabindex="0">
            <form id="registerForm" autocomplete="off">
              <div class="mb-3">
                <label for="registerName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="registerName" name="name" placeholder="Your Full Name" required>
              </div>
              <div class="mb-3">
                <label for="registerEmail" class="form-label">Email address</label>
                <input type="email" class="form-control" id="registerEmail" name="email" placeholder="name@example.com" required>
              </div>
              <div class="mb-3">
                <label for="registerPassword" class="form-label">Password</label>
                <input type="password" class="form-control" id="registerPassword" name="password" placeholder="Password" required>
              </div>
              <div class="mb-3">
                <label for="registerConfirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="registerConfirmPassword" name="password_confirmation" placeholder="Confirm Password" required>
              </div>
              <div class="d-grid gap-2 mb-3">
                <button type="button" onclick="register()" class="btn btn-success btn-book-a-table">Sign Up</button>
                <button type="button" class="btn btn-outline-danger">
                  <i class="bi bi-google me-2"></i> Sign up with Google
                </button>
              </div>
              <p class="text-center text-muted">Already have an account? <a href="#" data-bs-toggle="tab" data-bs-target="#login-tab-pane">Login here</a></p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title" id="searchModalLabel">Search The Swand</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="input-group input-group-lg">
            <input type="text" class="form-control" placeholder="Search for hotels, tours, activities..." aria-label="Search input">
            <button class="btn btn-primary" type="button"><i class="bi bi-search"></i></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- My Account Modal -->
<div class="modal fade" id="trModal-akun" tabindex="-1" aria-labelledby="trModalAkunLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="trModalAkunLabel">{{ __('headmenu.myakun') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label class="form-label text-muted">Name</label>
            <p class="form-control-plaintext fw-bold" id="name_head"></p>
          </div>
          <div class="col-md-6">
            <label class="form-label text-muted">Email</label>
            <p class="form-control-plaintext fw-bold" id="email_head"></p>
          </div>
          <div class="col-md-6">
            <label class="form-label text-muted">Mobile</label>
            <input type="text" class="form-control" id="mobile_head" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label text-muted">Nationality</label>
            <input type="hidden" id="nationality_head">
            <input type="text" class="form-control" id="country_name_head" readonly>
          </div>
        </div>
        <input type="hidden" name="last_rev" class="form-control" id="last_rev">
        <h5 class="mb-3">Your Reservations</h5>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>No.</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="table1">
              <!-- Reservations will be loaded here -->
            </tbody>
          </table>
        </div>
        <nav aria-label="Reservation navigation" class="mt-3">
          <ul class="pagination pagination-sm justify-content-center" id="reservation-pagination">
            <!-- Pagination controls will be injected here via JavaScript -->
          </ul>
        </nav>
        <div class="d-flex justify-content-end mt-3">
          <button type="button" class="btn btn-danger" onclick="logout()">Log Out</button>
        </div>
      </div>
    </div>
  </div>
</div>