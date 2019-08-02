<?php

use Illuminate\Database\Seeder;

class ImageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = database_path('seeds/csv/images.csv');	        // $csv = database_path('seeds/csv/products.csv');
        $excel = App::make('excel');	        // $excel = App::make('excel');
         $data = $excel->load($csv, function($reader) {	        // $data = $excel->load($csv, function($reader) {
            $results = $reader->all();	        //     $results = $reader->all();
            foreach($results as $row) {	        //     foreach($results as $row) {
                DB::table('images')->insert([	        //         DB::table('products')->insert([
                    'original_filename' => $row->original_filename,	        //             'name' => $row->name,
                    'filename' => $row->filename,	        //             'price' => $row->price,
                    'mime' => $row->mime,	
                    'product_id' => $row->product_id,        //             'desc' => $row->desc,
                ]);	        
            }	        
        });
    }
}
