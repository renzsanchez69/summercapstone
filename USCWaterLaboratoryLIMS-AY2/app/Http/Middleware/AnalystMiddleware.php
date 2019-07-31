<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use App\Sample as Sample;
use App\Station as Station;
use DB;

class AnalystMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->userType != 'analyst') {
            return redirect('/login');
        }
        $stations = Station::all();
        $sampledata = DB::table('samples AS s')
                    ->select('s.laboratoryCode', 's.dueDate', 's.sampleCollection','sta.stationName')
                    ->leftJoin('sample__tests AS st','st.sampleCode','=','s.sampleId')
                    ->leftJoin('parameters AS p', 'p.parameterId', '=', 'st.parameters')
                    ->leftJoin('stations AS sta', 'p.station', '=', 'sta.stationid')
                    ->where('s.dueDate','<',date("Y-m-d",strtotime("+5 day")))
                    ->where(function($query){
                        $query->where('st.status','=', 'In Progress')
                            ->orWhere('st.status','=', 'Not Started');
                    })
                    ->groupBy('s.laboratoryCode', 's.dueDate','s.sampleCollection','sta.stationName')
                    ->distinct()
                    ->get();

        View::share('notifcount', count($sampledata));
        View::share('sampledata', $sampledata);
        View::share('stations', $stations);
        return $next($request);
    }
}
