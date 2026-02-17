<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WeekModel;

class WeekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $days = [
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
        ];

        foreach ($days as $day) {
            WeekModel::create([    // <-- Use the model here
                'name' => $day
            ]);
        }
    }
}
