<?php

namespace App\Http\Controllers;

use Exception;
use DateTime;
use DateInterval;
use Redirect;
use Validator;
use Session;
use App\Event;
use App\Employee;
use App\Client;
use App\Sample;
use App\Sample_Tests;
use App\Parameter;
use App\Station;
use App\Supplier;
use App\Item;
use App\InventoryList;
use App\Transaction;
use App\Notifications\NewSampleAdded;
use App\Jobs\ProcessNotification;
use App\Rules\OldPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    // Dashboard page
    public function dashboard()
    {
        $client = Client::all()->count();
        $employee = Employee::where('employeeId', '!=', Auth::user()->employeeId)->get()->count();

        $sample_not_started = 0; $sample_in_progress = 0; $sample_completed = 0;
        $completed = FALSE; $not_started = FALSE; $in_progress = FALSE;
        $samples = Sample::with('parameters')->get();


        foreach ($samples as $sample) {
            foreach ($sample->parameters as $parameter) {
                if($parameter->pivot->status == 'Completed' ) {
                    $completed = TRUE;
                }
                elseif($parameter->pivot->status == 'Not Started') {
                    $not_started = TRUE;
                }
                else {
                    $in_progress = TRUE;
                    break;
                }
            }
            if($in_progress == TRUE) {
                $sample_in_progress++;
            }
            elseif($completed == TRUE && $not_started == FALSE) {
                $sample_completed++;
            }
            elseif($not_started == TRUE && $completed == FALSE) {
                $sample_not_started++;
            }
            else {
                $sample_in_progress++;  
            }
            $completed = FALSE; $not_started = FALSE; $in_progress = FALSE;
        }
        
        $samples_today = Sample::whereDate('created_at', Carbon::today())->get()->count();
        $samples_yesterday = Sample::whereDate('created_at', Carbon::today()->subDays(1))->get()->count();
        $samples_2_days_ago = Sample::whereDate('created_at', Carbon::today()->subDays(2))->get()->count();
        $samples_3_days_ago = Sample::whereDate('created_at', Carbon::today()->subDays(3))->get()->count();
        $samples_4_days_ago = Sample::whereDate('created_at', Carbon::today()->subDays(4))->get()->count();

        $array_samples = array($samples_today, $samples_yesterday, $samples_2_days_ago, $samples_3_days_ago, $samples_4_days_ago);
        $array_days = array(Carbon::today()->format('l'), Carbon::today()->subDays(1)->format('l'), Carbon::today()->subDays(2)->format('l'), Carbon::today()->subDays(3)->format('l'), Carbon::today()->subDays(4)->format('l'));

        $clients_this_month = Client::whereMonth('created_at', Carbon::today()->month)->get()->count();
        $clients_last_month = Client::whereMonth('created_at', Carbon::today()->subMonth(1))->get()->count();
        $clients_last_two_months = Client::whereMonth('created_at', Carbon::today()->subMonth(2))->get()->count();
        $clients_last_three_months = Client::whereMonth('created_at', Carbon::today()->subMonth(3))->get()->count();
        $clients_last_four_months = Client::whereMonth('created_at', Carbon::today()->subMonth(4))->get()->count();

        $array_clients = array($clients_this_month, $clients_last_month, $clients_last_two_months, $clients_last_three_months, $clients_last_four_months);
        $array_months = array(Carbon::today()->format('F'), Carbon::today()->subMonth(1)->format('F'), Carbon::today()->subMonth(2)->format('F'), Carbon::today()->subMonth(3)->format('F'), Carbon::today()->subMonth(4)->format('F'));

        return view('admin.dashboard', ['employee' => $employee, 'client' => $client, 'completed' => $sample_completed, 'not_started' => $sample_not_started, 'in_progress' => $sample_in_progress, 'array_days' => $array_days, 'array_samples' => $array_samples, 'array_clients' => $array_clients, 'array_months' => $array_months]);
    }

    // Admin home page
    public function admin()
    {
        $user = Employee::where('employeeId', Auth::user()->employeeId)->with('unreadNotifications')->first();
       
        return view('admin.home', ['user' => $user]);
    }

    // Summary of clients with samples
    public function transactions()
    {
        $transactions = Client::with('samples.parameters')
                        ->orderBy('risNumber', 'DESC')
                        ->paginate(10);
        return view('admin.transactions', ['transactions' => $transactions]);
    }

    // Samples page
    public function samples()
    {
        $samples = Sample::with('client', 'parameters')->orderBy('dueDate', 'DESC')->paginate(10);
        $parameters = Parameter::all();
        $clients = Client::orderBy('risNumber')->get();
        $samps = Sample::all();

        return view('admin.samples', ['samples' => $samples, 'samps' => $samps, 'parameters' => $parameters, 'clients' => $clients]);
    }

    public function sampleCreate($clientRis)
    {
        $parameters = Parameter::all();

        return view('admin.add_sample', ['clientRis' => $clientRis, 'parameters' => $parameters]);
    }
    
    // Clients page
    public function clients()
    {
        $clients = Client::orderBy('clientId')->paginate(10);
        $customers = Client::all();

        return view('admin.clients', ['clients' => $clients, 'customers' => $customers]);
    }

    // Employee accounts page
    public function accounts()
    {
        $accounts = Employee::where('employeeId', '!=', Auth::user()->employeeId)->orderBy('employeeName')->paginate(10);
        $employees = Employee::all();

        return view('admin.accounts', ['accounts' => $accounts, 'employees' => $employees]);
    }

    // Employee view account
    public function viewAccount($id)
    {
        $account = Employee::findOrFail($id);
        
        return view('admin.view_account', ['account' => $account]);
    }

    // Item use history page
    public function history()
    {
        $lists = InventoryList::with('item', 'inventories.user')->orderBy('updated_at')->paginate(10);
   
        return view('admin.inventory-history', ['lists' => $lists]);
    }

    // Glassware page
    public function glassware()
    {
        $items = Item::with('suppliers')->orderBy('itemName')->paginate(10);
        $suppliers = Supplier::all();
        $glasswares = Item::all();

        return view('admin.inventory-glassware', ['items' => $items, 'glasswares' => $glasswares, 'suppliers' => $suppliers]);
    }

    // Stations page
    public function stations()
    {
        $stations = Station::paginate(5);

        return view('admin.stations', ['stations' => $stations]);
    }

    // Parameters page
    public function parameters()
    {
        $parameters = Parameter::with('stations')->orderBy('analysis')->paginate(10);
        $stations = Station::all();
        $params = Parameter::all();

        return view('admin.parameters', ['parameters' => $parameters, 'params' => $params, 'stations' => $stations]);
    }

     // Suppliers page
    public function suppliers()
    {
        $suppliers = Supplier::orderBy('companyName')->paginate(10);
        $supps = Supplier::all();

        return view('admin.suppliers', ['suppliers' => $suppliers, 'supps' => $supps]);
    }

    // Create event page
    public function events()
    {
        $events = Event::paginate(10);

        return view('admin.create_event', ['events' => $events]);
    }

    // Add employee account
    protected function addAccount(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:30|min:4|unique:employees',
            'password' => 'required|string|min:6|confirmed',
            'employeeName' => 'required|string|max:50|unique:employees',
            'position' => 'required|string|max:30',
            'idNumber' => 'required|string|numeric|unique:employees',
            'licenseNumber' => 'required|string|max:50|unique:employees',
            'userType' => 'required|string|max:20',
        ]);
        // Validation checks
        if ($validator->fails()) {
            return redirect('admin/accounts')
                        ->withErrors($validator)
                        ->withInput();
        }

        $account = new Employee;
        $account->username = trim($request->username);
        $account->password = Hash::make($request->password);
        $account->employeeName =  trim($request->employeeName);
        $account->position = trim($request->position);
        $account->idNumber = trim($request->idNumber);
        $account->licenseNumber = trim($request->licenseNumber);
        $account->userType = $request->userType;
        $account->managedBy = Auth::user()->employeeName;
        $account->managedDate = new DateTime;

        if($account->save()){
            Session::flash('flash_account_added', 'Employee account added successfully.');
            return Redirect::back();
        }
        else {
            abort(500, 'Error! Employee was not added successfully.');
        }
    }
    // Delete employee account
    protected function destroyAccount($accountId)
    {
        $account = Employee::findOrFail($accountId);

        if($account->delete()){
            Session::flash('flash_account_deleted', 'Account deleted successfully.');
            return Redirect::back();
        }
        else {
            abort(500, 'Error! Account was not removed successfully.');
        }
    }
    // Update employee account
    protected function updateAccount(Request $request, $accountId)
    {
        // Validation
        $validatorUpdate = Validator::make($request->all(), [
            'username' => 'required|string|max:255|min:4',
            'employeeName' => 'required|string|max:50',
            'old_password' => ['nullable', 'string', new OldPassword],
            'password' => 'nullable|confirmed|string|min:5',
            'position' => 'required|string|max:30',
            'idNumber' => 'required|string|numeric',
            'licenseNumber' => 'required|string|max:50',
            'userType' => 'required|string|max:20',
        ]);
        // Validation check
        if ($validatorUpdate->fails()) {
            return redirect()->back()
                        ->withErrors($validatorUpdate)
                        ->withInput();
        }

        $account = Employee::findOrFail($accountId);
        $account->username = trim($request->username);
        $account->employeeName =  trim($request->employeeName);
        $account->position = trim($request->position);
        $account->idNumber = trim($request->idNumber);
        $account->licenseNumber = trim($request->licenseNumber);
        $account->userType = $request->userType;
        $account->managedBy = Auth::user()->employeeName;
        $account->managedDate = new DateTime();

        if($account->save()){
            Session::flash('flash_account_updated', 'Account updated successfully.');
            return Redirect::back();
        }
        else {
            abort(500, 'Error! Account was not updated successfully.');
        }
    }

    // Add client
    protected function addClient(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'nameOfPerson' => 'required|string|max:255|min:4',
            'nameOfEntity' => 'nullable|string|max:255',
            'address' => 'required|string|max:50',
            'contactNumber' => 'nullable|string|numeric',
            'telephone' => 'nullable|string|numeric',
            'faxNumber' => 'nullable|string|numeric',
            'emailAddress' => 'nullable|string|max:50|email',
            'discount' => 'nullable|numeric|between:0,100',
            'deposit' => 'nullable|numeric|between:0,100000',
            'reclaimSample' => 'required|numeric',
            'followUp' => 'required|date|after:now',
            'testResult' => 'nullable|string|max:5|min:1',
            'remarks' => 'required|string|max:20',
        ]);
        // Validation fails
        if ($validator->fails()) {
            return redirect('admin/clients')
                        ->withErrors($validator)
                        ->withInput();
        }
        // Add a client
        $client = new Client;
        $client->nameOfPerson = trim($request->nameOfPerson);
        $client->nameOfEntity = trim($request->nameOfEntity);
        $client->address =  trim($request->address);
        $client->contactNumber = ("63" . trim($request->contactNumber));
        $client->telephone = trim($request->telephone);
        $client->faxNumber = trim($request->faxNumber);
        $client->emailAddress = trim($request->emailAddress);
        $client->discount = $request->discount;
        $client->deposit = $request->deposit;
        $client->testResult = $request->testResult;
        $client->reclaimSample = $request->reclaimSample;
        $client->remarks = trim($request->remarks);
        $client->followUp = $request->followUp;
        $client->managedBy = Auth::user()->employeeName;
        $client->managedDate = new DateTime();
        $client->save();
        // Add ris number
        $client->risNumber = date("Y", strtotime($client->created_at)) . '-' . ($client->clientId + 549);

        if($client->save()) {
            // Insert transaction
            $transaction = new Transaction;
            $transaction->client = $client->clientId;
            $transaction->approvedBy = Auth::user()->employeeId;
            $transaction->managedBy = Auth::user()->employeeName;
            $transaction->managedDate = new DateTime();

            if($transaction->save()){
                $clientRis = $client->risNumber;
    
                Session::flash('flash_client_added', 'Client added successfully. Please add the samples of the new client.');
                return redirect()->action('AdminController@sampleCreate', ['clientRis' => $clientRis]);
            }
            else {
                abort(500, 'Error! Transaction was unsuccessful.');
            }
        }
        else {
            abort(500, 'Error! Client was not added successfully.');
        }
    }
    // Delete a client
    protected function destroyClient($clientId)
    {
        $client = Client::with('samples')->findOrFail($clientId);
        if($client->samples->count() > 0){
            return Redirect::back()->with('has_samples', 'This client has samples and cannot be deleted.');
        }
        else {
            if($client->delete()){
                Session::flash('flash_client_deleted', 'Client has been deleted successfully.');
                return Redirect::back();
            }
            else {
                abort(500, 'Error! Deletion was unsuccessful.');
            }
        }
    }
    // Update client
    protected function updateClient(Request $request, $clientId)
    {
        // Validation
        $validatorUpdate = Validator::make($request->all(), [
            'nameOfPerson' => 'required|string|max:255|min:4',
            'nameOfEntity' => 'nullable|string|max:255',
            'address' => 'required|string|max:50',
            'contactNumber' => 'string|numeric',
            'faxNumber' => 'nullable|string|numeric',
            'emailAddress' => 'nullable|string|max:50|email',
            'discount' => 'nullable|numeric|between:0,100',
            'deposit' => 'nullable|numeric|between:0,100000',
            'reclaimSample' => 'required|numeric',
            'followUp' => 'required|date',
            'testResult' => 'nullable|string|max:5|min:1',
            'remarks' => 'required|string|max:20',
        ]);
        // Validation fails...
        if ($validatorUpdate->fails()) {
            return redirect('admin/clients')
                        ->withErrors($validatorUpdate)
                        ->withInput();
        }
        // Find client
        $client = Client::findOrFail($clientId);
        // Update values
        $client->nameOfPerson = trim($request->nameOfPerson);
        $client->nameOfEntity = trim($request->nameOfEntity);
        $client->address =  trim($request->address);
        $client->contactNumber = ("63" . trim($request->contactNumber));
        $client->faxNumber = trim($request->faxNumber);
        $client->emailAddress = trim($request->emailAddress);
        $client->discount = $request->discount;
        $client->deposit = $request->deposit;
        $client->testResult = $request->testResult;
        $client->reclaimSample = $request->reclaimSample;
        $client->remarks = trim($request->remarks);
        $client->followUp = $request->followUp;
        $client->managedBy = Auth::user()->employeeName;
        $client->managedDate = new DateTime();
    
        if($client->save()){
            Session::flash('flash_client_updated', 'Client information updated successfully.');
            return Redirect::back();
        }
        else {
            abort(500, 'Error!');
        }

    }
    // Adding of sample
    protected function addSample(Request $request)
    {
        // Validation
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
        // Validation fails
        if ($validator->fails()) {
            return  redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        // Find id of client
        $client = Client::where('risNumber', $request->clientRis)->value('clientId');
        // Insert sample
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
        // Insert sample tests
        foreach ($request->parameter as $parameter => $analysis) {
            $sampletests = new Sample_Tests;
            $sampletests->sampleCode = $sample->sampleId;
            $sampletests->parameters = Parameter::where('analysis', $analysis)->value('parameterId');
            $sampletests->managedBy = Auth::user()->employeeName;
            $sampletests->managedDate = new DateTime();
            $sampletests->save();
        }
        // Return to add sample page
        if($sample->save()){

            $users = Employee::all();
        
            foreach ($users as $user) {
                if ($user['userType'] == 'administrator' || $user['userType'] == 'secretary') {

                    $user->notify((new NewSampleAdded($sample)));
                }
            }
            Session::flash('flash_sample_added', 'Sample added successfully. You can add another sample.');

            return redirect()->action('AdminController@sampleCreate', ['clientRis' => $request->clientRis]);
        }
        else {
            abort(500, 'Error! Sample not added.');
        }
        
    }
    // Add samples manually to client
    protected function insertSample(Request $request)
    {
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
            return redirect('admin/samples')
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
        // Insert sample tests
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

            $params = Parameter::all();
            Session::flash('flash_sample_added', 'Sample added successfully. You can add another sample.');

            return view('admin.add_sample', ['clientRis' => $request->clientId, 'parameters' => $params]);
        }
        else {
            abort(500, 'Error! Sample not added.');
        }
    }
    // Delete a sample
    protected function destroySample($sampleId)
    {
        $sample = Sample::findOrFail($sampleId);
        if($sample->delete()){
            Session::flash('flash_sample_deleted', 'Sample deleted successfully.');
            return Redirect::back();
        }
        else {
            abort(500, 'Error! Sample not deleted.');
        }
    }
    // Update sample
    protected function updateSample(Request $request, $sampleId)
    {
        // Validation
        $validatorUpdate = Validator::make($request->all(), [
            'clientId' => 'required',
            'clientsCode' => 'nullable|string|max:255',
            'sampleType' => 'required|string|max:255',
            'sampleCollection' => 'required|string|max:50',
            'samplePreservation' => 'nullable|string|max:50',
            'newParameter' => 'required',
            'purposeOfAnalysis' => 'nullable|string|max:50',
            'sampleSource' => 'required|string|max:20',
            'dueDate' => 'required|string|max:50',
        ]);
        // Validation fails
        if ($validatorUpdate->fails()) {
            return redirect('admin/samples')
                        ->withErrors($validatorUpdate)
                        ->withInput();
        }
        // Get id of client
        $client = Client::where('risNumber', $request->clientId)->value('clientId');

        // Find sample
        $sample = Sample::findOrFail($sampleId);
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
        //Remove sample tests
        $tests = Sample_Tests::where('sampleCode', $sample->sampleId)->get();
        foreach ($tests as $test) {
            $test->delete();
        }
        // Add sample tests
        foreach ($request->newParameter as $parameter => $analysis) {
            $sampletests = new Sample_Tests;
            $sampletests->sampleCode = $sample->sampleId;
            $sampletests->parameters = Parameter::where('analysis', $analysis)->value('parameterId');
            $sampletests->status = "Not Started";
            $sampletests->managedBy = Auth::user()->employeeName;
            $sampletests->managedDate = new DateTime();
            $sampletests->save();
        }
        
        if($sample->save()){
            Session::flash('flash_sample_updated', 'Sample updated successfully.');
            return Redirect::back();
        }
        else {
            abort(500, 'Error! Sample not updated.');
        }
    }
    // Add parameter
    protected function addParameter(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'analysis' => 'required|string|max:255|unique:parameters',
            'method' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'station' => 'required|string|max:10',
        ]);
        // Validation fails
        if ($validator->fails()) {
            return redirect('admin/parameters')
                        ->withErrors($validator)
                        ->withInput();
        }

        // Insert parameter
        $parameter = new Parameter;
        $parameter->analysis = trim($request->analysis);
        $parameter->method = trim($request->method);
        $parameter->price = $request->price;
        $parameter->station = Station::where('stationName', $request->station)->value('stationId');
        $parameter->managedBy = Auth::user()->employeeName;
        $parameter->managedDate = new DateTime();
        // Save
        if($parameter->save()){
            Session::flash('flash_parameter_added', 'Analysis added successfully.');
            return Redirect::back();
        }
        else {
            abort(500, 'Error! Parameter not added.');
        }
    }
    // Delete parameter
    protected function destroyParameter($parameterId)
    {
        $parameter = Parameter::findOrFail($parameterId);
        if($parameter->delete()){
            Session::flash('flash_parameter_deleted', 'Analysis has been deleted successfully.');
            return Redirect::back();
        }
        else {
            abort(500, 'Error! Parameter not deleted.');
        }
    }
    // Update parameter
    protected function updateParameter(Request $request, $parameterId)
    {
        // Validation
        $validatorUpdate = Validator::make($request->all(), [
            'analysis' => 'required|string|max:255',
            'method' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'station' => 'required|string|max:10',
        ]);
        // Validation fails
        if ($validatorUpdate->fails()) {
            return redirect('admin/parameters')
                        ->withErrors($validatorUpdate)
                        ->withInput();
        }
        // Find parameter
        $parameter = Parameter::findOrFail($parameterId);
        $parameter->analysis = trim($request->analysis);
        $parameter->method = trim($request->method);
        $parameter->price = $request->price;
        $parameter->station = Station::where('stationName', $request->station)->value('stationId');
        $parameter->managedBy = Auth::user()->employeeName;
        $parameter->managedDate = new DateTime();
        // Save
        if($parameter->save()){
            Session::flash('flash_parameter_updated', 'Analysis information updated successfully.');
            return Redirect::back();
        }
        else {
            abort(500, 'Error! Parameter not updated.');
        }
    }
    // Add supplier
    protected function addSupplier(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'companyName' => 'required|string|max:255|unique:suppliers',
            'emailAddress' => 'nullable|string|email|unique:suppliers',
            'contactNumber' => 'nullable|string|numeric',
        ]);
        // Validation fails
        if ($validator->fails()) {
            return redirect('admin/suppliers')
                        ->withErrors($validator)
                        ->withInput();
        }

        // Insert supplier
        $supplier = new Supplier;
        $supplier->companyName = trim($request->companyName);
        $supplier->emailAddress = trim($request->emailAddress);
        $supplier->contactNumber =  '63' . trim($request->contactNumber);
        $supplier->managedBy = Auth::user()->employeeName;
        $supplier->managedDate = new DateTime();
        // Save
        if($supplier->save()){
            Session::flash('flash_supplier_added', 'Supplier added successfully.');
            return Redirect::back();
        }
        else {
            abort(500, 'Error! Supplier not added.');
        }
    }
    // Delete supplier
    protected function destroySupplier($supplierId)
    {
        $supplier = Supplier::with('items')->findOrFail($supplierId);

        if($supplier->items->count() > 0) {
            return Redirect::back()->with('has_items', 'This supplier has items and cannot be deleted.');
        }
        else {
            if($supplier->delete()){
                Session::flash('flash_supplier_deleted', 'Supplier deleted successfully.');
                return Redirect::back();
            }
            else {
                abort(500, 'Error! Supplier not deleted.');
            }
        }
    }
    // Update supplier
    protected function updateSupplier(Request $request, $supplierId)
    {
        // Validation
        $validatorUpdate = Validator::make($request->all(), [
            'companyName' => 'required|string|max:255',
            'emailAddress' => 'nullable|string|min:6',
            'contactNumber' => 'required|string|max:50',
        ]);
        // Validation fails
        if ($validatorUpdate->fails()) {
            return redirect('admin/suppliers')
                        ->withErrors($validatorUpdate)
                        ->withInput();
        }
        // Find supplier
        $supplier = Supplier::findOrFail($supplierId);
        $supplier->companyName = trim($request->companyName);
        $supplier->emailAddress = trim($request->emailAddress);
        $supplier->contactNumber =  trim($request->contactNumber);
        $supplier->managedBy = Auth::user()->employeeName;
        $supplier->managedDate = new DateTime();
        // Save
        if($supplier->save()){
            Session::flash('flash_supplier_updated', 'Supplier updated successfully.');
            return Redirect::back();
        }
        else {
            abort(500, 'Error! Supplier not updated.');
        }
    }
    // Add station
    protected function addStation(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'stationName' => 'required|string|max:255|unique:stations'
        ]);
        // Validation fails
        if ($validator->fails()) {
            return redirect('admin/stations')
                        ->withErrors($validator)
                        ->withInput();
        }

        // Insert station
        $station = new Station;
        $station->stationName = trim($request->stationName);
        $station->managedBy = Auth::user()->employeeName;
        $station->managedDate = new DateTime();
        // Save
        if($station->save()){
            Session::flash('flash_station_added', 'Station added successfully.');
            return Redirect::back();
        }
        else {
            abort(500, 'Error! Station not added.');
        }
    }
    // Delete Station
    protected function destroyStation($stationId)
    {
        $station = Station::with('parameters')->findOrFail($stationId);

        if($station->parameters->count() > 0) {
            return Redirect::back()->with('has_parameters', 'This station has parameters and cannot be deleted.');
        }
        else {
            if($station->delete()){
                Session::flash('flash_station_deleted', 'Station deleted successfully.');
                return Redirect::back();
            }
            else {
                abort(500, 'Error! Station not deleted.');
            }
        }
    }
    // Add event
    protected function addEvent(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'eventName' => 'required|string|max:255',
            'startDate' => 'required|date|before:'.$request->endDate,
            'endDate' => 'required|date|after:'.$request->startDate
        ]);
        // Validation fails
        if ($validator->fails()) {
            return redirect('admin/events')
                        ->withErrors($validator)
                        ->withInput();
        }
        // Insert event
        $event = new Event;
        // $event->event_name = trim($request->eventName);
        $event->event_name = $request->eventName;
        $event->start_date = $request->startDate;
        $event->end_date = $request->endDate;

        //CHECK SAVE
        if($event->save()){
            Session::flash('flash_event_added', 'Event added successfully.');
            return Redirect::back();
        }
        else {
            abort(500, 'Error!');
        }
    }// Update event
    protected function updateEvent(Request $request, $eventId)
    {
        // Validation
        $validatorUpdate = Validator::make($request->all(), [
            'eventName' => 'required|string|max:255',
            'startDate' => 'required|date|before:'.$request->endDate,
            'endDate' => 'required|date|after:'.$request->startDate
        ]);
        // Validation fails
        if ($validatorUpdate->fails()) {
            return redirect('admin/events')
                        ->withErrors($validatorUpdate)
                        ->withInput();
        }
        // Find event
        $event = Event::findOrFail($eventId);
        $event->event_name = trim($request->eventName);
        $event->start_date = $request->startDate;
        $event->end_date = $request->endDate;
        // Save
        if($event->save()){
            Session::flash('flash_event_updated', 'Event updated successfully.');
            return Redirect::back();
        }
        else {
            abort(500, 'Error! Event not updated.');
        }
    }
    // Delete event
    protected function destroyEvent($eventId)
    {
        $event = Event::findOrFail($eventId);
        if($event->delete()){
            Session::flash('flash_event_deleted', 'Event deleted successfully.');
            return Redirect::back();
        }
        else {
            abort(500, 'Error! Event not deleted.');
        }
    }
    // Add glassware
    protected function addItem(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'itemName' => 'required|string|min:3|max:255',
            'containerType' => 'required|string|min:6|max:255',
            'volumeCapacity' => 'required|numeric',
            'quantity' => 'required|numeric',
            'supplier' => 'nullable|string',
        ]);
        // Validation fails
        if ($validator->fails()) {
            return redirect('admin/inventory/glassware')
                        ->withErrors($validator)
                        ->withInput();
        }
        // Add item
        $item = new Item;
        $item->itemName = trim($request->itemName);
        $item->containerType = trim($request->containerType);
        $item->volumeCapacity = $request->volumeCapacity;
        $item->quantity = $request->quantity;
        $item->supplier = Supplier::where('companyName', $request->supplier)->value('supplierId');
        $item->managedBy = Auth::user()->employeeName;
        $item->managedDate = new DateTime;
        if($item->save()) {
            Session::flash('flash_item_added', 'Item added successfully.');
            return Redirect::back();
        }
        else {
            abort(500, 'Error! Item not added.');
        }
    }
    // DESTROY ITEM
    protected function destroyItem($itemId)
    {
        $item = Item::findOrFail($itemId);
        if($item->delete()){
            Session::flash('flash_item_deleted', 'Item deleted successfully.');
            return Redirect::back();
        }
        else {
            abort(500, 'Error! Item not deleted.');
        }
    }
    // UPDATE ITEM
    protected function updateItem(Request $request, $itemId)
    {
        // Validation
        $validatorUpdate = Validator::make($request->all(), [
            'itemName' => 'nullable|string|min:3|max:255',
            'containerType' => 'nullable|string|min:6|max:255',
            'volumeCapacity' => 'nullable|numeric',
            'quantity' => 'nullable|numeric',
            'supplier' => 'nullable|string',
        ]);
        // Validation fails
        if ($validatorUpdate->fails()) {
            return redirect('admin/inventory/glassware')
                        ->withErrors($validatorUpdate)
                        ->withInput();
        }
        // Find item
        $item = Item::findOrFail($itemId);
        $item->itemName = trim($request->itemName);
        $item->containerType = trim($request->containerType);
        $item->volumeCapacity = $request->volumeCapacity;
        $item->quantity = $request->quantity;
        $item->supplier = Supplier::where('companyName', $request->supplier)->value('supplierId');
        $item->managedBy = Auth::user()->employeeName;
        $item->managedDate = new DateTime;
        if($item->save()) {
            Session::flash('flash_item_updated', 'Item updated successfully.');
            return Redirect::back();
        }
        else {
            abort(500, 'Error! Item was not updated.');
        }
    }

    protected function searchClient(Request $request)
    {
        $client = Client::where('risNumber', $request->search)->paginate(10);
        $customers = Client::all();

        if($client->count() == 0) {
            return $this->clients();
        }  
        else {
            return view('admin.clients', ['clients' => $client, 'customers' => $customers]);
        }
    }

    protected function searchSample(Request $request)
    {
        $samples = Sample::where('laboratoryCode', $request->search)->with('client', 'parameters')->paginate(10);
        $parameters = Parameter::all();
        $clients = Client::orderBy('risNumber')->get();
        $samps = Sample::all();

        if($samples->count() == 0) {
            return $this->samples();
        }
        else {
            return view('admin.samples', ['samples' => $samples, 'samps' => $samps, 'parameters' => $parameters, 'clients' => $clients]);
        }
    }

    protected function searchParameter(Request $request)
    {
        $parameters = Parameter::where('analysis', $request->search)->with('stations')->paginate(10);
        $params = Parameter::all();
        $stations = Station::all();

        if($parameters->count() == 0) {
            return $this->parameters();
        }
        else {
            return view('admin.parameters', ['parameters' => $parameters, 'params' => $params, 'stations' => $stations]);
        }
    }

    protected function searchAccount(Request $request)
    {
        $accounts = Employee::where('employeeName', $request->search)->paginate(10);
        $employees = Employee::all();

        if($accounts->count() == 0) {
            return $this->accounts();
        }
        else {
            return view('admin.accounts', ['accounts' => $accounts, 'employees' => $employees]);
        }
    }

    protected function searchItem(Request $request)
    {
        $items = Item::with('suppliers')->where('itemName', $request->search)->paginate(10);
        $suppliers = Supplier::all();
        $glasswares = Item::all();

        if($items->count() == 0) {
            return $this->glassware();
        }
        else {
            return view('admin.inventory-glassware', ['items' => $items, 'glasswares' => $glasswares, 'suppliers' => $suppliers]);
        }
    }

    protected function searchSupplier(Request $request)
    {
        $suppliers = Supplier::where('companyName', $request->search)->paginate(10);
        $supps = Supplier::all();

        if($suppliers->count() == 0) {
            return $this->suppliers();
        }
        else {
            return view('admin.suppliers', ['suppliers' => $suppliers, 'supps' => $supps]);
        }
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
        
        return $this->admin();
    }

    protected function readAll()
    {
        $user = Employee::where('employeeId', Auth::user()->employeeId)->with('unreadNotifications')->first();

        foreach ($user->notifications as $notification) {
            $notification->markAsRead();
        }

        return $this->admin();
    }
}



