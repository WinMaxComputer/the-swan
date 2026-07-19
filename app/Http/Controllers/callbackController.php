<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class callbackController extends Controller
{
    //
    public function suksesPayment(Request $request){
        $detail = $request->all();



        // Ini akan menjadi print_rToken Verifikasi Callback Anda yang dapat Anda peroleh dari dasbor.
        // Pastikan untuk menjaga kerahasiaan token ini dan tidak mengungkapkannya kepada siapa pun.
        // Token ini akan digunakan untuk melakukan verfikasi pesan callback bahwa pengirim callback tersebut adalah Xendit
        // $xenditXCallbackToken = 'ZYiHTtSD3D8Yhjaul7b8BHN7mNTUeJU182w00Ce5hXp9wvCK';

        // Bagian ini untuk mend$_id = $arrRequestInput['id'];
        // $_externalId = $arrRequestInput['external_id'];
        // $_userId = $arrRequestInput['user_id'];
        // $_status = $arrRequestInput['status'];
        // $_paidAmount = $arrRequestInput['paid_amount'];
        // $_paidAt = $arrRequestInput['paid_at'];
        // $_paymentChannel print_r= $arrRequestInput['payment_channel'];
        // $_paymentDestinatapatkan Token callback dari permintaan header, 
        // yang kemudian akan dibandingkan dengan token verifikasi callback Xendit
        // $reqHeaders = getallheaders();
        // $xIncomingCallbackTokenHeader = isset($reqHeaders['x-callback-token']) ? $reqHeaders['x-callback-token'] : "";

        // Untuk memastikan print_rpermintaan datang dari Xendit
        // Anda harus membandingkan token yang masuk sama dengan token verifikasi callback Anda
        // Ini untuk memastikan permintaan datang dari Xendit dan bukan dari pihak ketiga lainnya.
        // if($xIncomingCallprint_rbackTokenHeader === $xenditXCallbackToken){
        // Permintaan masuk diverifikasi berasal dari Xendit
        

        // Baris ini untuk mendapatkan semua input pesan dalam format JSON teks mentah
        // $rawRequestInput = file_get_untukcontents("php://input");
        // Baris ini melakukan formaprint_rt input mentah menjadi array asosiatif
        // $arrRequestInput = json_decode($rawRequestInput, true);
        // var_dump ($detail) ;
        // echo "tess" ;
        
        // $_id = $arrRequestInput['id'];
        $_externalId = $detail['external_id'];
        // $_userId = $arrRequestInput['user_id'];
        // $_status = $arrRequestInput['status'];
        // $_paidAmount = $arrRequestInput['paid_amount'];
        // $_paidAt = $arrRequestInput['paid_at'];
        // $_paymentChann$_id = $arrRequestInput['id'];
        // $_externalId = $arrRequestInput['external_id'];
        // $_userId = $arrRequestInput['user_id'];
        // $_status = $arrRequestInput['status'];
        // $_paidAmount = $arrRequestInput['paid_amount'];
        // $_paidAt = $arrRequestInput['paid_at'];
        // $_paymentChannel = $arrRequestInput['payment_channel'];
        // $_paymentDestinatel = $arrRequestInput['payment_channel'];
        // $_paymentDesti$_id = $arrprint_rRequestInput['id'];
        // $_externalId = $arrRequestInput['external_id'];
        // $_userId = $arrRequestInput['user_id'];
        // $_status = $arrRequestInput['status'];
        // $_paidAmount = $arrRequestInput['paid_amount'];
        // $_paidAt = $arrRequestInput['paid_at'];
        // $_paymentChannel = $arrRequestInput['payment_channel'];
        // $_paymentDestinatnation = $arrRequestInput['payment_destination'];

        // Kamu bisa menggunakan array objek diatas sebagai informasi callback yang dapat digunaka untuk melakukan pengecekan atau aktivas tertentu di aplikasi atau sistem kamu.

        // }else{
        // Permintaan bukan dari Xendit, tolak dan buang pesan dengan HTTP status 403
        // http_response_code(403);
        // echo "tes yg ini" ;
        // print_r($arrRequestInput);
        // }
        DB::table('reservations')->where('no_reservasi', $_externalId)->update([
            'status' => 'PAID',
        ]);

        $this->broadcastPropertyCalendarChanged('xendit-payment-updated');

        // return response()->json([
        //     'success' => trprint_rue,
        //     'message' => 'Post Berhasil di insert!',
        //     // 'data' => $detail
        // ], 200);
    }

    public function midtrans(Request $request){
        $detail = $request->all();


        
        $transaction = $detail['transaction_status'];
        $order_id = $detail['order_id'];
        $type = $detail['payment_type'];
        // Kamu bisa menggunakan array objek diatas sebagai informasi callback yang dapat digunaka untuk melakukan pengecekan atau aktivas tertentu di aplikasi atau sistem kamu.


        DB::table('reservations')->where('no_reservasi', $order_id)->update([
            'status' => $transaction,
        ]);

        $this->broadcastPropertyCalendarChanged('midtrans-payment-updated');

        if ($transaction == 'capture') {
        if ($type == 'credit_card'){
            if($fraud == 'accept'){
            // TODO set payment status in merchant's database to 'Success'
            // echo "Transaction order_id: " . $order_id ." successfully captured using " . $type;
            session()->put('success','Transaction order_id: ' . $order_id .' successfully captured using ' . $type);
            }
            }
        }
        else if ($transaction == 'settlement'){
        // TODO set payment status in merchant's database to 'Settlement'
        // echo "Transaction order_id: " . $order_id ." successfully transfered using " . $type;
        session()->put("success","Transaction order_id: " . $order_id ." successfully transfered using " . $type);
        }
        else if($transaction == 'pending'){
        // TODO set payment status in merchant's database to 'Pending'
        // echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
        // session()->put("warning","Waiting customer to finish transaction order_id: " . $order_id . " using " . $type);
        return view('pages.notification')->with('message', 'pending');
        }
        else if ($transaction == 'deny') {
        // TODO set payment status in merchant's database to 'Denied'
        echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
        session()->put("error","Waiting customer to finish transaction order_id: " . $order_id . " using " . $type);
        }
        else if ($transaction == 'expire') {
        // TODO set payment status in merchant's database to 'expire'
        // echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";
        session()->put("error","Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.");
        }
        else if ($transaction == 'cancel') {
        // TODO set payment status in merchant's database to 'Denied'
        // echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
        session()->put("error","Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.");
        }


        // return response()->json([
        //     'success' => trprint_rue,
        //     'message' => 'Post Berhasil di insert!',
        //     // 'data' => $detail
        // ], 200);
    }

    public function createPayment(Request $request){

        $detail = $request->all();

        return view(['pages.booking-detail.php', $detail]);
    
    }

    public function paypalComplete(){

        $detail = $request->all();

        $transaction = $detail['event_type'];
        $order_id = $detail['id'];
        $summary = $detail['summary'];
        // Kamu bisa menggunakan array objek diatas sebagai informasi callback yang dapat digunaka untuk melakukan pengecekan atau aktivas tertentu di aplikasi atau sistem kamu.


        DB::table('reservations')->where('no_reservasi', $order_id)->update([
            'status' => $transaction,
        ]);

        $this->broadcastPropertyCalendarChanged('paypal-payment-updated');

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
