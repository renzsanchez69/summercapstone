<?php

use Illuminate\Database\Seeder;
use App\Products as Products;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = database_path('seeds/csv/products.csv');	        // $csv = database_path('seeds/csv/products.csv');
        $excel = App::make('excel');	        // $excel = App::make('excel');
         $data = $excel->load($csv, function($reader) {	        // $data = $excel->load($csv, function($reader) {
            $results = $reader->all();	        //     $results = $reader->all();
            foreach($results as $row) {	        //     foreach($results as $row) {
                DB::table('products')->insert([	        //         DB::table('products')->insert([
                    'name' => $row->name,	        //             'name' => $row->name,
                    'price' => $row->price,	        //             'price' => $row->price,
                    'desc' => $row->desc,	        //             'desc' => $row->desc,
                    'status' => $row->status,	        //             'status' => $row->status,
                    'qty' => $row->qty,	        //             'qty' => $row->qty,
                    'total' => $row->total,	        //             'total' => $row->total,
                    'category_id' => $row->category_id,	        //             'category_id' => $row->category_id,
                    'sellers_id' => $row->sellers_id,	        //             'seller_id' => $row->seller_id,
                    'location' => $row->location	        //             'location' => $row->location
                ]);	        //         ]);
            }	        //     }
        });
    }
}
