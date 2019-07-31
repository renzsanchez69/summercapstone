@extends('layouts.admin_app')

@section('content')

{{-- DECLARING OF COUNTER VARIABLE FOR MULTIPLE MODALS --}}
<?php $count = 0; ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Inventory - Glassware &nbsp; &nbsp;
                <a href="#addItem" id="addNew" class="glyphicon glyphicon-plus" data-toggle="collapse" onclick="changeText()">Add new</a>
                <form class="float-right" action="{{ route('searchItem-admin') }}" method="GET">
                    @csrf
                    <select class="js-example-responsive" id="search" name="search">
                        <option selected>Search Item</option>
                        @foreach ($glasswares as $glassware)
                        <option value="{{ $glassware->itemName }}">{{ $glassware->itemName }}</option>
                        @endforeach
                    </select>
                    <input class="float-right" type="submit" value="Search">
                </form>
                <div id="addItem" @if($errors->any()) class="collapse.show" @else class="collapse" @endif>
                    <div class="card-body">
                        <form method="POST" action="{{ route('addItem-admin') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="itemName" class="col-md-4 col-form-label text-md-right">{{ __('Name of Item') }}</label>
    
                                <div class="col-md-6">
                                    <input id="itemName" type="text" class="form-control{{ $errors->has('itemName') ? ' is-invalid' : '' }}" name="itemName" value="{{ old('itemName') }}" required autofocus>
    
                                    @if ($errors->has('itemName'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('itemName') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="containerType" class="col-md-4 col-form-label text-md-right">{{ __('Container Type') }}</label>
    
                                <div class="col-md-6">
                                    <input id="containerType" type="text" class="form-control{{ $errors->has('containerType') ? ' is-invalid' : '' }}" name="containerType" value="{{ old('containerType') }}" autofocus>
    
                                    @if ($errors->has('containerType'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('containerType') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="volumeCapacity" class="col-md-4 col-form-label text-md-right">{{ __('Volume Capacity') }}</label>
    
                                <div class="col-md-6">
                                    <input id="volumeCapacity" type="number" class="form-control{{ $errors->has('volumeCapacity') ? ' is-invalid' : '' }}" name="volumeCapacity" value="{{ old('volumeCapacity') }}" autofocus>
    
                                    @if ($errors->has('volumeCapacity'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('volumeCapacity') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="quantity" class="col-md-4 col-form-label text-md-right">{{ __('Quantity') }}</label>
    
                                <div class="col-md-6">
                                    <input id="quantity" type="number" class="form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}" name="quantity" value="5" autofocus>
    
                                    @if ($errors->has('quantity'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('quantity') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="supplier" class="col-md-4 col-form-label text-md-right">{{ __('Supplier') }}</label>
                                
                                <div class="col-md-6">
                                    <select name="supplier" id="supplier" class="form-control js-example-basic-single" style="width:100%;" required>
                                        <option value="" selected>Choose a supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->companyName }}">{{ $supplier->companyName }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('supplier'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('supplier') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-secondary">
                                        {{ __('Add Item') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="admin-table">Name of Item</th>
                            <th class="admin-table">Container Type</th>
                            <th class="admin-table">Volume Capacity</th>
                            <th class="admin-table">Quantity</th>
                            <th class="admin-table">Supplier</th>
                            <th class="admin-table">Functions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td class="admin-table">{{ $item->itemName }}</td>
                            <td class="admin-table">{{ $item->containerType }}</td>
                            <td class="admin-table">{{ $item->volumeCapacity }}</td>
                            <td class="admin-table">{{ $item->quantity }}</td>
                            @if ($item->suppliers != NULL)
                                <td class="admin-table">{{ $item->suppliers['companyName'] }}</td>
                            @else
                                <td class="admin-table">NULL</td>    
                            @endif
                            <td>
                                {{-- EDIT BUTTON --}}
                                <a data-toggle="modal" data-target="#editItem{{ $count }}"><i class="fa fa-edit"></i></a>
                                <div id="editItem{{ $count }}" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header editModal">
                                                <h5 class="modal-title">Edit Item</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <form method="POST" action="{{ route('updateItem-admin', [$item->itemId]) }}">
                                                @method('PATCH')
                                                @csrf
                                            <div class="modal-body">
                                                <div class="form-group row">
                                                    <label for="itemName" class="col-md-4 col-form-label text-md-right">{{ __('Name of Item') }}</label>
                        
                                                    <div class="col-md-6">
                                                        <input id="itemName" type="text" class="form-control{{ $errors->has('itemName') ? ' is-invalid' : '' }}" name="itemName" value="{{ $item->itemName }}" >
                        
                                                        @if ($errors->has('itemName'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('itemName') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                        
                                                <div class="form-group row">
                                                    <label for="containerType" class="col-md-4 col-form-label text-md-right">{{ __('Container Type') }}</label>
                        
                                                    <div class="col-md-6">
                                                        <input id="containerType" type="text" class="form-control{{ $errors->has('containerType') ? ' is-invalid' : '' }}" name="containerType" value="{{ $item->containerType }}"  >
                        
                                                        @if ($errors->has('containerType'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('containerType') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                    
                                                <div class="form-group row">
                                                    <label for="volumeCapacity" class="col-md-4 col-form-label text-md-right">{{ __('Volume Capacity') }}</label>
                        
                                                    <div class="col-md-6">
                                                        <input id="volumeCapacity" type="number" class="form-control{{ $errors->has('volumeCapacity') ? ' is-invalid' : '' }}" name="volumeCapacity" value="{{$item->volumeCapacity}}"  >
                        
                                                        @if ($errors->has('volumeCapacity'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('volumeCapacity') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                    
                                                <div class="form-group row">
                                                    <label for="quantity" class="col-md-4 col-form-label text-md-right">{{ __('Quantity') }}</label>
                        
                                                    <div class="col-md-6">
                                                        <input id="quantity" type="number" class="form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}" name="quantity" value="{{ $item->quality }}">
                        
                                                        @if ($errors->has('quantity'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('quantity') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="supplier" class="col-md-4 col-form-label text-md-right">{{ __('Supplier') }}</label>
                                                    
                                                    <div class="col-md-6">
                                                        <select name="supplier" id="supplier" class="form-control js-example-basic-single" style="width:100%;">
                                                            <option value="" selected>No supplier</option>
                                                            @foreach($suppliers as $supplier)    
                                                                <option value="{{ $supplier->companyName }}" @if($item->suppliers != NULL) @if($item->suppliers->companyName == $supplier->companyName) selected @endif @endif>{{ $supplier->companyName }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('supplier'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('supplier') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                                &nbsp;&nbsp; 
                            {{-- DELETE BUTTON --}}
                            <a data-toggle="modal" data-target="#deleteItem{{ $count }}"><i class="fa fa-trash"></i></a>
                            <div id="deleteItem{{ $count }}" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        <div class="modal-header deleteModal">
                                            <h5 class="modal-title">Delete Item</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete {{ $item->itemName }}?</p>                   
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('destroyItem-admin', [$item->itemId])}}" method="post">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>                      
                                        </div>
                                    </div> 
                                </div>
                            </div>
                            </td>
                        </tr>
                        {{-- COUNT INCREMENTS --}}
                        <?php $count++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row justify-content-center mt-2">
                {{ $items->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
