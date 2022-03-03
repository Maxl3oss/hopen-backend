<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ลบข้อมูลเก่าออกไปก่อน
        DB::table('users')->delete();

        $data = [
            'fullname' => 'Moomin Brown',
            'username' => 'Moomin00',
            'email' => 'Moomin@gmail.com',
            'password' => Hash::make('123456'),
            'tel' => '0802202220',
            'avatar' => 'https://via.placeholder.com/400x400.png/005429?text=udses',
            'role' => '1',
            'remember_token' => 'XBWyeaiest',
        ];
        User::create($data);

        // ทำการเรียกตัว UserFactory ที่จะทำการ Faker ข้อมูลให้
        User::factory(50)->create();
    }
}
