<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Ris;
use App\Sample;
use Validator;
use Redirect;
use App\Client;
use App\Parameter;  
use App\Employee;
use App\Transaction;
use App\Sample_Tests;
use Illuminate\Support\Facades\Auth;
use DateTime;
use App\Notifications\NewSampleAdded;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Notifications\ReadyForPickUp;
use App\Notifications\SampleDueDate;
use App\Notifications\InformClient;


class SecretaryController extends Controller
{
    //
    public function index()
    {
        $user = Employee::where('employeeId', Auth::user()->employeeId)->with('unreadNotifications')->first();

        return view('Secretary-file.secretary', ['user' => $user]);
    }
    public function sampleView($clientRis){
        $parameters = Parameter::all();

        return view('Secretary-file.sample-secretary',['clientRis' => $clientRis, 'parameters' => $parameters]);
    }
    protected function read($id)
    {
        $user =  Employee::where('employeeId', Auth::user()->employeeId)->with('unreadNotifications')->first();

        foreach ($user->notifications as $notification) {
            if($notification->id == $id) {
                $notification->markAsRead();
                break;
            }
        }
        
        return $this->index();
    }
    protected function readAll()
    {
        $user = Employee::where('employeeId', Auth::user()->employeeId)->with('unreadNotifications')->first();

        foreach ($user->notifications as $notification) {
            $notification->markAsRead();
        }

        return $this->index();
    }
    public function addSample(){
        $parameter = Parameter::all();
        $clients = Client::orderBy('clientId', 'DESC')->get();
        return view ('Secretary-file.add-sample', ['parameters' => $parameter, 'clients' => $clients]);
    }
    public function stat()
    {
        return view('Secretary-file.view-secretary');
    }
    public function create()
    {
        return view ('Secretary-file.create-secretary');
    }
    public function samples(){
        $samples=Sample::all();
        return view('dynamic_pdf',['samples'=>$samples]);
    }

    public function postAddSample(Request $request){
        // Validation
        
        $validator = Validator::make($request->all(), [
            'clientId' => 'required',
            'clientsCode' => 'nullable|string|max:255',
            'sampleType' => 'required|string|max:255',
            'sampleCollection' => 'required|date|before:now',
            'samplePreservation' => 'nullable|string|max:50',
            'parameter' => 'required',
            'purposeOfAnalysis' => 'nullable|string|max:50',
            'sampleSource' => 'required|string|max:20',
            'dueDate' => 'required|date|after:now',
        ]);
        // Check validation
        if ($validator->fails()) {
            return redirect('/secretary/add-sample')
                        ->withErrors($validator)
                        ->withInput();
        }

        // Find client
        $client = Client::where('risNumber', $request->clientId)->value('clientId');

        // Insert new sample
        $sample = new Sample;
        $sample->risNumber = $client;
        $sample->clientsCode = trim($request->clientsCode);
        $sample->sampleType =  trim($request->sampleType);
        $sample->sampleCollection = $request->sampleCollection;
        $sample->samplePreservation = trim($request->samplePreservation);
        $sample->purposeOfAnalysis = trim($request->purposeOfAnalysis);
        $sample->sampleSource = $request->sampleSource;
        $sample->dueDate = $request->dueDate;
        $sample->managedBy = Auth::user()->employeeName;
        $sample->managedDate = new DateTime();
        $sample->save();
        // Add lab code 
        $sample->laboratoryCode = date("Y", strtotime($sample->created_at)) . '-' . date("m", strtotime($sample->created_at)) . '-' . $sample->sampleId;
        // Add sample tests
        foreach ($request->parameter as $parameter => $analysis) {
            $sampletests = new Sample_Tests;
            $sampletests->sampleCode = $sample->sampleId;
            $sampletests->parameters = Parameter::where('analysis', $analysis)->value('parameterId');
            $sampletests->status = "Not Started";
            $sampletests->managedBy = Auth::user()->employeeName;
            $sampletests->managedDate = new DateTime();
            $sampletests->save();
        }
        // Return to samples page
        if($sample->save()){

            $users = Employee::all();
        
            foreach ($users as $user) {
                if ($user['userType'] == 'administrator' || $user['userType'] == 'secretary') {

                    $user->notify((new NewSampleAdded($sample)));
                }
            }

            $clients=Client::all();
            $parameters = Parameter::all();
            
            Session::flash('flash_sample_added', 'Sample inserted successfully!');
            return view('Secretary-file.add-sample',['clients'=> $clients, 'parameters' => $parameters]);
        }
        else {
            abort(500, 'Error! Sample not added.');
        }
        
    }

    public function form()
    {
        $clients = DB::table('clients')->orderBy('clientId','DESC')->paginate(10);
        return view('Secretary-file.secretary-form',['clients'=>$clients]);
    }


    
    protected function status(){
        
        $cli = Client::with('samples.parameters')->get();
        $isComplete = 'false';

        foreach($cli as $cl){
            foreach($cl->samples as $sample){
                foreach($sample->parameters as $parameter){
                    if($parameter->pivot->status == "Not Started" || $parameter->pivot->status == "In Progress"){
                        $isComplete = 'false';
                        break;
                    }
                    else{
                        $isComplete = 'true';
                    }
                }
            }
            if($isComplete == 'true'){
                $ready = Client::findOrFail($cl->clientId);
                $ready->readyForPickUp = 'yes';
                $ready->save();
            }else{
                $ready = Client::findOrFail($cl->clientId);
                $ready->readyForPickUp = 'no';
                $ready->save();
            }
        }
       
        $client = Client::where('readyForPickUp','yes')->paginate(15);

        return view('Secretary-file.manage_client_secretary', ['status'=>$client]);
    }

    protected function paid($clientId){

        $client = Client::findOrFail($clientId);
        if($client->paid == "no"){
            $client->paid = "yes";
            if($client->save()){
                $client = Client::where('readyForPickUp','yes')->paginate(15);

                return view('Secretary-file.manage_client_secretary', ['status'=>$client]);
            }
        }
        else{
            $client->paid = "no";
            if($client->save()){
                $client = Client::where('readyForPickUp','yes')->where('readyForPickUp','Yes')->paginate(15);

                return view('Secretary-file.manage_client_secretary', ['status'=>$client]);
            }
        }

    }

    protected function send($clientId){

        $client = Client::findOrFail($clientId);       
        $client->sendText="Yes";

        if($client->save()){
            $client->notify(new ReadyForPickUp($client));
        }
        return redirect()->action('SecretaryController@status');

    }

    protected function informClient($clientId, Request $request){
        $client = Client::findorFail($clientId);

        if($request->message == NULL){
            Session::flash('no_message', 'No message inputted.');
            return redirect()->action('SecretaryController@form');
        }
        else{
            $client->notify(new InformClient($request->message));
            Session::flash('flash_client_message', 'Client messaged succesfully.');
            return redirect()->action('SecretaryController@form');
        }
        

    }

    protected function addClient(Request $request)
    {
        // VALIDATION
        $validator = Validator::make($request->all(), [
            'nameOfPerson' => 'required|string|max:255|min:4',
            'nameOfEntity' => 'nullable|string|max:255',
            'address' => 'required|string|max:50',
            'contactNumber' => 'required|digits:10',
            'faxNumber' => 'nullable|string|numeric',
            'emailAddress' => 'nullable|string|max:50|email',
            'discount'=> 'nullable|numeric|max:100|min:0',
            'dueDate' => 'required|date|after:now'
        ]);
        
        // VALIDATION CHECKS
        if ($validator->fails()) {
            return redirect('secretary/create')
                        ->withErrors($validator)
                        ->withInput();
        }

        //ELOQUENT INSERT
        $client = new Client;
        $client->nameOfPerson = trim($request->nameOfPerson);
        $client->nameOfEntity = trim($request->nameOfEntity);
        $client->address =  trim($request->address);
        $client->contactNumber = ("63" . trim($request->contactNumber));
        $client->faxNumber = trim($request->faxNumber);
        $client->emailAddress = trim($request->emailAddress);
        if($request->discount == NULL){
            $client->discount = 0;
        }
        else {
            $client->discount = trim($request->discount);
        }
        if($request->deposit == NULL){
            $client->deposit = 0;
        }
        else {
            $client->deposit = trim($request->deposit);
        }
        $client->reclaimSample = trim($request->reclaimSample);
        $client->followUp= trim($request->dueDate);
        $client->testResult = trim($request->testResult);
        $client->remarks =  trim($request->remarks);    
        $client->managedBy = Auth::user()->employeeName;
        $client->managedDate = new DateTime();
       
        $client->save();
       
        $client->risNumber = date("Y", strtotime($client->created_at)) . '-' . ($client->clientId + 554);
        $client->save();
        

        // INSERT TRANSACTION
        $transaction = new Transaction;
        $transaction->client = $client->clientId;
        $transaction->approvedBy = Auth::user()->employeeId;
        $transaction->managedBy = Auth::user()->employeeName;
        $transaction->managedDate = new DateTime();
        //SAVE TO DB && CHECK
        if($transaction->save()){
            $parameter = Parameter::orderBy('analysis')->get();
            $clientRis = $client->risNumber;
            Session::flash('flash_client_added', 'Client added successfully. Please add the samples of the new client.');
            return redirect()->action('SecretaryController@sampleView', ['clientId' =>$client->clientId]);
        //     return view('Secretary-file.sample-secretary', ['risNumber' => $client->risNumber, 'parameters' => $parameter]);
        }
        else{
            App::abort(500, 'Error!');
        }
       
        
    }

    protected function  createSample(Request $request){


        // $sample = new Sample;
        // $sample->risNumber = trim($request->clientId);

        {
            // VALIDATION
            $validator = Validator::make($request->all(), [
                'clientRis' => 'required',
                'clientsCode' => 'nullable|string|max:255',
                'sampleType' => 'required|string|max:255',
                'sampleCollection' => 'required|date|before:now',
                'samplePreservation' => 'nullable|string|max:50',
                'parameter' => 'required',
                'purposeOfAnalysis' => 'nullable|string|max:50',
                'sampleSource' => 'required|string|max:20',
                'dueDate' => 'required|date|after:now',
            ]);
            //VALIDATION CHECKS
            if ($validator->fails()) {     
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $client = DB::table('clients')->where('clientId', $request->clientRis)->value('clientId');
            //ELOQUENT INSERT
            $sample = new Sample;
            $sample->risNumber = $client;
            $sample->clientsCode = trim($request->clientsCode);
            $sample->sampleType =  trim($request->sampleType);
            $sample->sampleCollection = $request->sampleCollection;
            $sample->samplePreservation = trim($request->samplePreservation);
            $sample->purposeOfAnalysis = trim($request->purposeOfAnalysis);
            $sample->sampleSource = $request->sampleSource;
            $sample->dueDate = $request->dueDate;
            $sample->managedBy = Auth::user()->employeeName;
            $sample->managedDate = new DateTime();
            $sample->save();
            //INSERT LAB CODE TO SAMPLES
            $sample->laboratoryCode = date("Y", strtotime($sample->created_at)) . '-' . date("m", strtotime($sample->created_at)) . '-' . ($sample->sampleId + 1588);
            
            //INSERT SAMPLE TESTS IN LOOP
            foreach ($request->parameter as $parameter => $analysis) {
                $sampletests = new Sample_Tests;
                $sampletests->sampleCode = $sample->sampleId;
                $sampletests->parameters = DB::table('parameters')->where('analysis', $analysis)->value('parameterId');
                $sampletests->managedBy = Auth::user()->employeeName;
                $sampletests->managedDate = new DateTime();
                $sampletests->save();
            }
            //RETURN TO ADD SAMPLE PAGE TO ADD MORE SAMPLES
            if($sample->save()){
                $users = Employee::all();
        
            foreach ($users as $user) {
                if ($user['userType'] == 'administrator' || $user['userType'] == 'secretary') {

                    $user->notify((new NewSampleAdded($sample)));
                }
            }
                
                Session::forget('flash_client_added');
                Session::flash('flash_sample_added', 'Sample added successfully. You can add another sample.');
                return redirect()->action('SecretaryController@sampleView', ['clientRis'=> $request->clientRis]);
            }
            else {
                App::abort(500, 'Error!');
            }
        
        }
    }
}
