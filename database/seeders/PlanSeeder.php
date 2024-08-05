<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder {

    public function run(): void {
        DB::table('plan')->insert([
            'name' => 'Gratuito',
            'description' => 'Aproveite os benefícios',
            'value' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
