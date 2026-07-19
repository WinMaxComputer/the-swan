<?php

namespace App\Http\Controllers\Checkout;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
// use Xendit\Xendit;
use Xendit\Invoice\InvoiceApi;
use App\Http\Services\Checkout\CheckoutService as Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Mail;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CheckoutController extends BaseController {

    public function index() {
        return View::make("v1/checkout/checkout");
    }


    public function onSubmit() {
        return View::make("pages.service");
    }


    public function create(Request $req) {
        $service = new Service();

       

        try {

            return $service->createInvoice1($req->all());
            

        } catch (\Throwable $e) {

            $response['message'] = $e->getMessage();
        }

        // return $service->createInvoice1($req->all());
    }

    public function createReservasi(Request $req) {

        $startDate = date("Y-m-d", strtotime($req->cek_in));
        $endDate = date("Y-m-d", strtotime($req->cek_out));
        $dateRange = CarbonPeriod::create($startDate, $endDate);
        // $dates = $dateRange->toArray();
        $count = count($dateRange);
        $i=0;
        $detail = [];
        $status = "";
        
        $users = DB::table("room_nomors")->select('*')
        ->whereNOTIn('room_no', DB::table('reservation_room_detail')
                    ->select('no_room')
                    // ->from('reservation_room_detail')
                    ->where('tgl', $startDate)
                    // ->where('status', '!=', 'In House')
                    ->where('status', '!=', 'cekout')
                    
        )
        ->where('unit_code', $req->kode_product)
        ->get();
        $no_kamar = $users['0']->room_no ;
        
        foreach ($dateRange as $date) {
            // $detail[] = [
            //   "tgl" => $date->format('Y-m-d')
            // ];
            $i++;
            if($i == 1){
                $status = "cekin";
            }elseif($i == $count){
                $status = "cekout";
            }else{
                $status = "In House" ;
            }

            
            
            $getDetail = DB::table('rates')
                ->where('tgl', $date->format('Y-m-d'))
                ->where('kode_kamar', $req->kode_product)
                ->get();
            $detail[] = [
                'no_reservasi' => $req->external_id,
                "kode_unit" => $req->kode_product,
                "tgl" => $date->format('Y-m-d'),
                "no_room" => $no_kamar,
                "harga" => $getDetail['0']->harga,
                "status" => $status
            ];

            
                
            // if($i==($count-1)){
            //     // echo 'skip';
            // }else{
                // echo $value;
            if($i == ($count) ) continue ;
            DB::table('rates')
            ->where('tgl', $date->format('Y-m-d'))
            ->where('kode_kamar', $req->kode_product)
            ->update([
                'stok' => $getDetail['0']->stok - 1,
            ]);
            // };
            
            // var_dump($getDetail);
        }

        DB::table('reservation_room_detail')->insert($detail);
        // var_dump($detail);
        $post = DB::table('reservations')->insert([
            'no_reservasi' => $req->external_id,
            'guest_email' => $req->payer_email,
            'code_service' => $req->kode_product,
            'tgl_reservasi' => $req->tgl_reservasi,
            'guest_name' => $req->name,
            'room_no' => $no_kamar,
            'cek_in' => $req->cek_in,
            'cek_out' => $req->cek_out,
            'payment_type' => $req->type_bayar.'-Xendit',
            'status' => 'PENDING', // $req->status,
            'guest_paid' => $req->amount,
            'book_status' => 'New',
            'adult' => $req->adult, // $req->amount,
            'total' => $req->total,
            // 'snap_token' => 
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()

        ]);
        $this->broadcastPropertyCalendarChanged('xendit-reservation-created');
        // if($post){
        //     var_dump($post);
        // }
        $text = substr($req->email, 0, 6);
        $pass = Hash::make($text);
        DB::table('guests')->upsert([
            'name' => $req->name,
            'email' => $req->payer_email,
            'password' => $pass,
            'nationality' => $req->nationality,
            'mobile' => $req->mobile,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ], 'email' );

        \Mail::send('pages.email-booking', array(
            'name' => $req->name,
            'email' => $req->payer_email,
            'subject' => 'Confirm',
            'no_reservasi' => $req->external_id,
            'code_service' => $req->kode_product,
            'tgl_reservasi' => $req->tgl_reservasi,
            'nationality' => $req->nationality,
            'cek_in' => $req->cek_in,
            'cek_out' => $req->cek_out,
            'adult' => $req->adult,
            'type_bayar' => $req->type_bayar.'-Xendit',
            'total_bayar' => $req->total,
            'total' => $req->amount,
            'detail' => $detail
        ), function($message) use ($req){
            // $message->from($request->email);
            $message->to('winmaxcomp@gmail.com', 'No-replay')->subject('Booking Confirm');
            $message->to($req->payer_email, $req->name)->subject('Booking Confirm');
        });
        // return $service->createInvoice1($req->all());
    }

    private function broadcastPropertyCalendarChanged(string $reason): void
    {
        try {
            broadcast(new \App\Events\PropertyCalendarChanged($reason));
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}
