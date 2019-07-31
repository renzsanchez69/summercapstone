@extends('layouts.analyst_app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header "> Inventory History <a class="pull-right" href="{{ Request::server('HTTP_REFERER') }}">Back</a></div>
                <br/>

                <table id="historytable" class="display sampledata" style="width:100%">
                    <thead>
                        <tr>
                            <th>Inventory</th>
                            <th>Date of Use</th>
                            <th>Item Name</th>
                            <th>Container Type</th>
                            <th>Volume Capacity</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $data)
                            <tr>
                                <td>{{ $data->inventoryId }}</td>
                                <td>{{ $data->created_at }}</td>
                                <td>{{ $data->itemName }}</td>
                                <td>{{ $data->containerType }}</td>
                                <td>{{ $data->volumeCapacity }}</td>
                                <td>{{ $data->qty }}</td>
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
        $('#historytable').DataTable();
    });
</script>
@endsection
