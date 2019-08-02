<?php

use Illuminate\Database\Seeder;

class SellersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = database_path('seeds/csv/sellers.csv');
        $excel = App::make('excel');

        $data = $excel->load($csv, function($reader) {
            $results = $reader->all();
            foreach($results as $row) {
                DB::table('sellers')->insert([
                    'location' => $row->location,
                    'products_sold' => $row->products_sold,
                    'products_posted' => $row->products_posted,
                    'products_count' => $row->products_count,
                    'stars' => $row->stars,
                    'status' => $row->status,
                    'user_id' => $row->user_id
                ]);
            }
        });
    }
}
