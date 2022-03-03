<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Image;

class AuthController extends Controller
{
    public function index()
    {
        // Read all products
        // return User::all();
        // อ่านข้อมูลแบบแบ่งหน้า
        return User::orderBy('id', 'desc')->paginate(25);
        // return Product::with('users', 'users')->orderBy('id', 'desc')->paginate(25);
    }
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id);
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
            return User::destroy($id);
        } else {
            return [
                'status' => 'Permission denied to create',
            ];
        }

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
        // เช็คสิทธิ์ (role) ว่าเป็น admin (1)
        $user = auth()->user();

        if ($user->tokenCan("1")) {

            $request->validate([
                'fullname' => 'required|string',
                'username' => 'required|string',
                'tel' => 'required',
                'role' => 'required|integer',
            ]);

            $data_users = array(
                'fullname' => $request->input('fullname'),
                'username' => $request->input('username'),
                'password' => bcrypt($request->input('password')),
                // 'email_verified_at' => $request->input('email_verified_at'),
                'tel' => $request->input('tel'),
                'role' => $request->input('role'),
            );

            // รับภาพเข้ามา
            $image = $request->file('file');

            if (!empty($image)) {

                $file_name = "user_" . time() . "." . $image->getClientOriginalExtension();

                $imgwidth = 400;
                $imgHeight = 400;
                $folderupload = public_path('/images/users/thumbnail');
                $path = $folderupload . '/' . $file_name;

                // uploade to folder thumbnail
                $img = Image::make($image->getRealPath());
                $img->orientate()->fit($imgwidth, $imgHeight, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($path);

                // uploade to folder original
                $destinationPath = public_path('/images/users/original');
                $image->move($destinationPath, $file_name);

                $data_users['avatar'] = url('/') . '/images/users/thumbnail/' . $file_name;

            }

            $user = User::find($id);
            $user->update($data_users);

            return $user;

        } else {
            return [
                'status' => 'Permission denied to create',
            ];
        }
    }
    // Register
    public function register(Request $request)
    {
        // Random string 10
        $original_string = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        $original_string = implode("", $original_string);
        $RandomStr = substr(str_shuffle($original_string), 0, 10);
        // Validate field
        $fields = $request->validate([
            'fullname' => 'required|string',
            'username' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'tel' => 'required',
            'role' => 'required|integer',
        ]);

        // Create user
        $user = User::create([
            'fullname' => $fields['fullname'],
            'username' => $fields['username'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'tel' => $fields['tel'],
            'avatar' => "https://cdn-icons-png.flaticon.com/512/219/219986.png",
            'remember_token' => $RandomStr,
            'role' => $fields['role'],
        ]);

        // Create token
        $token = $user->createToken($request->userAgent(), ["$user->role"])->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 201);

    }

    public function login(Request $request)
    {

        // Validate field
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();

        // Check password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Invalid login!',
            ], 401);
        } else if ($user->email_verified_at == null) {
            return response([
                'check' => 'this email is not allowed.',
            ], 400);
        } else {

            // ลบ token เก่าออกแล้วค่อยสร้างใหม่
            $user->tokens()->delete();

            // Create token
            $token = $user->createToken($request->userAgent(), ["$user->role"])->plainTextToken;

            // check role
            $response = [
                'user' => $user,
                'token' => $token,
            ];

            return response($response, 201);
        }

    }

    // Logout
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'Logged out',
        ];
    }

    // verifide
    public function verified(Request $request, $id)
    {
        // เช็คสิทธิ์ (role) ว่าเป็น admin (1)
        $user = auth()->user();

        if ($user->tokenCan("1")) {
            $data_verified = array(
                'email_verified_at' => $request->input('email_verified_at'),
            );
            $user = User::find($id);
            $user->update($data_verified);
            return $user;

        } else {
            return [
                'status' => 'Permission denied to verified',
            ];
        }
    }

    // user update profile
    public function updateProfile(Request $request, $id)
    {

        $request->validate([
            'fullname' => 'required|string',
            'username' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string',
            'tel' => 'required',
        ]);
        // Check email
        $check = User::where('email', $request->input('email'))->first();
        // Check password
        if (!$check || !Hash::check($request->input('password'), $check->password)) {
            return response([
                'message' => 'Invalid password!',
            ], 401);
        } else {
            $data_users = array(
                'fullname' => $request->input('fullname'),
                'username' => $request->input('username'),
                'tel' => $request->input('tel'),
            );

            // รับภาพเข้ามา
            $image = $request->file('file');

            if (!empty($image)) {

                $file_name = "user_" . time() . "." . $image->getClientOriginalExtension();

                $imgwidth = 400;
                $imgHeight = 400;
                $folderupload = public_path('/images/users/thumbnail');
                $path = $folderupload . '/' . $file_name;

                // uploade to folder thumbnail
                $img = Image::make($image->getRealPath());
                $img->orientate()->fit($imgwidth, $imgHeight, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($path);

                // uploade to folder original
                $destinationPath = public_path('/images/users/original');
                $image->move($destinationPath, $file_name);

                $data_users['avatar'] = url('/') . '/images/users/thumbnail/' . $file_name;

            }

            $user = User::find($id);
            $user->update($data_users);

            return $user;
        }

    }

}
