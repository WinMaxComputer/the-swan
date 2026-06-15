<a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/vendor/aos/aos.js')}}"></script>
  <script src="{{asset('assets/vendor/glightbox/js/glightbox.min.js')}}"></script>
  <script src="{{asset('assets/vendor/purecounter/purecounter_vanilla.js')}}"></script>
  <script src="{{asset('assets/vendor/swiper/swiper-bundle.min.js')}}"></script>
  <script src="{{asset('assets/vendor/php-email-form/validate.js')}}"></script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://www.google.com/recaptcha/api.js?render=6LfncpwpAAAAAK3qP36whis5OLg29EomK2g9thx0"></script>
  <script src="https://kit.fontawesome.com/e396f0476d.js" crossorigin="anonymous"></script>

  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>

  
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

  <!-- Template Main JS File -->
  <script src="{{asset('assets/js/main.js')}}"></script>

  <script type="text/javascript">
      document.addEventListener("DOMContentLoaded",
      
      function () {
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                }
            });
        // console.log('page load')
        var guest = JSON.parse(localStorage.getItem('guest'));
        var login = document.getElementById("masuk");
        var myakun = document.getElementById("akun");
        if(guest === null){
          let arrGuest = {
              name: '',
              email: '',
              nationality: '',
              country_name: '',
              phone: ''
          }
          localStorage.setItem('guest', JSON.stringify(arrGuest));
          login.style.display = "block";
          myakun.style.display = "none";
          // console.log(guestData.name);
          // alert('null')
        }else{
          // alert('tidak null')
          var guestData = JSON.parse(localStorage.getItem('guest'));
          if(guestData.name !== ""){
            login.style.display = "none";
            myakun.style.display = "block";
            
            if(document.getElementById('name_head')) document.getElementById('name_head').innerHTML = guestData.name;
            if(document.getElementById('email_head')) document.getElementById('email_head').innerHTML = guestData.email;
            if(document.getElementById('mobile_head')) document.getElementById('mobile_head').value =  guestData.phone;
            if(document.getElementById('nationality_head')) document.getElementById('nationality_head').value = guestData.nationality;
            if(document.getElementById('country_name_head')) document.getElementById('country_name_head').value = guestData.country_name;
            //==============Guest Detail
            
            $.ajax({
                type: "POST",
                url: "/guest-order",
                data: { "email": guestData.email },
                error: function (request, error) {
                    console.error("Could not fetch reservations", error);
                },
                success: function (result) {
                  const reservations = result.data;
                  const itemsPerPage = 5;
                  let currentPage = 1;

                  function renderTable(page) {
                    const start = (page - 1) * itemsPerPage;
                    const end = start + itemsPerPage;
                    const paginatedItems = reservations.slice(start, end);
                    
                    var trHTML = '';
                    $.each(paginatedItems, function (i, o){
                        const statusBadge = o.status === 'COMPLETED' || o.status === 'PAID' || o.status === 'settlement' ? 'bg-success' : 'bg-warning';
                        trHTML += '<tr><td style="font-size: 0.8rem;">' + o.no_reservasi +
                                  '</td><td>' + moment(o.cek_in).format('MMM DD, YYYY') +
                                  '</td><td>' + moment(o.cek_out).format('MMM DD, YYYY') +
                                  '</td><td><span class="badge ' + statusBadge + '">' + o.status + '</span></td>' +
                                  '</td><td>' + '<a href="/detail-reservasi/' + o.id + '"><i class="fa-solid fa-eye text-primary"></i></a>' +
                                  '</td></tr>';
                    });
                    $('#table1').html(trHTML);
                    renderPagination();
                  }

                  function renderPagination() {
                    const totalPages = Math.ceil(reservations.length / itemsPerPage);
                    let paginationHTML = '';
                    if (totalPages > 1) {
                        paginationHTML += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}"><a class="page-link" href="javascript:void(0)" onclick="window.changeReservationPage(${currentPage - 1})">Prev</a></li>`;
                        for (let i = 1; i <= totalPages; i++) {
                            paginationHTML += `<li class="page-item ${currentPage === i ? 'active' : ''}"><a class="page-link" href="javascript:void(0)" onclick="window.changeReservationPage(${i})">${i}</a></li>`;
                        }
                        paginationHTML += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}"><a class="page-link" href="javascript:void(0)" onclick="window.changeReservationPage(${currentPage + 1})">Next</a></li>`;
                    }
                    $('#reservation-pagination').html(paginationHTML);
                  }

                  window.changeReservationPage = function(page) {
                    if(page < 1 || page > Math.ceil(reservations.length / itemsPerPage)) return;
                    currentPage = page;
                    renderTable(currentPage);
                  };
                  
                  if (reservations.length > 0) {
                    renderTable(currentPage);
                    // Assuming the latest booking is the first one if sorted by ID DESC in the controller
                    document.getElementById('last_rev').value = reservations[0].no_reservasi;
                  } else {
                    $('#table1').html('<tr><td colspan="5" class="text-center text-muted py-3">No reservations found.</td></tr>');
                  }
                },
            });
          }else{
            login.style.display = "block";
            myakun.style.display = "none";
          }
        }

      }, false);
      function getComboA(selectObject) {
          var value = selectObject.value;
          document.getElementById('nationality').value = value
          // console.log(value);
      };
      function logout(){
        let arrGuest = {
              name: '',
              email: '',
              nationality: '',
              country_name: '',
              phone: ''
          }
          localStorage.setItem('guest', JSON.stringify(arrGuest));
        window.location.reload()
      }
      function login(){
        var email_login =  document.getElementById('loginEmail').value;
        var password =  document.getElementById('loginPassword').value;
        $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),}
            });
        $.ajax({
            type: "POST",
            url: "/guest-login",
            data: { "email": email_login, "password": password },
            error: function (request, error) {
                alert('Login failed. Please check your credentials.');
            },
            success: function (result) {
              let arrGuest = {
                  name: result.user.name,
                  email: result.user.email,
                  nationality: result.user.nationality,
                  country_name: result.user.country_name,
                  phone: result.user.mobile
              }
              localStorage.setItem('guest', JSON.stringify(arrGuest));
              window.location.reload();
            },
        });
      }

      function register(){
        var name = document.getElementById('registerName').value;
        var email = document.getElementById('registerEmail').value;
        var password = document.getElementById('registerPassword').value;
        var password_confirmation = document.getElementById('registerConfirmPassword').value;
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),}
            });
        $.ajax({
            type: "POST",
            url: "/guest-register", // Assuming you have this route
            data: { "name": name, "email": email, "password": password, "password_confirmation": password_confirmation },
            error: function (request, error) {
                alert('Registration failed. Please try again.');
            },
            success: function (result) {
                // Assuming successful registration also logs the user in or provides a token
                // For now, let's just alert success and close the modal
                alert('Registration successful! Please log in.');
                var authModal = bootstrap.Modal.getInstance(document.getElementById('authModal'));
                authModal.hide();
                // Optionally, switch to login tab
                var loginTabTrigger = document.getElementById('login-tab');
                var loginTab = new bootstrap.Tab(loginTabTrigger);
                loginTab.show();
            },
            // dataType: "json"
        });
      }
    
  </script>