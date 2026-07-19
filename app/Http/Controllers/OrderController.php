<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Mail;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class OrderController extends Controller
{
    //
    public function __construct()
    {
        \Midtrans\Config::$serverKey    = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized  = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds        = config('services.midtrans.is3ds');
    }

    public function pay(Request $request){
        DB::transaction(function() use($request) { 
            
            $startDate = date("Y-m-d", strtotime($request->cek_in));
            $endDate = date("Y-m-d", strtotime($request->cek_out));
            $dateRange = CarbonPeriod::create($startDate, $endDate);
            // $dates = $dateRange->toArray();
            $count = count($dateRange);
            $i=0;
            $detail = [];
            $status = "";
            
            $users = DB::table("room_nomors")->select('*')
            ->whereNOTIn('room_no', DB::table('reservation_room_detail')
                        ->select('no_room')
                        ->where('tgl', $startDate)
                        ->where('status', '!=', 'cekout')
            )
            ->where('unit_code', $request->kode_product)
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
                    ->where('kode_kamar', $request->kode_product)
                    ->get();
                $detail[] = [
                    'no_reservasi' => $request->external_id,
                    "kode_unit" => $request->kode_product,
                    "tgl" => $date->format('Y-m-d'),
                    "no_room" => $no_kamar,
                    "harga" => $getDetail['0']->harga,
                    "status" => $status,                        
                ];

                if($i == ($count) ) continue ;
                DB::table('rates')
                ->where('tgl', $date->format('Y-m-d'))
                ->where('kode_kamar', $request->kode_product)
                ->update([
                    'stok' => $getDetail['0']->stok - 1,
                ]);
            }

            DB::table('reservation_room_detail')->insert($detail);
            // var_dump($detail);
            $post = \App\Models\Reservation::create([
                'no_reservasi' => $request->external_id,
                'guest_email' => $request->email,
                'code_service' => $request->kode_product,
                'tgl_reservasi' => $request->tgl_reservasi,
                'guest_name' => $request->name,
                'room_no' => $no_kamar,
                'cek_in' => $request->cek_in,
                'cek_out' => $request->cek_out,
                'payment_type' => $request->type_bayar.'-Midtrans',
                'status' => 'PENDING', // $req->status,
                'guest_paid' => $request->amount,
                'book_status' => 'New',
                'adult' => $request->adult, // $req->amount,
                'total' => $request->total,

            ]);
            // if($post){
            //     var_dump($post);
            // }
            $text = substr($request->email, 0, 6);
            $pass = Hash::make($text);
            DB::table('guests')->upsert([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $pass,
                'nationality' => $request->nationality,
                'mobile' => $request->mobile,
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
            ], 'email' );

            \Mail::send('pages.email-booking', array(
                'name' => $request->name,
                'email' => $request->email,
                'subject' => 'Confirm',
                'no_reservasi' => $request->external_id,
                'code_service' => $request->kode_product,
                'tgl_reservasi' => $request->tgl_reservasi,
                'nationality' => $request->nationality,
                'cek_in' => $request->cek_in,
                'cek_out' => $request->cek_out,
                'adult' => $request->adult,
                'type_bayar' => $request->type_bayar.'-Midtrans',
                'total_bayar' => $request->total,
                'total' => $request->amount,
                'detail' => $detail
            ), function($message) use ($request){
                // $message->from($request->email);
                $message->to('winmaxcomp@gmail.com', 'No-replay')->subject('Booking Confirm');
                $message->to($request->email, $request->name)->subject('Booking Confirm');
            });


            // $donation = \App\Models\Donation::create([
            //     'code'   => 'DONATION-' . mt_rand(100000, 999999),
            //     'name'   => $request->name,
            //     'email'  => $request->email,
            //     'amount' => $request->amount,
            //     // 'note'   => $request->note,
            // ]);

            $payload = [
                'transaction_details' => [
                    'order_id'     => $request->external_id,
                    'gross_amount' => $request->amount,
                ],
                'customer_details' => [
                    'first_name' => $request->name,
                    'email'      => $request->email,
                ],
                'item_details' => [  
                    [
                        'id'            => $request->kode_product,
                        'price'         => $request->amount,
                        'quantity'      => 1,
                        'name'          => ' - '.$count.' Night '.$request->nama_kamar,
                        'brand'         => 'Night',
                        'category'      => config('app.name'),
                        // 'merchant_name' => config('app.name'),
                    ],
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($payload);
            $post->snap_token = $snapToken;
            $post->save();

            $this->response['snap_token'] = $snapToken;
        });

        $this->broadcastPropertyCalendarChanged('reservation-created');

        return response()->json([
            'status'     => 'success',
            'snap_token' => $this->response,
        ]);
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
