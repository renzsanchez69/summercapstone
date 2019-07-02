<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = database_path('seeds/csv/categories.csv');
        $excel = App::make('excel');

        $data = $excel->load($csv, function($reader) {
            $results = $reader->all();
            foreach($results as $row) {
                DB::table('categories')->insert([
                    'name' => $row->name,
                    'desc' => $row->desc,
					'filename' => $row->filename
                ]);
            }
        });
    }
}
