<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        Status::create([
            'name' => 'To Do',
            'order' => 1,
        ]);

        Status::create([
            'name' => 'In Progress',
            'order' => 2,
        ]);

        Status::create([
            'name' => 'Review',
            'order' => 3,
        ]);

        Status::create([
            'name' => 'Done',
            'order' => 4,
        ]);
    }
}