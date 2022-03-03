<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Image;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // ไม่ต้องล็อคอิน
    public function index()
    {
        // Read all products
        // return Order::all();
        // อ่านข้อมูลแบบแบ่งหน้า
        // return Product::orderBy('id','desc')->paginate(25);
        return Order::with('user_orders', 'user_orders')->orderBy('id', 'desc')->paginate(25);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate form
        $request->validate([
            'order_paytype' => 'required',
        ]);
        // random order_id
        $original_string = array_merge(range(0, 9), range('A', 'Z'));
        $original_string = implode("", $original_string);
        $RandomOrderID = substr(str_shuffle($original_string), 0, 10);

        // create Order
        $user = auth()->user();
        $orderdata['order_key'] = '#HOPEN-' . $RandomOrderID;
        $orderdata['order_qty'] = $request->input("order_qty");
        $orderdata['order_total'] = $request->input("order_total");
        $orderdata['order_status'] = 'pending';
        $orderdata['order_paytype'] = $request->input("order_paytype");
        $orderdata['user_id'] = $user->id;

        // รับไฟล์ภาพเข้ามา
        $image = $request->file('file');

        // เช็คว่าผู้ใช้มีการอัพโหลดภาพเข้ามาหรือไม่
        if (!empty($image)) {

            // อัพโหลดรูปภาพ
            // เปลี่ยนชื่อรูปที่ได้
            $file_name = "slips_" . time() . "." . $image->getClientOriginalExtension();
            // กำหนดขนาดความกว้าง และสูง ของภาพที่ต้องการย่อขนาด
            // $imgWidth = 400;
            // $imgHeight = 400;
            $folderupload = public_path('/images/slips/original');
            $path = $folderupload . "/" . $file_name;

            // อัพโหลดเข้าสู่ folder original
            $img = Image::make($image->getRealPath());
            $img->save($path);

            // กำหนด path รูปเพื่อใส่ตารางในฐานข้อมูล
            $orderdata['order_image'] = url('/') . '/images/slips/original/' . $file_name;
        }
        // create Product in order
        $order = Order::create($orderdata);
        // กำหนดตัวแปร
        $PID = $request->input("product_id");
        $PQTY = json_decode($request->input("product_qty"));
        $PP = json_decode($request->input("product_price"));

        // loop product ตามจำนวน
        foreach (json_decode($PID) as $item => $PID) {
            // check product amount ว่าพอรึป่าว
            $data = Product::select('amount')->where('id', $PID)->first();
            $check = (int) $data->amount < (int) $PQTY[$item];
            if (!$check) {
                Product::where('id', $PID)->decrement('amount', (int) $PQTY[$item]);

                // create cart
                $itemped['order_id'] = $order->id;
                $itemped['product_id'] = $PID;
                $itemped['product_qty'] = $PQTY[$item];
                $itemped['product_price'] = $PP[$item];
                Cart::create($itemped);
            } else {
                //เมื่อจำนวนของสินค้าไม่เพียงใหห้ลบ order ที่ทำการจองไว้
                Order::destroy($order->id);
                return [
                    'status' => 'จำนวนไม่พอ',
                ];
            }
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Order::find($id);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $user = auth()->user();
        $data_order = array(
            'order_status' => $request->input('order_status'),
        );
        $order = Order::find($id);
        $order->update($data_order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // เช็คสิทธิ์ (role) ว่าเป็น admin (1)
        $user = auth()->user();
        if ($user->tokenCan("1")) {
            return Order::destroy($id);
        } else {
            return [
                'status' => 'Permission denied to create',
            ];
        }

    }
    /**
     * Search for a name
     *
     * @param  string $keyword
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search($keyword)
    {
        return Order::with('user_orders', 'user_orders')
            ->where('user_id', '=', $keyword . '%')
            ->orderBy('id', 'desc')
            ->paginate(25);
    }
}
