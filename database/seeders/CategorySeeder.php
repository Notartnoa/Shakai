<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Illustration',
                'slug' => 'Illustration',
                'icon' => 'images/ic_ebook.svg',
                'description' => 'Creative visual art',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Icon',
                'slug' => 'Icon',
                'icon' => 'images/ic_course.svg',
                'description' => 'Minimal graphic set',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'UI Kit',
                'slug' => 'UI Kit',
                'icon' => 'images/ic_template.svg',
                'description' => 'Interface essentials',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Source Code',
                'slug' => 'source-code',
                'icon' => 'images/ic_font.svg',
                'description' => 'Reusable project code',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
