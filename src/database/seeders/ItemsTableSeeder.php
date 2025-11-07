<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userId = 1;
        $userId1 = $userId++;
        $userId2 = $userId++;
        $userId3 = $userId++;

        User::firstOrCreate([
            'id' => $userId1,
        ], [
            'name' => 'テストユーザー1',
            'email' => 'test1@example.com',
            'password' => bcrypt('password'),
            'post_code' => '123-4567',
            'address' => '東京',
        ]);


        User::firstOrCreate([
            'id' => $userId2,
        ], [
            'name' => 'テストユーザー2',
            'email' => 'test2@example.com',
            'password' => bcrypt('password'),
            'post_code' => '123-4567',
            'address' => '東京',
        ]);


        User::firstOrCreate([
            'id' => $userId3,
        ], [
            'name' => 'テストユーザー3',
            'email' => 'test3@example.com',
            'password' => bcrypt('password'),
        ]);



        $conditionMap = [
            '良好' => 1,
            '目立った傷や汚れなし' => 2,
            'やや傷や汚れあり' => 3,
            '状態が悪い' => 4,
        ];

        $items = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolax',
                'detail' => 'スタイリッシュなデザインのメンズ腕時計',
                'img' => 'test/1',
                'condition_id' => $conditionMap['良好'],
                'user_id' => $userId1,
                'buyer_id' => null,
                'payment_method' => null,
                'post_code' => null,
                'address' => null,
                'building' => null,
                'sold' => 0,
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'detail' => '高速で信頼性の高いハードディスク',
                'img' => 'test/2',
                'condition_id' => $conditionMap['目立った傷や汚れなし'],
                'user_id' => $userId1,
                'buyer_id' => null,
                'payment_method' => null,
                'post_code' => null,
                'address' => null,
                'building' => null,
                'sold' => 0,
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand' => 'なし',
                'detail' => '新鮮な玉ねぎ3束のセット',
                'img' => 'test/3',
                'condition_id' => $conditionMap['やや傷や汚れあり'],
                'user_id' => $userId1,
                'buyer_id' => null,
                'payment_method' => null,
                'post_code' => null,
                'address' => null,
                'building' => null,
                'sold' => 0,
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'brand' => null,
                'detail' => 'クラシックなデザインの革靴',
                'img' => 'test/4',
                'condition_id' => $conditionMap['状態が悪い'],
                'user_id' => $userId1,
                'buyer_id' => null,
                'payment_method' => null,
                'post_code' => null,
                'address' => null,
                'building' => null,
                'sold' => 0,
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'brand' => null,
                'detail' => '高性能なノートパソコン',
                'img' => 'test/5',
                'condition_id' => $conditionMap['良好'],
                'user_id' => $userId1,
                'buyer_id' => null,
                'payment_method' => null,
                'post_code' => null,
                'address' => null,
                'building' => null,
                'sold' => 0,
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'brand' => 'なし',
                'detail' => '高音質のレコーディング用マイク',
                'img' => 'test/6',
                'condition_id' => $conditionMap['目立った傷や汚れなし'],
                'user_id' => $userId2,
                'buyer_id' => null,
                'payment_method' => null,
                'post_code' => null,
                'address' => null,
                'building' => null,
                'sold' => 0,
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => null,
                'detail' => 'おしゃれなショルダーバッグ',
                'img' => 'test/7',
                'condition_id' => $conditionMap['やや傷や汚れあり'],
                'user_id' => $userId2,
                'buyer_id' => null,
                'payment_method' => null,
                'post_code' => null,
                'address' => null,
                'building' => null,
                'sold' => 0,
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'brand' => 'なし',
                'detail' => '使いやすいタンブラー',
                'img' => 'test/8',
                'condition_id' => $conditionMap['状態が悪い'],
                'user_id' => $userId2,
                'buyer_id' => null,
                'payment_method' => null,
                'post_code' => null,
                'address' => null,
                'building' => null,
                'sold' => 0,
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbacks',
                'detail' => '手動のコーヒーミル',
                'img' => 'test/9',
                'condition_id' => $conditionMap['良好'],
                'user_id' => $userId2,
                'buyer_id' => null,
                'payment_method' => null,
                'post_code' => null,
                'address' => null,
                'building' => null,
                'sold' => 0,
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'brand' => null,
                'detail' => '便利なメイクアップセット',
                'img' => 'test/10',
                'condition_id' => $conditionMap['目立った傷や汚れなし'],
                'user_id' => $userId2,
                'buyer_id' => null,
                'payment_method' => null,
                'post_code' => null,
                'address' => null,
                'building' => null,
                'sold' => 0,
            ],
        ];

        DB::table('items')->insert($items);
    }
}
