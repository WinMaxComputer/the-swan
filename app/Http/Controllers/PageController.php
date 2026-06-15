<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PageController extends Controller
{
    /**
     * Display icons page
     *
     * @return \Illuminate\View\View
     */
    public function icons()
    {
        return view('admin.pages.icons');
    }

    public function properti()
    {
        return view('admin.pages.properti');
    }

    /**
     * Display maps page
     *
     * @return \Illuminate\View\View
     */
    public function maps()
    {
        return view('admin.pages.maps');
    }

    /**
     * Display tables page
     *
     * @return \Illuminate\View\View
     */
    public function tables()
    {
        return view('admin.pages.tables');
    }

    /**
     * Display notifications page
     *
     * @return \Illuminate\View\View
     */
    public function notifications()
    {
        return view('admin.pages.notifications');
    }

    /**
     * Display rtl page
     *
     * @return \Illuminate\View\View
     */
    public function rtl()
    {
        return view('admin.pages.rtl');
    }

    /**
     * Display typography page
     *
     * @return \Illuminate\View\View
     */
    public function typography()
    {
        return view('admin.pages.typography');
    }

    public function rooms(Request $request)
    {
        $defaultLocale = config('app.locale');
        $lang = $request->query('lang', $defaultLocale);
        $search = $request->query('search');
        $perPage = $request->query('perPage', 10);

        $room = DB::table('bookings')
            ->where('lang', $lang)
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                      ->orWhere('title', 'like', "%{$search}%")
                      ->orWhere('real_name', 'like', "%{$search}%");
                });
            })
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.pages.rooms', ['room' => $room]);
    }
    public function roomAdd()
    {
        // $defaultLocale = config('app.locale');
        $fasilitas = DB::table('facilities')->get();
        return view('admin.pages.room_add', compact('fasilitas'));
    }

    public function rates()
    {
        $defaultLocale = config('app.locale');

        // Generate a collection of dates for the next 30 days
        $dates = collect();
        for ($i = 0; $i < 30; $i++) {
            $dates->push(Carbon::now()->addDays($i));
        }

        $rates = DB::table('rates')->get();

        // Fetch unique room types from the bookings table for rate management
        $rooms = DB::table('bookings')
            ->where('lang', $defaultLocale)
            ->get();

        return view('admin.pages.rates', compact('rates', 'rooms', 'dates'));
    }

    public function tour(Request $request)
    {
        $defaultLocale = config('app.locale');
        $lang = $request->query('lang', $defaultLocale);
        $search = $request->query('search');
        $perPage = $request->query('perPage', 10);

        $tur = DB::table('tour_packages')
            ->where('lang', $lang)
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                      ->orWhere('tour_name', 'like', "%{$search}%")
                      ->orWhere('type', 'like', "%{$search}%");
                });
            })
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.pages.tour', ['tour' => $tur]);
    }
    public function tourAdd()
    {
        // $defaultLocale = config('app.locale');
        $destinasi = DB::table('destinations')->get();
        $areas = DB::table('travel_area')->get();
        return view('admin.pages.tour_add', compact('destinasi', 'areas'));
    }
    /**
     * Display upgrade page
     *
     * @return \Illuminate\View\View
     */
    public function destinasi(Request $request)
    {
        $defaultLocale = config('app.locale');
        $lang = $request->query('lang', $defaultLocale);
        $search = $request->query('search');
        $perPage = $request->query('perPage', 10);

        $destinasi = DB::table('destinations')
            ->when($lang, function ($query, $lang) {
                return $query->where('lang', $lang);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%")
                      ->orWhere('type', 'like', "%{$search}%");
                });
            })
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.pages.destinasi', ['destinasi' => $destinasi]);
    }
    public function destinasiAdd()
    {
        // $defaultLocale = config('app.locale');
        return view('admin.pages.destinasi_add');
    }
    public function activity(Request $request)
    {
        $defaultLocale = config('app.locale');
        $lang = $request->query('lang', $defaultLocale);
        $search = $request->query('search');
        $perPage = $request->query('perPage', 10);

        $activity = DB::table('activities')
            ->when($lang, function ($query, $lang) {
                return $query->where('lang', $lang);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%")
                      ->orWhere('area', 'like', "%{$search}%")
                      ->orWhere('type', 'like', "%{$search}%");
                });
            })
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.pages.activity', ['activity' => $activity]);
    }
    public function activityAdd()
    {
        // $defaultLocale = config('app.locale');
        return view('admin.pages.activity_add');
    }
    public function products(Request $request){
        $search = $request->query('search');
        $perPage = $request->query('perPage', 10);

        $products = DB::table('products')
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('product_code', 'like', "%{$search}%")
                      ->orWhere('product_name', 'like', "%{$search}%")
                      ->orWhere('product_des', 'like', "%{$search}%")
                      ->orWhere('parent_type', 'like', "%{$search}%");
                });
            })
            ->paginate($perPage)
            ->withQueryString();

        $defaultLocale = config('app.locale');
        $activities = DB::table('activities')
            ->where('lang', $defaultLocale)
            ->pluck('name', 'code');

        foreach ($products as $product) {
            $codes = array_unique(array_filter(explode(';', $product->parent_type)));
            $names = array_map(function($code) use ($activities) {
                return $activities[$code] ?? $code;
            }, $codes);
            $product->activity_names = implode(', ', $names);
        }

        return view('admin.pages.products', ['products' => $products]);
    }
    public function productsAdd(){

        $activities = DB::table('activities')->get();
        return view('admin.pages.products_add', ['activities' => $activities]);
    }
    public function news(Request $request)
    {
        $defaultLocale = config('app.locale');
        $lang = $request->query('lang', $defaultLocale);
        $search = $request->query('search');
        $perPage = $request->query('perPage', 10);

        $news = DB::table('artikels')
            ->where('lang', $lang)
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('isi', 'like', "%{$search}%");
                });
            })
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.pages.news', ['news' => $news]);
    }
    public function newsAdd()
    {
        // $defaultLocale = config('app.locale');
        return view('admin.pages.news_add', ['newsDetail' => null]);
    }
    
}
