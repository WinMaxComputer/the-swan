<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Validator;

class backendController extends Controller
{
    //
    public function storeMedia(Request $request)
    {
        $path = public_path('assets/img/rooms/'); //storage_path('tmp/uploads');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $file = $request->file('file');
        $name = uniqid() . '_' . trim($file->getClientOriginalName());
        $file->move($path, $name);
        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }
    public function deleteMedia(Request $request)
    {
        $toDelete= $request->filetodelete ;
        unlink(public_path('assets/img/rooms/'.$toDelete));
    }

    public function store(Request $request)
    {
        try{
            $exception = DB::transaction(function() use ($request){ 
            
                $data = $request->all();
            
                $gmbr = "";
                $foto = $data['document'];
                foreach($foto as $ft){
                    $gmbr = $gmbr.$ft.";" ;
                }
                $fasi = implode(';', $data['facility']);
                $project = DB::table('bookings')->upsert([
                    'id' => $data['id'],
                    'code' => $data['code'],
                    'real_name' => $data['real_name'],
                    'real_address' => $data['real_address'],
                    'title' => $data['title'],
                    'slug' => $data['slug'],
                    'desc' => $data['desc'],
                    'price' => $data['price'],
                    'facility' => ';'.$fasi,
                    'lang' => $data['lang'],
                    'alotment' => $data['allotment'],
                    'area' => $data['area'],
                    'map' => $data['map'],
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                ], 'id');

                DB::table('room_fotos')->upsert([
                    'code' => $data['code'],
                    'foto' => $gmbr,
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                ], 'code');
                
                DB::table('room_nomors')->where('unit_code', $data['code'])->delete();
                for ($i = 0; $i < $data['allotment']; $i++) {
                    DB::table('room_nomors')->insert([
                        'room_code' => $i+1 ,
                        'room_no' => $i+1 ,
                        'unit_code' => $data['code'],
                        'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                        'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                    ]);
                }
                // echo $gmbr ;
                // print_r($data['document']);
                // foreach ($request->input('document', []) as $file) {
                //     \File::move(storage_path('tmp/uploads/'.$file), public_path('assets/img/rooms/'.$file));
                //     // echo $file ;
                // }
                DB::commit();
            });
            if(is_null($exception)) {
                // return response()->json([
                //     'success' => true,
                //     'message' => 'Post Berhasil Diupdate!',
                // ], 200);
                return redirect()->route('pages.rooms');
            } else {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Post Gagal Diupdate!',
                ], 500);
                // return redirect()->route('pages.room_add');
            }
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return response()->json([
             'success' => false,
             'message' => 'exception'.$e,
         ], 400);
            // return redirect()->route('pages.room_add');
        }

        // return redirect()->route('pages.rooms');
    }

    public function edit($room_code){
        $roomDetail = DB::table('bookings')->where('bookings.id', $room_code)
                    ->join('room_fotos', 'room_fotos.code', 'bookings.code')
                    ->select('bookings.*', 'room_fotos.foto')
                    ->first();
        $fasilitas = DB::table('facilities')->get();

        return view('admin.pages.room_add', compact('roomDetail', 'fasilitas'));
    }

    public function storeMediaTour(Request $request)
    {
        $path = public_path('assets/img/tour/'); //storage_path('tmp/uploads');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $file = $request->file('file');
        $name = uniqid() . '_' . trim($file->getClientOriginalName());
        $file->move($path, $name);
        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }
    public function deleteMediaTour(Request $request)
    {
        $toDelete= $request->filetodelete ;
        unlink(public_path('assets/img/tour/'.$toDelete));
    }

public function storeTour(Request $request)
    {
        try{
            $exception = DB::transaction(function() use ($request){ 
            
                $data = $request->all();
            
                $gmbr = "";
                $foto = $data['document'];
                foreach($foto as $ft){
                    $gmbr = $gmbr.$ft.";" ;
                }
                $desti = implode(';', $data['destination']);
                $project = DB::table('tour_packages')->upsert([
                    'id' => $data['id'],
                    'code' => $data['code'],
                    'type' => $data['type'],
                    'tour_name' => $data['tour_name'],
                    'slug' => $data['slug'],
                    'area_tour' => $data['area_tour'],
                    'itinerary' => $data['itinerary'],
                    'price' => $data['price'],
                    'destination' => $desti.';',
                    'lang' => $data['lang'],
                    'payment' => $data['payment'],
                    'note' => $data['note'],
                    'pickup' => $data['pickup'],
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                ], 'id');
                DB::table('tour_fotos')->upsert([
                    'code' => $data['code'],
                    'foto' => $gmbr,
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                ], 'code');

                DB::commit();
            });
            if(is_null($exception)) {
                // return response()->json([
                //     'success' => true,
                //     'message' => 'Post Berhasil Diupdate!',
                // ], 200);
                return redirect()->route('pages.tour');
            } else {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Post Gagal Diupdate!',
                ], 500);
                // return redirect()->route('pages.room_add');
            }
} catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return response()->json([
             'success' => false,
             'message' => 'exception'.$e
            ], 400);
            // return redirect()->route('pages.room_add');
        }

        // return redirect()->route('pages.rooms');
    }

    public function editTour($room_code){
        $tourDetail = DB::table('tour_packages')->where('tour_packages.id', $room_code)
                    ->join('tour_fotos', 'tour_fotos.code', 'tour_packages.code')
                    ->select('tour_packages.*', 'tour_fotos.foto')
                    ->first();
        $areas = DB::table('travel_area')->get();
        // return redirect()->route('pages.room_add');
        $destinasi = DB::table('destinations')->get();

        return view('admin.pages.tour_add', compact('tourDetail', 'destinasi', 'areas'));

    }

    public function storeMediaDestinasi(Request $request)
    {
        $path = public_path('assets/img/destinasi/'); //storage_path('tmp/uploads');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $file = $request->file('file');
        $name = uniqid() . '_' . trim($file->getClientOriginalName());
        $file->move($path, $name);
        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }
    public function deleteMediaDestinasi(Request $request)
    {
        $toDelete= $request->filetodelete ;
        unlink(public_path('assets/img/destinasi/'.$toDelete));
    }

    public function storeDestinasi(Request $request)
    {
        try{
            $exception = DB::transaction(function() use ($request){ 
            
                $data = $request->all();
            
                $gmbr = "";
                $foto = $data['document'];
                foreach($foto as $ft){
                    $gmbr = $gmbr.$ft.";" ;
                }
                // $desti = implode(';', $data['destination']);
                $project = DB::table('destinations')->upsert([
                    'id' => $data['id'],
                    'code' => $data['code'],
                    'deskripsi' => $data['deskripsi'],
                    'name' => $data['name'],
                    'slug' => $data['slug'],
                    'type' => $data['type'],
                    'lang' => $data['lang'],
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                ], 'id');
                DB::table('destination_fotos')->upsert([
                    'code' => $data['code'],
                    'foto' => $gmbr,
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                ], 'code');

                DB::commit();
            });
            if(is_null($exception)) {
                // return response()->json([
                //     'success' => true,
                //     'message' => 'Post Berhasil Diupdate!',
                // ], 200);
                return redirect()->route('pages.destinasi');
            } else {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Post Gagal Diupdate!',
                ], 500);
                // return redirect()->route('pages.room_add');
            }
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return response()->json([
             'success' => false,
             'message' => 'exception'.$e,
         ], 400);
            // return redirect()->route('pages.room_add');
        }

        // return redirect()->route('pages.rooms');
    }

    public function editDestinasi(Request $request){
        $destinasi_code = $request->id ;
        $type = $request->type ;

        $destinasiDetail = DB::table('destinations')->where('destinations.id', $destinasi_code)
                    ->join('destination_fotos', 'destination_fotos.code', 'destinations.code')
                    ->select('destinations.*', 'destination_fotos.foto')
                    ->first();
        if($type == 'copy'){
            $data = 'copy';
        }else{
            $data = 'edit';
        }
        return view('admin.pages.destinasi_add', ['destinasiDetail' => $destinasiDetail, 'type' => $data]);

    }

    public function storeMediaActivity(Request $request)
    {
        $path = public_path('assets/img/activity/'); //storage_path('tmp/uploads');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $file = $request->file('file');
        $name = uniqid() . '_' . trim($file->getClientOriginalName());
        $file->move($path, $name);
        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }
    public function deleteMediaActivity(Request $request)
    {
        $toDelete= $request->filetodelete ;
        unlink(public_path('assets/img/activity/'.$toDelete));
    }

    public function storeActivity(Request $request)
    {
        try{
            $exception = DB::transaction(function() use ($request){ 
            
                $data = $request->all();
            
                $gmbr = "";
                $foto = $data['document'];
                foreach($foto as $ft){
                    $gmbr = $gmbr.$ft.";" ;
                }
                // $type = implode(';', $data['type']);
                $project = DB::table('activities')->upsert([
                    'id' => $data['id'],
                    'code' => $data['code'],
                    'deskripsi' => $data['deskripsi'],
                    'name' => $data['name'],
                    'slug' => $data['slug'],
                    'type' => $data['type'],
                    'lang' => $data['lang'],
                    'area' => $data['area'],
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                ], 'id');
                DB::table('activity_fotos')->upsert([
                    'code' => $data['code'],
                    'foto' => $gmbr,
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                ], 'code');

                DB::commit();
            });
            if(is_null($exception)) {
                // return response()->json([
                //     'success' => true,
                //     'message' => 'Post Berhasil Diupdate!',
                // ], 200);
                return redirect()->route('pages.activity');
            } else {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Post Gagal Diupdate!',
                ], 500);
                // return redirect()->route('pages.room_add');
            }
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return response()->json([
             'success' => false,
             'message' => 'exception'.$e,
         ], 400);
            // return redirect()->route('pages.room_add');
        }

        // return redirect()->route('pages.rooms');
    }

    public function editActivity($code){
        // $tourDetail = DB::table('tour_packages')->where('code', $room_code)->first();
        // return redirect()->route('pages.room_add');
        $activityDetail = DB::table('activities')->where('activities.id', $code)
                    ->join('activity_fotos', 'activity_fotos.code', 'activities.code')
                    ->select('activities.*', 'activity_fotos.foto')
                    ->first();


        return view('admin.pages.activity_add', compact('activityDetail'));

    }

    public function storeMediaProducts(Request $request)
    {
        $path = public_path('assets/img/products/'); //storage_path('tmp/uploads');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $file = $request->file('file');
        $name = uniqid() . '_' . trim($file->getClientOriginalName());
        $file->move($path, $name);
        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }
    public function deleteMediaProducts(Request $request)
    {
        $toDelete= $request->filetodelete ;
        unlink(public_path('assets/img/products/'.$toDelete));
    }

    public function storeProducts(Request $request)
    {
        try{
            $exception = DB::transaction(function() use ($request){ 
                $data = $request->all();
                
                $gmbr = "";
                $foto = $data['document'] ?? [];
                foreach($foto as $ft){
                    $gmbr = $gmbr.$ft.";" ;
                }
                $parent = isset($data['parent_type']) ? implode(';', $data['parent_type']).';' : '';
                
                // Auto-generate code if empty
                $productCode = $data['product_code'] ?? '';
                if (empty($productCode)) {
                    $productCode = 'PRD-' . substr(time(), -6);
                }
                
                $project = DB::table('products')->upsert([
                    'id' => $data['id'] ?? null,
                    'product_code' => $productCode,
                    'product_des' => $data['product_des'] ?? '',
                    'product_name' => $data['product_name'] ?? '',
                    'slug' => $data['slug'] ?? Str::slug($data['product_name'] ?? 'product'),
                    'parent_type' => $parent,
                    'product_foto' => $gmbr,
                    'price' => $data['price'] ?? 0,
                    'lang' => $data['lang'] ?? config('app.locale'),
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                ], 'id');

                DB::commit();
            });
            if(is_null($exception)) {
                return redirect()->route('pages.products');
            } else {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Post Gagal Diupdate!',
                ], 500);
                // return redirect()->route('pages.room_add');
            }
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            // return response()->json([
            //  'success' => false,
            //  'message' => $e->getMessage(),
            // ], 400);
            // return redirect()->route('pages.room_add');
            return redirect()->back()->with('success', $e->getMessage());   
        }

        // return redirect()->route('pages.rooms');
    }

    public function editProducts($code){
        // $tourDetail = DB::table('tour_packages')->where('code', $room_code)->first();
        // return redirect()->route('pages.room_add');
        $productsDetail = DB::table('products')->where('id', $code)->first();

        $activities = DB::table('activities')->get();
        return view('admin.pages.products_add', compact('productsDetail', 'activities'));

    }

    public function guestOrder(Request $request){
        $email = $request->email ;
        $data = DB::table('reservations')->where('guest_email', $email)->orderBy('tgl_reservasi', 'DESC')->get();
         return response()->json([
            'success' => true,
            'message' => 'your list reservasi!',
            'data' => $data
        ], 200);

    }

    public function guestLogin(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $user= DB::table('guests')->where('email', $request->email)
                ->join('countries', 'guests.nationality', 'countries.country_code')    
                ->first();
        
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'success'   => false,
                    'message' => ['These credentials do not match our records.']
                ], 404);
            }
               
            $response = [
                'success'   => true,
                'user'      => $user,
            ];
        
        return response($response, 201);

    }
    


    /**
     * Update or create a room rate and stock for a specific date.
     */
    public function updateRate(Request $request)
    {
        $request->validate([
            'tgl' => 'required|date',
            'kode_kamar' => 'required|string',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
        ]);

        try {
            DB::table('rates')->updateOrInsert(
                ['tgl' => $request->tgl, 'kode_kamar' => $request->kode_kamar],
                [
                    'harga' => $request->harga,
                    'stok' => $request->stok,
                    'updated_at' => Carbon::now()
                ]
            );

            return redirect()->back()->with('success', 'Rate and availability updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update or create room rates and stock for a date range.
     */
    public function bulkUpdateRate(Request $request)
    {
        $request->validate([
            'kode_kamar' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
        ]);

        try {
            $period = CarbonPeriod::create($request->start_date, $request->end_date);
            
            foreach ($period as $date) {
                DB::table('rates')->updateOrInsert(
                    ['tgl' => $date->format('Y-m-d'), 'kode_kamar' => $request->kode_kamar],
                    ['harga' => $request->harga, 'stok' => $request->stok, 'updated_at' => Carbon::now()]
                );
            }

            return redirect()->back()->with('success', 'Bulk update completed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Bulk update failed: ' . $e->getMessage());
        }
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
                    $q->where('isi', 'like', "%{$search}%")
                      ->orWhere('judul', 'like', "%{$search}%")
                      ->orWhere('lang', 'like', "%{$search}%");
                });
            })
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.pages.news', ['news' => $news]);    
    }
    public function editNews($code){
        $newsDetail = DB::table('artikels')->where('artikels.id', $code)
                    ->leftJoin('artikel_fotos', 'artikel_fotos.code', 'artikels.code')
                    ->select('artikels.*', 'artikel_fotos.foto')
                    ->first();
        return view('admin.pages.news_add', compact('newsDetail'));

    }
    public function storeNews(Request $request)
    {    
        try{
            $exception = DB::transaction(function() use ($request){ 
            
                $data = $request->all();
            
                $gmbr = "";
                $foto = $data['document'];                
                    foreach($foto as $ft){
                        $gmbr = $gmbr.$ft.";" ;
                    }
                $project = DB::table('artikels')->upsert([
                    'id' => $data['id'],
                    'code' => $data['code'],
                    'judul' => $data['judul'],
                    'slug' => $data['slug'],
                    'lang' => $data['lang'],
                    'isi' => $data['isi'],
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
                ], 'id');
                DB::table('artikel_fotos')->upsert([
                    'code' => $data['code'],
                    'foto' => $gmbr,
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                ], 'code');

                DB::commit();
            });
            if(is_null($exception)) {
                // return response()->json([
                //     'success' => true,
                //     'message' => 'News created successfully!'
                // ]);
                return redirect()->route('pages.news');
            } else {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create news!'  
                    ], 500);
                    // return redirect()->route('pages.news_add');
            }
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return response()->json([
             'success' => false,
             'message' => 'Exception: '.$e->getMessage(),
         ], 400);
            // return redirect()->route('pages.news_add');
        }
    }

    public function deleteNews(Request $request)
    {
        $code = $request->code;
        $id = $request->news_id;
        // Assuming 'artikel_fotos' also needs to be deleted or handled
        try {
            DB::table('artikels')->where('id', $id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'News deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete news: ' . $e->getMessage()
            ], 500);
        }
    }
    public function updateNews(Request $request)
    {   
        try{
            $exception = DB::transaction(function() use ($request){ 
            
                $data = $request->all();
            
                $gmbr = "";
                $foto = $data['document'];
                foreach($foto as $ft){
                    $gmbr = $gmbr.$ft.";" ;
                }
                $project = DB::table('artikels')->upsert([
                    'id' => $data['id'],
                    'code' => $data['code'],
                    'judul' => $data['judul'],
                    'slug' => $data['slug'],
                    'lang' => $data['lang'],
                    'isi' => $data['isi'],
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
                ], 'id');
                DB::table('artikel_fotos')->upsert([
                    'code' => $data['code'],
                    'foto' => $gmbr,
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                ], 'code'); 
                DB::commit();
            });
            if(is_null($exception)) {
                return redirect()->route('pages.news');
            } else {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update news!'  
                    ], 500);
                    // return redirect()->route('pages.news_add');
                    }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Exception: '.$e->getMessage(),
            ], 400);
        }
    }
    public function deleteMediaNews(Request $request)
    {
        $toDelete= $request->filetodelete ;
        unlink(public_path('assets/img/news/'.$toDelete));
    }
    public function storeMediaNews(Request $request)
    {
        $path = public_path('assets/img/news/');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $file = $request->file('file');
        $name = uniqid() . '_' . trim($file->getClientOriginalName());
        $file->move($path, $name);
        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }
}
