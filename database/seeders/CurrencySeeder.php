<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            ['code' => 'USD', 'name' => 'United States Dollar', 'symbol' => '$', 'country' => 'United States'],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'country' => 'European Union'],
            ['code' => 'JPY', 'name' => 'Japanese Yen', 'symbol' => '¥', 'country' => 'Japan'],
            ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£', 'country' => 'United Kingdom'],
            ['code' => 'IDR', 'name' => 'Indonesian Rupiah', 'symbol' => 'Rp', 'country' => 'Indonesia'],
            ['code' => 'CNY', 'name' => 'Chinese Yuan', 'symbol' => '¥', 'country' => 'China'],
            ['code' => 'AUD', 'name' => 'Australian Dollar', 'symbol' => '$', 'country' => 'Australia'],
        ];

        DB::table('currencies')->insert($currencies);
    }
}
