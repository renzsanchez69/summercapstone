<?php

namespace App\Http\Controllers;
use Session;
use App\Employee;
use App\Client;
use App\Sample;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Parameter;
use App\Notifications\ClientContact;
use Redirect;

class ClientController extends Controller
{
    public function RIS(Request $request)
    {
        
        $risNumber = Client::where('risNumber', '=', $request->search)->first();
        
        if($risNumber != NULL) {
            $clients = Client::where('risNumber', $request->search)->with('samples.parameters')->get();
            return view('clients.client_RIS', ['clients' => $clients]);
        }
        else {
            return view('clients.risError');
        }
    }

    public function searchParameter(Request $request)
    {
        $parameters = Parameter::where('analysis', $request->search)->paginate(10);
        $params = Parameter::all();

        if(count($parameters) == 0 ){
            return $this->parameters();
        }else{
            return view('clients.client_S&R', ['parameters' => $parameters, 'params' => $params]);
        }

    }

    public function parameters()
    {
        $parameters = Parameter::with('stations')->orderBy('analysis')->paginate(10);
        $params = Parameter::all();

        return view('clients.client_S&R', ['parameters' => $parameters, 'params' => $params]);
    }

    public function contact()
    {
        $contact = DB::table('client_contacts');
        return view('clients.contact');
    }

    public function receive(Request $request)
    {
        $users = Employee::all();
        
        foreach ($users as $user) {
            if ($user['userType'] == 'administrator' || $user['userType'] == 'secretary') {

                $user->notify((new ClientContact($request)));

                Session::flash('flash_feedback_added','Message sent successfully!');
                return Redirect::back();
            }
            else {
                return view('client.contact');
            }
        }
    }
}