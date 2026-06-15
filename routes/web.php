<?php

use Illuminate\Support\Facades\Route;
use App\Models\Booking;
use App\Models\Transport;
use App\Models\TourPackage;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () { return view('pages.home'); });
// Route::get('/service', function () {
//     return view('pages.service');
// });
// Route::get('/booking', function () {
//     return view('pages.booking');
// });https://d3d2-120-188-76-241.ngrok-free.app/
// Route::get('/booking', [App\Http\Controllers\bookingController::class , 'index']);


if (file_exists(app_path('Http/Controllers/LocalizationController.php')))
{
    Route::get('/lang', [App\Http\Controllers\LocalizationController::class , 'lang'])->name('lang');
    // Route::get('/', function () { return view('pages.home'); });
    Route::get('/', [App\Http\Controllers\bookingController::class , 'home']);
    Route::get('/about-us', function () { return view('pages.about'); });
    Route::get('/gallery', [App\Http\Controllers\bookingController::class , 'galeri']);
    Route::get('/events', [App\Http\Controllers\bookingController::class , 'event']);
    Route::get('/contact_us', [App\Http\Controllers\ContactUsController::class , 'index']);
    
    Route::get('/transport', [App\Http\Controllers\bookingController::class , 'transport']);
    Route::get('/tour_packages', [App\Http\Controllers\bookingController::class , 'tour']);
    Route::get('/service', [App\Http\Controllers\bookingController::class , 'service'])->name('service');
    Route::get('/tour_packages/{slug}', [App\Http\Controllers\bookingController::class , 'tourDetail']);
    Route::get('/hotels', [App\Http\Controllers\bookingController::class , 'hotel']);
    Route::get('/bookings/{slug}', [App\Http\Controllers\bookingController::class , 'hotelDetail']);
    Route::get('/destinations', [App\Http\Controllers\bookingController::class , 'destination']);
    Route::get('/destinations/{slug}', [App\Http\Controllers\bookingController::class , 'destinationDetail']);
    Route::get('/activities', [App\Http\Controllers\bookingController::class , 'activity']);
    Route::get('/activities/{slug}', [App\Http\Controllers\bookingController::class , 'activityDetail']);

    Route::get('/detail-reservasi/{id}', [App\Http\Controllers\bookingController::class , 'bookDetail']);

    Route::get('/try-checkout', [App\Http\Controllers\Checkout\CheckoutController::class, 'onSubmit']);
}

// Route::post('/contact-us', ['App\Http\Controllers\ContactUsController', 'send'])->name('contact.send');
Route::post('/contact-us', [App\Http\Controllers\ContactUsController::class, 'send']);
Route::post('/review-store', [App\Http\Controllers\bookingController::class, 'reviewstore'])->name('review.store');

//================paypal
Route::get('paypal', [App\Http\Controllers\PayPalController::class, 'index'])->name('paypal');
Route::get('paypal/payment', [App\Http\Controllers\PayPalController::class, 'payment'])->name('paypal.payment');
Route::get('paypal/payment/success', [App\Http\Controllers\PayPalController::class, 'paymentSuccess'])->name('paypal.payment.success');
Route::get('paypal/payment/cancel', [App\Http\Controllers\PayPalController::class, 'paymentCancel'])->name('paypal.payment/cancel');

//====tess
Route::get('display-user', [App\Http\Controllers\bookingController::class, 'getLoc']);
Route::post('get-product', [App\Http\Controllers\bookingController::class, 'getProduct'])->name('get.product');
Route::post('donation/pay', [App\Http\Controllers\OrderController::class, 'pay'])->name('donation.pay');
//=====callback
Route::post('/midtrans-status', [App\Http\Controllers\callbackController::class, 'midtrans']);
Route::post('/book-status', [App\Http\Controllers\callbackController::class, 'suksesPayment']);
Route::post('/paypal-callback', [App\Http\Controllers\callbackController::class, 'paypalComplete']);


Route::get('/sitemap', function(){
    $sitemap = Sitemap::create()
    ->add(Url::create('/about-us'))
    ->add(Url::create('/contact_us'))
    ->add(Url::create('/gallery'))
    ->add(Url::create('/transport'))
    ->add(Url::create('/tour_packages'))
    ->add(Url::create('/hotels'));
   
    $book = Booking::all();
    foreach ($book as $book) {
        $sitemap->add(Url::create("/bookings/{$book->slug}"));
    }
    $sitemap->writeToFile(public_path('sitemap.xml'));

    $activities = DB::table('activities')->get();
    foreach ($activities as $act) {
        $sitemap->add(Url::create("/activities/{$act->slug}"));
    }
    $sitemap->writeToFile(public_path('sitemap.xml'));

    $destinasi = DB::table('destinations')->get();
    foreach ($destinasi as $des) {
        $sitemap->add(Url::create("/destinations/{$des->slug}"));
    }
    $sitemap->writeToFile(public_path('sitemap.xml'));

    $tour = TourPackage::all();
    foreach ($tour as $tur) {
        $sitemap->add(Url::create("/tour_packages/{$tur->slug}"));
    }
    $sitemap->writeToFile(public_path('sitemap.xml'));

    
}); 

//======================guest
Route::post('guest-order', [App\Http\Controllers\backendController::class, 'guestOrder'])->name('guest.order');
Route::post('guest-login', [App\Http\Controllers\backendController::class, 'guestLogin'])->name('guest.login');

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Auth::routes();
Route::group(['middleware' => ['auth']], function() {
    // your routes
    Route::get('properti', ['as' => 'pages.properti', 'uses' => 'App\Http\Controllers\PageController@properti']);
    Route::get('icons', ['as' => 'pages.icons', 'uses' => 'App\Http\Controllers\PageController@icons']);
    Route::get('rooms', ['as' => 'pages.rooms', 'uses' => 'App\Http\Controllers\PageController@rooms']);
    Route::get('rates', ['as' => 'pages.rates', 'uses' => 'App\Http\Controllers\PageController@rates']);
    Route::get('tour', ['as' => 'pages.tour', 'uses' => 'App\Http\Controllers\PageController@tour']);
    Route::get('news', ['as' => 'pages.news', 'uses' => 'App\Http\Controllers\PageController@news']);
    Route::get('destinasi', ['as' => 'pages.destinasi', 'uses' => 'App\Http\Controllers\PageController@destinasi']);
    Route::get('activity', ['as' => 'pages.activity', 'uses' => 'App\Http\Controllers\PageController@activity']);
    Route::get('products', ['as' => 'pages.products', 'uses' => 'App\Http\Controllers\PageController@products']);
    //=================rate
    // Route::get('rates', ['as' => 'pages.rates', 'uses' => 'App\Http\Controllers\PageController@rates']);
    Route::post('rates/update', [App\Http\Controllers\backendController::class, 'updateRate'])->name('rates.update');
    Route::post('rates/bulk-update', [App\Http\Controllers\backendController::class, 'bulkUpdateRate'])->name('rates.bulkUpdate');
    //============room
    Route::get('room-add', ['as' => 'pages.room_add', 'uses' => 'App\Http\Controllers\PageController@roomAdd']);
    Route::post('room/media', [App\Http\Controllers\backendController::class, 'storeMedia'])->name('room.storeMedia');
    Route::post('room/media/delete', [App\Http\Controllers\backendController::class, 'deleteMedia'])->name('room.deleteMedia');
    Route::post('room-store', [App\Http\Controllers\backendController::class, 'store'])->name('room.store');
    Route::get('room-edit/{room_code}', [App\Http\Controllers\backendController::class, 'edit'])->name('room.edit');
    //=========tour
    Route::get('tour-add', ['as' => 'pages.tour_add', 'uses' => 'App\Http\Controllers\PageController@tourAdd']);
    Route::post('tour/media', [App\Http\Controllers\backendController::class, 'storeMediaTour'])->name('tour.storeMedia');
    Route::post('tour/media/delete', [App\Http\Controllers\backendController::class, 'deleteMediaTour'])->name('tour.deleteMedia');
    Route::post('tour-store', [App\Http\Controllers\backendController::class, 'storeTour'])->name('tour.store');
    Route::get('tour-edit/{tour_code}', [App\Http\Controllers\backendController::class, 'editTour'])->name('tour.edit');
    //=========news
    Route::get('news-add', ['as' => 'pages.news_add', 'uses' => 'App\Http\Controllers\PageController@newsAdd']);
    Route::post('news/media', [App\Http\Controllers\backendController::class, 'storeMediaNews'])->name('news.storeMedia');
    Route::post('news/media/delete', [App\Http\Controllers\backendController::class, 'deleteMediaNews'])->name('news.deleteMedia');
    Route::post('news-store', [App\Http\Controllers\backendController::class, 'storeNews'])->name('news.store');
    Route::post('news-delete', [App\Http\Controllers\backendController::class, 'deleteNews'])->name('news.delete');
    Route::get('news-edit/{news_code}', [App\Http\Controllers\backendController::class, 'editNews'])->name('news.edit');
    
    //=========destinasi
    Route::get('destinasi-add', ['as' => 'pages.destinasi_add', 'uses' => 'App\Http\Controllers\PageController@destinasiAdd']);
    Route::post('destinasi/media', [App\Http\Controllers\backendController::class, 'storeMediaDestinasi'])->name('destinasi.storeMedia');
    Route::post('destinasi/media/delete', [App\Http\Controllers\backendController::class, 'deleteMediaDestinasi'])->name('destinasi.deleteMedia');
    Route::post('destinasi-store', [App\Http\Controllers\backendController::class, 'storeDestinasi'])->name('destinasi.store');
    Route::get('destinasi-edit', [App\Http\Controllers\backendController::class, 'editDestinasi'])->name('destinasi.edit');
    //=========bali aktiviti
    Route::get('activity-add', ['as' => 'pages.activity_add', 'uses' => 'App\Http\Controllers\PageController@activityAdd']);
    Route::post('activity/media', [App\Http\Controllers\backendController::class, 'storeMediaActivity'])->name('activity.storeMedia');
    Route::post('activity/media/delete', [App\Http\Controllers\backendController::class, 'deleteMediaActivity'])->name('activity.deleteMedia');
    Route::post('activity-store', [App\Http\Controllers\backendController::class, 'storeActivity'])->name('activity.store');
    Route::get('activity-edit/{activity_code}', [App\Http\Controllers\backendController::class, 'editActivity'])->name('activity.edit');

    //=========bali products
    Route::get('products-add', ['as' => 'pages.products_add', 'uses' => 'App\Http\Controllers\PageController@productsAdd']);
    Route::post('products/media', [App\Http\Controllers\backendController::class, 'storeMediaProducts'])->name('products.storeMedia');
    Route::post('products/media/delete', [App\Http\Controllers\backendController::class, 'deleteMediaProducts'])->name('products.deleteMedia');
    Route::post('products-store', [App\Http\Controllers\backendController::class, 'storeProducts'])->name('products.store');
    Route::get('products-edit/{product_code}', [App\Http\Controllers\backendController::class, 'editProducts'])->name('products.edit');

});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show', 'register']]);

Route::get('maps', ['as' => 'pages.maps', 'uses' => 'App\Http\Controllers\PageController@maps']);
Route::get('notifications', ['as' => 'pages.notifications', 'uses' => 'App\Http\Controllers\PageController@notifications']);
Route::get('rtl', ['as' => 'pages.rtl', 'uses' => 'App\Http\Controllers\PageController@rtl']);
Route::get('tables', ['as' => 'pages.tables', 'uses' => 'App\Http\Controllers\PageController@tables']);
Route::get('typography', ['as' => 'pages.typography', 'uses' => 'App\Http\Controllers\PageController@typography']);
