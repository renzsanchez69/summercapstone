@extends('layouts.analyst_app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header ">Samples
                </div>
                <br>
                <table id="sampledata" class="display sampledata table table-hover" style="width:100%">
                    <thead class="thead-light">
                        <tr>
                            <th class="admin-table">Due Date</th>
                            <th class="admin-table">Date Received</th>
                            <th class="admin-table">Laboratory Code</th>
                            <th class="admin-table">Collection Time</th>
                            <th class="admin-table">Purpose of Analysis</th>
                            <th class="admin-table">Station</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sampledatas as $data)
                            <tr>
                                <td>{{ $data->dueDate }}</td>
                                <td>{{ $data->timeReceived}}</td>
                                <td>{{ $data->laboratoryCode }}</td>
                                <td>{{ $data->sampleCollection }}</td>
                                <td>{{ $data->purposeOfAnalysis }}</td>
                                <td>{{ $data->stationName }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#sampledata').DataTable(); // to use the datatable jquery (datables.min.js)
    });
</script>
@endsection
