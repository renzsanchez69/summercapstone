<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use Session;
use App\Sample;
use Illuminate\Support\Facades\DB;

class ProduitController extends Controller
{
    public function index($clientId)
    {
                   
        $client=Client::where('clientId', $clientId)->with('samples.parameters')->get();

        return view('produit',['client'=>$client]);
    }

    public function selectSamples(){
        $sample=Sample::orderBy('created_at', 'DESC')->get();

        return view('Secretary-file.selectSamples',['samples'=>$sample]);
    }

    public function printSamples(Request $request){
        

            $list_of_samples = $request->samples;
            $lists = [];           
            $samples = Sample::whereIn('laboratoryCode',$list_of_samples)->get();
            foreach($samples as $sample){
                $lists[] = [$sample->risNumber];
            }
            $clients = Client::wherein('clientId', $lists)->get();
            
            
            return view('Secretary-file.printSamples', ['samples' => $samples], ['clients' => $clients]);
            // echo "<br>";
        
        
        
    }

    public function search(Request $request){

        $produits = DB::table('clients')->where('risNumber', $request->search)->get();

        if(count($produits)<1){
            Session::flash('flash_not_found', 'No Client ID exists.');
            return view('Secretary-file.search-fail');
          }

        else{
            
            return view('Secretary-file.secretary-search',['clients'=>$produits]);
            
        }
    }
}
