<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Table;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        $tables = [
            ['table_number' => 'T01', 'name' => 'Window Table 1', 'capacity' => 2, 'location' => 'Window Area'],
            ['table_number' => 'T02', 'name' => 'Window Table 2', 'capacity' => 2, 'location' => 'Window Area'],
            ['table_number' => 'T03', 'name' => 'Main Hall 1', 'capacity' => 4, 'location' => 'Main Hall'],
            ['table_number' => 'T04', 'name' => 'Main Hall 2', 'capacity' => 4, 'location' => 'Main Hall'],
            ['table_number' => 'T05', 'name' => 'Main Hall 3', 'capacity' => 4, 'location' => 'Main Hall'],
            ['table_number' => 'T06', 'name' => 'Main Hall 4', 'capacity' => 6, 'location' => 'Main Hall'],
            ['table_number' => 'T07', 'name' => 'Family Room 1', 'capacity' => 8, 'location' => 'Family Room'],
            ['table_number' => 'T08', 'name' => 'Family Room 2', 'capacity' => 8, 'location' => 'Family Room'],
            ['table_number' => 'T09', 'name' => 'VIP Room 1', 'capacity' => 6, 'location' => 'VIP Room'],
            ['table_number' => 'T10', 'name' => 'VIP Room 2', 'capacity' => 10, 'location' => 'VIP Room'],
            ['table_number' => 'T11', 'name' => 'Outdoor 1', 'capacity' => 4, 'location' => 'Outdoor'],
            ['table_number' => 'T12', 'name' => 'Outdoor 2', 'capacity' => 4, 'location' => 'Outdoor'],
        ];

        foreach ($tables as $table) {
            $table['status'] = 'available';
            Table::firstOrCreate(['table_number' => $table['table_number']], $table);
        }
    }
}
