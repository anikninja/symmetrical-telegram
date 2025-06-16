<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Enums\StatusEnum;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $languages = [
            ['name' => 'English', 'code' => 'en', 'status' => StatusEnum::ACTIVE->value],
        ];

        $timestamp = Carbon::now();
        $data = array_map(function ($language) use ($timestamp) {
            return array_merge($language, [
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }, $languages);

        DB::table('languages')->insert($data);
    }
}
