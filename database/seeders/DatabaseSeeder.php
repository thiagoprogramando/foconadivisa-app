<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

    public function run(): void {
        $this->call([
            PlanSeeder::class,
            SubjectSeeder::class,
            QuestionSeeder::class
        ]);
    }
}
