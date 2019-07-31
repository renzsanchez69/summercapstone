<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Event;

use Calendar;

use DateTime;

class EventsController extends Controller
{
    public function index(){
    	$events = Event::get();
    	$event_list = [];
    	foreach ($events as $key => $event) {
    		$event_list[] = Calendar::event(
                $event->event_name,
                false,
                new \DateTime($event->start_date),
                new \DateTime($event->end_date ),
                $key,
                [
                    'color' => '#1E91D6',
                ]
            );
        }
        
    	$calendar_details = Calendar::addEvents($event_list)->setCallbacks([
            ])->setOptions([
            'header' => [
                'right' => ' month agendaDay list  ',
                'center' => 'title',
                'left' => ' prev,next today',
            ],
            'defaultView' => 'month',
            "eventLimit" => 3,
            "allDay" => false,
        ]);
        return view('clients.client_home')->with(compact('calendar_details'));
    }
}