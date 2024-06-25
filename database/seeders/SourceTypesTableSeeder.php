<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SourceType;

class SourceTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define source types data
        $sourceTypes = [
            ['name' => 'Whatsapp'],
            ['name' => 'Facebook'],
            ['name' => 'Other'],
            ['name' => 'Linkedin'],
            ['name' => 'Instagram'],

            // Add more source types as needed
        ];

        // Insert data into the database
        SourceType::insert($sourceTypes);
    }
}
