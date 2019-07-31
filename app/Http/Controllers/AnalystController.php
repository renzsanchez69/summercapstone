<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Sample as Sample;
use App\Employee;
use App\Item as Item;
use App\Station as Station;
use App\Inventory as Inventory;
use App\InventoryList as InventoryList;
use App\Client as Clients;
use Nexmo\Client\Credentials\Basic as NexmoBasic;
use Nexmo\Client as NexmoClient;

use DB;

class AnalystController extends Controller
{  
    public function samples(){
        //SEELCT s.laboratoryCode,s.dueDate,s.sampleCollection,sta.stationName,s.purposeOfAnalysis,st.timeReceived 
        //FROM samples s 
        //LEFT JOIN sample__tests st ON st.sampleCode=s.sampleId 
        //LEFT JOIN parameters p ON p.parameterId=st.parameters
        //LEFT JOIN stations AS sta ON p.station=sta.stationid
        //WHERE st.status = 'In Progress'
        //GROUP BY s.laboratoryCode,s.dueDate,s.sampleCollection,sta.stationName,s.purposeOfAnalysis,st.timeReceived
        
        $sampledata = DB::table('samples AS s')
        ->select('s.laboratoryCode', 's.dueDate', 's.sampleCollection','sta.stationName','s.purposeOfAnalysis','st.timeReceived')
        ->leftJoin('sample__tests AS st','st.sampleCode','=','s.sampleId')
        ->leftJoin('parameters AS p', 'p.parameterId', '=', 'st.parameters')
        ->leftJoin('stations AS sta', 'p.station', '=', 'sta.stationid')
        ->where('st.status','=', 'In Progress')
        ->groupBy('s.laboratoryCode', 's.dueDate','s.sampleCollection','sta.stationName','s.purposeOfAnalysis','st.timeReceived')
        ->distinct()
        ->get();

    	return view('analyst.samples',[ 'sampledatas' => $sampledata ]);
    }
    
    public function notification(Request $request){
        //SEELCT s.laboratoryCode,s.dueDate,s.sampleCollection,sta.stationName,s.purposeOfAnalysis,st.timeReceived 
        //FROM samples s 
        //LEFT JOIN sample__tests st ON st.sampleCode=s.sampleId 
        //LEFT JOIN parameters p ON p.parameterId=st.parameters
        //LEFT JOIN stations AS sta ON p.station=sta.stationid
        //WHERE st.status = 'In Progress' AND dueDate < (currentDate+4)
        //GROUP BY s.laboratoryCode,s.dueDate,s.sampleCollection,sta.stationName,s.purposeOfAnalysis,st.timeReceived
        $user = Employee::where('employeeId', Auth::user()->employeeId)->with('unreadNotifications')->first();
        $sampledata = DB::table('samples AS s')
                    ->select('s.laboratoryCode', 's.dueDate', 's.sampleCollection','sta.stationName','st.status','p.analysis')
                    ->leftJoin('sample__tests AS st','st.sampleCode','=','s.sampleId')
                    ->leftJoin('parameters AS p', 'p.parameterId', '=', 'st.parameters')
                    ->leftJoin('stations AS sta', 'p.station', '=', 'sta.stationid')
                    ->where('s.dueDate','<',date("Y-m-d",strtotime("+5 day")))
                    ->where(function($query){
                        $query->where('st.status','=', 'In Progress')
                            ->orWhere('st.status','=', 'Not Started');
                    })
                    ->groupBy('s.laboratoryCode', 's.dueDate','s.sampleCollection','sta.stationName','st.status','p.analysis')
                     ->distinct()
                     ->get();

         $request->session()->put('notifcount', count($sampledata));
        return view('analyst.notification',[ 'sampledatas' => $sampledata,'user'=>$user ]);
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
        
        return $this->analyst();
    }

    protected function readAll()
    {
        $user = Employee::where('employeeId', Auth::user()->employeeId)->with('unreadNotifications')->first();

        foreach ($user->notifications as $notification) {
            $notification->markAsRead();
        }

        return $this->analyst();
    }

    
    public function inventory(){
        // SELECT * FROM item
    	$itemdata = Item::all();

    	return view('analyst.inventory', [ 'items' => $itemdata ]);
    }

    public function inventoryupdate(Request $request){
        $input = $request->all();
        
    	$inventory = array('usedBy' => Auth::user()->employeeId);

    	$invresult = Inventory::create($inventory);

    	$input['itemid'] = explode(",",$input['itemid']);
     	$input['borrowqty'] = explode(",",$input['borrowqty']);

    	for($i=0; $i < sizeof($input['itemid']); $i++){
    		if($input['borrowqty'][$i] > 0){
    			$id = $input['itemid'][$i];
                $item = Item::find($id);
                //UPDATE item SET quantity = currentqty - inputbrwqty WHERE itemId=id
    			$updateresult = Item::find($id)->update(array('quantity' => $item->quantity - $input['borrowqty'][$i]));
                //INSERT INTO CREATE(inventoryId,itemId,qty) VALUES ($invresult->inventoryId,$itemId,$input[borrowqty])
                $invListResult = InventoryList::create(array('inventoryId' => $invresult->inventoryId , 'itemId' => $id, 'qty' => $input['borrowqty'][$i]));
    		}
    	}
		return redirect('/analyst/inventory');	
    }

    public function history(){

        // SELECT inventory_list.qty, inventory.inventoryId, inventory.dateofuse, item.itemType, item.containerType 
        // FROM inventory LEFT JOIN inventory_list ON inventory_list.inventoryId=inventory.inventoryId 
        // LEFT JOIN item ON item.itemId=inventory_list.itemId WHERE inventory.empId = 1 
        // ORDER BY inventory_list.created_at
    	$history = DB::table('inventory_list')
    		->select('inventory_list.qty', 'inventories.inventoryId' , 'inventories.created_at', 'items.itemName', 'items.containerType', 'items.volumeCapacity')
    		->leftJoin('inventories','inventory_list.inventoryId','=','inventories.inventoryId')
    		->leftJoin('items','items.itemId','=','inventory_list.itemId')
    		->where('inventories.usedBy','=',Auth::user()->employeeId) //change to session id
    		->orderBy('inventory_list.created_at')
    		->get();

    	return view('analyst.inventoryhistory', [ 'history' => $history ]);
    }

    public function samplePerStation($id){
        //SEELCT s.laboratoryCode,s.dueDate,st.status,st.timecompleted 
        //FROM samples s 
        //LEFT JOIN sample__tests st ON st.sampleCode=s.sampleId 
        //LEFT JOIN parameters p ON p.parameterId=st.parameters
        //LEFT JOIN stations AS sta ON p.station=sta.stationid
        //WHERE st.status = 'Completed' AND p.station = $id
        //GROUP BY s.laboratoryCode,s.dueDate,st.status,st.timecompleted
        
    	$completeperstation = DB::table('samples AS s')
    			->select('s.laboratoryCode', 's.dueDate', 'st.status','st.timecompleted' )
    			->leftJoin('sample__tests AS st','st.sampleCode','=','s.sampleId')
    			->leftJoin('parameters AS p', 'p.parameterId', '=', 'st.parameters')
    			->leftJoin('stations AS sta', 'p.station', '=', 'sta.stationid')
    			->where('p.station','=', $id)
                ->where('st.status','=', 'Completed')
                ->groupBy('s.laboratoryCode', 's.dueDate','st.status','st.timecompleted')
                ->distinct()
                ->get();

        //SEELCT s.laboratoryCode,s.dueDate,st.status,st.timecompleted 
        //FROM samples s 
        //LEFT JOIN sample__tests st ON st.sampleCode=s.sampleId 
        //LEFT JOIN parameters p ON p.parameterId=st.parameters
        //LEFT JOIN stations AS sta ON p.station=sta.stationid
        //WHERE st.status = 'In Progress' AND p.station = $id
        //GROUP BY s.laboratoryCode,s.dueDate,st.status,st.timecompleted

        $progressperstation = DB::table('samples AS s')
                ->select('s.laboratoryCode', 's.dueDate', 'st.status','s.created_at', 'st.timeReceived' )
                ->leftJoin('sample__tests AS st','st.sampleCode','=','s.sampleId')
                ->leftJoin('parameters AS p', 'p.parameterId', '=', 'st.parameters')
                ->leftJoin('stations AS sta', 'p.station', '=', 'sta.stationid')
                ->where('p.station','=', $id)
                ->where('st.status','=', 'In Progress')
                ->groupBy('s.laboratoryCode', 's.dueDate','st.status','s.created_at', 'st.timeReceived')
                ->distinct()
                ->get();

        $clientsssss = Clients::all();

        $station = Station::where('stationId','=',$id)->get();
             
    	return view('analyst.stationsamples', ['manyclient' => $clientsssss, 'inprogresssample' => $progressperstation ,'station' => $station[0], 'completedsample' => $completeperstation]);
    }

    public function sampleDetails($stationid,$id){
        //SEELCT s.laboratoryCode,p.analysis,s.sampleCollection,st.status,st.timecompleted,s.created_at 
        //FROM samples s 
        //LEFT JOIN sample__tests st ON st.sampleCode=s.sampleId 
        //LEFT JOIN parameters p ON p.parameterId=st.parameters
        //WHERE s.laboratoryCode = $id AND p.station = $stationid

    	$sampledetails = DB::table('samples AS s')
    			->select('s.laboratoryCode','p.analysis', 's.sampleCollection', 'st.status', 'st.timecompleted', 's.created_at' )
    			->leftJoin('sample__tests AS st','st.sampleCode','=','s.sampleId')
    			->leftJoin('parameters AS p', 'p.parameterId', '=', 'st.parameters')
    			->where('s.laboratoryCode','=', $id)
    			->where('p.station','=', $stationid)
    			->get();
        
    	return view('analyst.sampledetails', [ 'details' => $sampledetails, 'station' => $stationid ]);
    }

    public function receiveSample($id,Request $request){
        $input = $request->all();

        $updateresult = DB::table('sample__tests AS st')
            ->leftJoin('samples AS s','st.sampleCode','=','s.sampleId')
            ->leftJoin('parameters AS p', 'p.parameterId', '=', 'st.parameters')
            ->where('s.laboratoryCode','=', $input['scanid'])
            ->where('p.station','=', $id)
            //where('st.status','=', 'Not Started')
            ->update(array('st.status' => 'In Progress','s.managedBy' => Auth::user()->employeeName , 'st.managedBy' => Auth::user()->employeeName, 'timeReceived' => date("Y-m-d H:m:s")));
        
        if( $updateresult > 0 )
            return redirect('/analyst/sample/station/'.$id)->with(['samplereceiveNotif' => true]);
        else
            return redirect('/analyst/sample/station/'.$id)->with(['errorreceiveNotif' => true]);
    }

    public function completeSample($id,Request $request){
        $input = $request->all();

        $updateresult = DB::table('sample__tests AS st')
            ->leftJoin('samples AS s','st.sampleCode','=','s.sampleId')
            ->leftJoin('parameters AS p', 'p.parameterId', '=', 'st.parameters')
            ->where('s.laboratoryCode','=', $input['scanid'])
            ->where('p.station','=', $id)
            ->where('st.status','=', 'In Progress')
            ->update(array('st.status' => 'Completed', 's.managedBy' => Auth::user()->employeeName , 'st.managedBy' => Auth::user()->employeeName, 'st.timecompleted' => now()));

        if( $updateresult > 0 )
            return redirect('/analyst/sample/station/'.$id)->with(['samplecompletedNotif' => true]);
        else
            return redirect('/analyst/sample/station/'.$id)->with(['errorcompletedNotif' => true]);
    }
}
