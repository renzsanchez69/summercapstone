@extends('layouts.admin_app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    Inventory History
                </div>
                @if ($lists->count() == 0)
                    <div class="alert alert-info m-0" role="alert">
                        <p>There are no records of glass wares being used.</p>
                    </div>
                @else
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="admin-table">Date of Use</th>
                                <th class="admin-table">Item Used</th>
                                <th class="admin-table">Container Type</th>
                                <th class="admin-table">Volume Capacity</th>
                                <th class="admin-table">Used By</th>
                                <th class="admin-table">Used Amount</th>
                                <th class="admin-table">Remaining Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lists as $list)
                            <tr>
                                <td class="admin-table">{{ date("F j, Y g:m A", strtotime($list->updated_at)) }}</td>
                                <td class="admin-table">{{ $list->item->itemName }}</td>
                                <td class="admin-table">{{ $list->item->containerType }}</td>
                                <td class="admin-table">{{ $list->item->volumeCapacity }}</td>
                                <td class="admin-table">{{ $list->inventories->user->employeeName }}</td>
                                <td class="admin-table">{{ $list->qty }}</td>
                                <td class="admin-table">{{ $list->item->quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>      
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
