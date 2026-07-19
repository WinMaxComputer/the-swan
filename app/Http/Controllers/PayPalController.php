<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Mail;
use Illuminate\Support\Facades\Hash;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{
    //
    public function index(){

        return \App::call('App\Http\Controllers\bookingController@home');

    }

  

    /**

     * Write code on Method

     *

     * @return response()

     */


    public function payment(Request $request){

        
        $code = $request->code ;
        // $nores = $code.date('ymdhis', time());
        $name = $request->name ;
        $email = $request->email ;
        $mobile = $request->mobile ;
        $adult = $request->adult ;
        $nationality = $request->nationality ;
        $cekin = $request->cek_in ;
        $cekout = $request->cek_out ;
        $tgl_reservasi = $request->tgl_reservasi ;
        $room_no = $request->room_no ;
        $type_bayar = $request->tipe_bayar ;
        $total_bayar = $request->total_bayar ;
        $total_dolar = $request->bayar_dolar ;
        $total = $request->total ;
        $dateRange = CarbonPeriod::create($cekin, $cekout);

        

        // // echo $code;

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.payment.success'),
                "cancel_url" => route('paypal.payment/cancel'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $total_dolar
                    ]
                ]
            ]
        ]);


        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {

                    $nores = $response['id'] ;

                    $count = count($dateRange);
                    $i=0;
                    $detail = [];
                    $status = "";
                    $no_kamar = "";
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

                        $users = DB::table("room_nomors")->select('*')
                        ->whereNOTIn('room_no', DB::table('reservation_room_detail')
                                    ->select('no_room')
                                    // ->from('reservation_room_detail')
                                    ->where('tgl', $date->format('Y-m-d'))
                                    // ->where('status', '!=', 'cekin')
                                    ->where('status', '!=', 'cekout')
                                    
                        )
                        ->where('unit_code', $request->code)
                        ->get();
                        // var_dump($users);
                        $no_kamar = $users['0']->room_no ;
                        
                        $getDetail = DB::table('rates')
                            ->where('tgl', $date->format('Y-m-d'))
                            ->where('kode_kamar', $code)
                            ->get();
                        $detail[] = [
                            'no_reservasi' => $nores,
                            "kode_unit" => $code,
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
                        ->where('kode_kamar', $code)
                        ->update([
                            'stok' => $getDetail['0']->stok - 1,
                        ]);
                        // };
                        
                        // var_dump($getDetail);
                    }

                    DB::table('reservation_room_detail')->insert($detail);
                    // var_dump($detail);
                    $post = DB::table('reservations')->insert([
                        'no_reservasi' => $nores,
                        'guest_email' => $email,
                        'code_service' => $code,
                        'tgl_reservasi' => $tgl_reservasi,
                        'guest_name' => $name,
                        'room_no' =>  $no_kamar,
                        'cek_in' => $cekin,
                        'cek_out' => $cekout,

                        'payment_type' => $type_bayar.'-Paypal',
                        'status' => 'PENDING', // $req->status,

                        'guest_paid' => $total_bayar,
                        'book_status' => 'New',
                        'adult' => $adult, // $req->amount,
                        'total' => $total,
                        // 'guest_paid' => 
                        'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                        'updated_at' => \Carbon\Carbon::now()->toDateTimeString()

                    ]);
                    $this->broadcastPropertyCalendarChanged('paypal-reservation-created');
                    // if($post){
                    //     var_dump($post);
                    // }
                    $pass = Hash::make(substr($email, 0, 6));
                    DB::table('guests')->upsert([
                        'name' => $name,
                        'email' => $email,
                        'password' => $pass,
                        'nationality' => $nationality,
                        'mobile' => $mobile,
                        'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                        'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                    ], 'email' );

                    \Mail::send('pages.email-booking', array(
                        'name' => $name,
                        'email' => $email,
                        'subject' => 'Confirm',
                        'no_reservasi' => $nores,
                        'code_service' => $code,
                        'tgl_reservasi' => $tgl_reservasi,
                        'nationality' => $nationality,
                        'cek_in' => $cekin,
                        'cek_out' => $cekout,
                        'adult' => $adult,
                        'type_bayar' => $type_bayar.'-paypal',
                        'total_bayar' => $total_bayar,
                        'total' => $total,
                        'detail' => $detail
                    ), function($message) use ($request){
                        // $message->from($request->email);
                        $message->to('winmaxcomp@gmail.com', 'No-replay')->subject('Booking Confirm');
                        $message->to($request->email, $request->name)->subject('Booking Confirm');
                    });
                    
                    return redirect()->away($links['href']);
                }
            }
            return redirect()->route('cancel.payment')->with('error', 'Something went wrong.');
        } else {
            return redirect()->route('create.payment')->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }

  

    /**

     * Write code on Method

     *

     * @return response()

     */

    public function paymentCancel(){

        return redirect()->route('paypal')->with('error', $response['message'] ?? 'You have canceled the transaction.');

    }

    private function broadcastPropertyCalendarChanged(string $reason): void
    {
        try {
            broadcast(new \App\Events\PropertyCalendarChanged($reason));
        } catch (\Throwable $exception) {
            report($exception);
        }
    }

  

    /**

     * Write code on Method

     *

     * @return response()

     */

    public function paymentSuccess(Request $request){

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

  

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            return redirect()->route('service')->with('success', 'Transaction complete.');

        } else {
            return redirect()->route('service')->with('error', $response['message'] ?? 'Something went wrong.');

        }

    }
}
