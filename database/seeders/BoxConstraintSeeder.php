<?php

namespace Database\Seeders;

use App\Models\BoxConstraint;
use Illuminate\Database\Seeder;

class BoxConstraintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BoxConstraint::insert([
            [
                'name' => 'BOXA',
                'length' => 20,
                'width' => 15,
                'height' => 10,
                'weight_limit' => 5,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'BOXB',
                'length' => 30,
                'width' => 25,
                'height' => 20,
                'weight_limit' => 10,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'BOXC',
                'length' => 60,
                'width' => 55,
                'height' => 50,
                'weight_limit' => 50,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'BOXD',
                'length' => 50,
                'width' => 45,
                'height' => 40,
                'weight_limit' => 30,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'BOXE',
                'length' => 40,
                'width' => 35,
                'height' => 30,
                'weight_limit' => 20,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
