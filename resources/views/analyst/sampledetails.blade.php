@extends('layouts.analyst_app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header ">Samples
                    <a class="pull-right" href="{{ Request::server('HTTP_REFERER') }}">Back</a>
                </div> 
                <br>
                <table id="sampledata" class="display sampledata table table-hover" style="width:100%">
                    <thead class="thead-light">
                        <tr>
                            <th class="admin-table">Laboratory Code </th>
                            <th class="admin-table">Parameters</th>
                            <th class="admin-table">Collection Time</th>
                            <th class="admin-table">Date Received</th>
                            <th class="admin-table">Status</th>
                            <th class="admin-table">Date and Time Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($details as $data)
                            <tr>
                                <td>{{ $data->laboratoryCode}} </td>
                                <td>{{ $data->analysis }}</td>
                                <td>{{ $data->sampleCollection }}</td>
                                <td>{{ $data->created_at}} </td>
                                <td>{{ $data->status }}</td>
                                <td>{{ $data->timecompleted }}</td>
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
        $('#sampledata').DataTable();
    });
</script>
@endsection
