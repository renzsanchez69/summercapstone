<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Station;

Auth::routes();

//CLIENT ROUTES
Route::get('/RIS', function () {
    return view('clients.client_RIS');
})->name('RisNumber');

Route::post('/RIS', 'ClientController@RIS')->name('RIS');
Route::get('/S&R/parameters/search', 'ClientController@searchParameter')->name('searchParameter-client');
Route::get('/', 'EventsController@index')->name('events.index');
Route::post('/client-home', 'EventsController@addEvent')->name('events.add');
Route::get('/S&R', 'ClientController@parameters')->name('parameters-client');
Route::get('/contact', 'ClientController@contact');
Route::post('/contact','ClientController@receive')->name('contact.store');
// END CLIENT ROUTES

//SECRETARY ROUTES
Route::middleware(['secretary','auth'])->group(function (){
Route::get('/secretary/home', 'SecretaryController@index')->name('notification-secretary');
Route::get('/secretary/inventory', 'SecretaryController@inve')->name('inventory');
Route::get('/secretary/view', 'SecretaryController@stat')->name('view');
Route::get('/secretary/add', 'SecretaryController@status')->name('addSecretary');
Route::post('/secretary/paid/{clientId}', 'SecretaryController@paid')->name('paidSecretary');
Route::post('/secretary/send/{clientId}', 'SecretaryController@send')->name('send');
Route::post('/secretary/informClient/{clientId}','SecretaryController@informClient');

Route::get('/secretary/create','SecretaryController@create')->name('createClient');
Route::post('/secretary/create', 'SecretaryController@addClient')->name('addClient-secretary');
Route::post('/secretary/create-sample','SecretaryController@createSample')->name('createSample-secretary');
Route::get('/secretary/create-sample/{clientRis}','SecretaryController@sampleView');
Route::get('/secretary/form','SecretaryController@form')->name('form');
Route::get('/barcode/{clientId}','ProduitController@index')->name('barcode');
Route::post('/secretary/search','ProduitController@search')->name('search-barcode');
Route::get('/secretary/add-sample','SecretaryController@addSample')->name('addSample');
Route::post('/secretary/add-sample','SecretaryController@postAddSample')->name('postAddSample');
Route::get('/secretary/samples','ProduitController@selectSamples');
Route::post('/secretary/print','ProduitController@printSamples');
Route::get('/secretary/home/{id?}', 'SecretaryController@read')->name('notif-read');
Route::post('/secretary/home', 'SecretaryController@readAll')->name('read-all');
// Route::post('/secretary/search/not_found','ProduitController@search')->name('search-fail');
});
//END SECRETARY ROUTES

//ADMIN ROUTES
//Redirect route
Route::redirect('/admin', '/admin/home');
//Middleware for User Content Control
Route::middleware(['admin','auth'])->group(function (){
    //Admin routes
    Route::get('/admin/dashboard', 'AdminController@dashboard')->name('dashboard');
    Route::get('/admin/notifications', 'AdminController@admin')->name('admin');
    Route::get('/admin/transactions', 'AdminController@transactions')->name('transactions');
    Route::get('/admin/samples', 'AdminController@samples')->name('samples-admin');
    Route::get('/admin/clients', 'AdminController@clients')->name('clients-admin');
    Route::get('/admin/accounts', 'AdminController@accounts')->name('accounts-admin');
    Route::get('/admin/accounts/{id}', 'AdminController@viewAccount')->name('view-account');
    Route::get('/admin/stations', 'AdminController@stations')->name('stations-admin');
    Route::get('/admin/parameters', 'AdminController@parameters')->name('parameters-admin');
    Route::get('/admin/events', 'AdminController@events')->name('events-admin');
    //ADD, DELETE AND UPDATE ROUTES
    Route::post('/admin/accounts', 'AdminController@addAccount')->name('addAccount-admin');
    Route::delete('/admin/accounts/{accountId}', 'AdminController@destroyAccount')->name('deleteAccount-admin');
    Route::patch('/admin/accounts/{accountId}', 'AdminController@updateAccount')->name('updateAccount-admin');
    Route::post('/admin/clients', 'AdminController@addClient')->name('addClient-admin');
    Route::get('admin/client-samples/{clientRis}', 'AdminController@sampleCreate')->name('sampleCreate-admin');
    Route::delete('/admin/clients/{clientId}', 'AdminController@destroyClient')->name('deleteClient-admin');
    Route::patch('/admin/clients/{clientId}', 'AdminController@updateClient')->name('updateClient-admin');
    Route::post('/admin/samples-add', 'AdminController@addSample')->name('addSample-admin');
    Route::post('/admin/samples', 'AdminController@insertSample')->name('insertSample-admin');
    Route::delete('/admin/samples/{sampleId}', 'AdminController@destroySample')->name('destroySample-admin');
    Route::patch('/admin/samples/{sampleId}', 'AdminController@updateSample')->name('updateSample-admin');
    Route::post('/admin/stations', 'AdminController@addStation')->name('addStation-admin');
    Route::delete('/admin/stations/{stationId}', 'AdminController@destroyStation')->name('destroyStation-admin');
    Route::patch('/admin/stations/{stationId}', 'AdminController@updateStation')->name('updateStation-admin');
    Route::post('/admin/parameters', 'AdminController@addParameter')->name('addParameter-admin');
    Route::delete('/admin/parameters/{parameterId}', 'AdminController@destroyParameter')->name('deleteParameter-admin');
    Route::patch('/admin/parameters/{parameterId}', 'AdminController@updateParameter')->name('updateParameter-admin');
    Route::post('/admin/events', 'AdminController@addEvent')->name('addEvent-admin');
    Route::delete('/admin/events/{eventId}', 'AdminController@destroyEvent')->name('destroyEvent-admin');
    Route::patch('/admin/events/{eventId}', 'AdminController@updateEvent')->name('updateEvent-admin');
    //Inventory routes
    Route::get('/admin/suppliers', 'AdminController@suppliers')->name('suppliers-admin');
    Route::post('/admin/suppliers', 'AdminController@addSupplier')->name('addSupplier-admin');
    Route::delete('/admin/suppliers/{supplierId}', 'AdminController@destroySupplier')->name('deleteSupplier-admin');
    Route::patch('/admin/suppliers/{supplierId}', 'AdminController@updateSupplier')->name('updateSupplier-admin');
    Route::post('/admin/inventory/glassware', 'AdminController@addItem')->name('addItem-admin');
    Route::delete('/admin/inventory/glassware/{itemId}', 'AdminController@destroyItem')->name('destroyItem-admin');
    Route::patch('/admin/inventory/glassware/{itemId}', 'AdminController@updateItem')->name('updateItem-admin');
    Route::get('/admin/inventory/history', 'AdminController@history')->name('inventory-history-admin');
    Route::get('/admin/inventory/glassware', 'AdminController@glassware')->name('inventory-glassware-admin');
    //Search routes
    Route::get('/admin/clients/search', 'AdminController@searchClient')->name('searchClient-admin');
    Route::get('/admin/samples/search', 'AdminController@searchSample')->name('searchSample-admin');
    Route::get('/admin/parameters/search', 'AdminController@searchParameter')->name('searchParameter-admin');
    Route::get('/admin/accounts/search', 'AdminController@searchAccount')->name('searchAccount-admin');
    Route::get('/admin/inventory/glassware/search', 'AdminController@searchItem')->name('searchItem-admin');
    Route::get('/admin/suppliers/search', 'AdminController@searchSUpplier')->name('searchSupplier-admin');
    Route::get('/admin/home/{id?}', 'AdminController@read')->name('notif-read-admin');
    Route::post('/admin/home', 'AdminController@readAll')->name('read-notif-all');
});
// END ADMIN ROUTES


//Route::middleware(['analyst','auth'])->group(function (){
    // ANALYST ROUTES
Route::middleware(['analyst','auth'])->group(function (){
    Route::redirect('/analyst', '/analyst/samples');
    Route::get('/analyst/notification', 'AnalystController@notification')->name('analystnotification');
    Route::get('/analyst/notification/{id?}', 'AnalystController@read')->name('read-notif-analyst');
    Route::post('/analyst/notification', 'AnalystController@readAll')->name('readall');
    Route::get('/analyst/samples', 'AnalystController@samples')->name('analystsamples');

    Route::get('/analyst/inventory', 'AnalystController@inventory')->name('analystinventory');
    Route::post('/analyst/inventory/update', 'AnalystController@inventoryupdate')->name('inventoryupdate');
    Route::get('/analyst/inventory/history', 'AnalystController@history')->name('inventoryhistory');

    Route::get('/analyst/sample/station/{id}', 'AnalystController@samplePerStation')->name('samplestation');
    Route::get('/analyst/{stationid}/sample/{id}', 'AnalystController@sampleDetails')->name('sampledetails');

    Route::post('/analyst/receive/sample/{id}', 'AnalystController@receiveSample')->name('receivesample');
    Route::post('/analyst/complete/sample/{id}', 'AnalystController@completeSample')->name('completesample');
    Route::get('/analyst/sendmessage', 'AnalystController@sendMessage');
});
    // END ANALYST ROUTES
//});
