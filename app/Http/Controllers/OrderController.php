<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use PDO;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    function orders(){
        $orders = Order::all();
        return view('admin.order.order', [
            'orders'=>$orders,
        ]);
    }

    function order_status(Request $request){
        $after_explode = explode(',', $request->status);
        $order_id = $after_explode[0];
        $status = $after_explode[1];
        Order::where('order_id', $order_id)->update([
            'status'=>$status,
        ]);
        return back();
    }

    function invoice_download($order_id){
        $order_info = Order::find($order_id);
        $pdf = Pdf::loadView('invoice.download',[
            'order_id'=>$order_info->order_id,
        ]);
        return $pdf->download('invoice.pdf');
    }
}
