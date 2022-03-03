<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerControllerr extends Controller
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
        // return Customer::all();
        // อ่านข้อมูลแบบแบ่งหน้า
        // return Product::orderBy('id','desc')->paginate(25);
        return Customer::with('user_customers', 'user_customers')->orderBy('id', 'desc')->paginate(25);
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
            'fullname' => 'required',
            'tel' => 'required',
            'address' => 'required',
        ]);
        $user = auth()->user();
        // กำหนดตัวแปรรับค่าจากฟอร์ม
        $data_customer = array(
            'fullname' => $request->input('fullname'),
            'tel' => $request->input('tel'),
            'address' => $request->input('address'),
            'user_id' => $user->id,
        );
        return Customer::create($data_customer);
    }
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Customer::find($id);
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
        $user = auth()->user();
        $data_customer = array(
            'fullname' => $request->input('fullname'),
            'tel' => $request->input('tel'),
            'address' => $request->input('address'),
            'user_id' => $user->id,
        );
        $customer = Customer::find($id);
        $customer->update($data_customer);
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
            return customer::destroy($id);
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
        return Customer::with('user_customers', 'user_customers')
            ->where('user_id', '=', $keyword . '%')->get();
    }
}
