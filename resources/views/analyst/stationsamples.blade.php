@extends('layouts.analyst_app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ $station->stationName }}
                    <button id="cmpltbtn" type="button" class="btn btn-info btn-lg pull-right analystbtn" style="margin-left: 10px;display:none;" data-toggle="modal" data-target="#completescan">Complete</button>
                    <button id="rcvbtn" type="button" class="btn btn-info btn-lg pull-right analystbtn" data-toggle="modal" data-target="#scanmodal">Receive</button>
                </div>

                @if(Session::get('samplereceiveNotif') !== NULL)
                    <div style="margin: 5px 10px; padding: 5px" class="alert alert-info offset-md-1 col-md-10">
                        <a class="close" data-dismiss="alert">×</a>
                        <strong>Notification:</strong> Successfully Receive Sample
                    </div>
                @endif

                @if(Session::get('samplecompletedNotif') !== NULL)
                    <div style="margin: 5px 10px; padding: 5px" class="alert alert-info offset-md-1 col-md-10">
                        <a class="close" data-dismiss="alert">×</a>
                        <strong>Notification:</strong> Successfully Completed Sample
                    </div>
                @endif
                
                @if(Session::get('errorreceiveNotif') !== NULL)
                    <div style="margin: 5px 10px; padding: 5px" class="alert alert-info offset-md-1 col-md-10">
                        <a class="close" data-dismiss="alert">×</a>
                        <strong>Notification:</strong> Can't receive sample, it is not for this station.
                    </div>
                @endif

                @if(Session::get('errorcompletedNotif') !== NULL)
                    <div style="margin: 5px 10px; padding: 5px" class="alert alert-info offset-md-1 col-md-10">
                        <a class="close" data-dismiss="alert">×</a>
                        <strong>Notification:</strong> Error in completing sample. Sample might not be In Progress or not for this station.
                    </div>
                @endif
                <ul class="nav nav-tabs">
                   <li id="inprogresstab"><a href="#inprogress" data-toggle="tab">In Progress</a></li>
                   <li id="completedtab"><a href="#completed" data-toggle="tab">Completed</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="inprogress">
                        <table id="sampledatainprogress" class="display sampledata table table-hover" style="width:100%">
                            <thead class="thead-light">
                                <tr>
                                    <th class="admin-table">Laboratory Code</th>
                                    <th class="admin-table">Due Date</th>
                                    <th class="admin-table">Status </th>
                                    <th class="admin-table">Time Received</th>
                                    <th class="admin-table">Received</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($inprogresssample))
                                    @foreach($inprogresssample as $data)
                                        <tr>
                                            <td><a href="/analyst/{{ $station->stationId }}/sample/{{ $data->laboratoryCode }}">{{ $data->laboratoryCode }}</a></td>
                                            <td>{{ $data->dueDate }}</td>
                                            <td>{{ $data->status}} </td>
                                            <td>{{ $data->created_at}} </td>
                                            <td>{{ $data->timeReceived}} </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="completed">
                        <table id="sampledatacomplete" class="display sampledata table table-hover" style="width:100%">
                            <thead class="thead-light">
                                <tr>
                                    <th class="admin-table">Laboratory Code</th>
                                    <th class="admin-table">Due Date</th>
                                    <th class="admin-table">Status </th>
                                    <th class="admin-table">Time Completed </th>

                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($completedsample))
                                    @foreach($completedsample as $data)
                                        <tr>
                                            <td><a href="/analyst/{{ $station->stationId }}/sample/{{ $data->laboratoryCode }}">{{ $data->laboratoryCode }}</a></td>
                                            <td>{{ $data->dueDate }}</td>
                                            <td>{{ $data->status}} </td>
                                            <td>{{ $data->timecompleted}} </td>
                                            
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <div id="completescan" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                <h3>Complete?</h3>
                <form action="/analyst/complete/sample/{{ $station->stationId }}" method="post" class="bookingInput">
                    {{ csrf_field() }}
                    <input type="text" id="scanid2" name="scanid" autofocus>
                    <input id="acceptbtn" type="submit" class="accept" value="Proceed">
                </form>
              </div>
            </div>
        </div>
    </div>

    <div id="scanmodal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                <h3>Receive?</h3>
                <form action="/analyst/receive/sample/{{ $station->stationId }}" method="post" class="bookingInput">
                    {{ csrf_field() }}
                    <input type="text" id="scanid" name="scanid" autofocus>
                    <input id="receivebtn" type="submit" class="accept" value="Proceed">
                </form>
              </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#sampledatainprogress').DataTable({
            "order": [[ 4, "desc" ]],
            "columnDefs": [
                {
                    "targets": [ 4 ],
                    "visible": false,
                    "searchable": false
                }
            ]
        });
        $('#sampledatacomplete').DataTable({
            "order": [[ 3, "desc" ]]
        });
        $('#scanid2').focus();
        $('#scanid').focus();

        $('#receivebtn').click(function(e){
            if($('#scanid').val() == ""){
                e.preventDefault();
                alert('Input the laboratory Code');  
            }
        });

        $('#acceptbtn').click(function(e){
            if($('#scanid2').val() == ""){
                e.preventDefault();
                alert('Input the laboratory Code');  
            }
        });
    });

    $("#inprogresstab").click(function(e){
        $("#cmpltbtn").show();
        $("#rcvbtn").hide();
    });

    $("#completedtab").click(function(e){
        $("#rcvbtn").hide();
        $("#cmpltbtn").hide();
    });
</script>
@endsection
