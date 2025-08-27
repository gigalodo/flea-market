<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userId = 1; // 出品者のユーザーID

        // 状態名 → condition_id のマッピング
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
                'img' => 'images/Armani_Mens_Clock.jpg',
                'condition_id' => $conditionMap['良好'],
                'user_id' => $userId,
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
                'img' => 'images/HDD_Hard_Disk.jpg',
                'condition_id' => $conditionMap['目立った傷や汚れなし'],
                'user_id' => $userId,
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
                'img' => 'images/Onion.jpg',
                'condition_id' => $conditionMap['やや傷や汚れあり'],
                'user_id' => $userId,
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
                'img' => 'images/Leather_Shoes_Product.jpg',
                'condition_id' => $conditionMap['状態が悪い'],
                'user_id' => $userId,
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
                'img' => 'images/Living_Room_Laptop.jpg',
                'condition_id' => $conditionMap['良好'],
                'user_id' => $userId,
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
                'img' => 'images/Music_Mic_4632231.jpg',
                'condition_id' => $conditionMap['目立った傷や汚れなし'],
                'user_id' => $userId,
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
                'img' => 'images/Purse_fashion_pocket.jpg',
                'condition_id' => $conditionMap['やや傷や汚れあり'],
                'user_id' => $userId,
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
                'img' => 'images/Tumbler_souvenir.jpg',
                'condition_id' => $conditionMap['状態が悪い'],
                'user_id' => $userId,
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
                'img' => 'images/Waitress_with_Coffee_Grinder.jpg',
                'condition_id' => $conditionMap['良好'],
                'user_id' => $userId,
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
                'img' => 'images/Makeup_set.jpg',
                'condition_id' => $conditionMap['目立った傷や汚れなし'],
                'user_id' => $userId,
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
