@extends('layouts.analyst_app')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header ">Inventory
                    <a class="btn btn-secondary btn-lg pull-right analystbtn"  style="margin-left: 10px;" href="/analyst/inventory/history">View History</a>
                    <button type="button" class="btn btn-secondary btn-lg pull-right analystbtn" data-toggle="modal" data-target="#myModal">Transact</button>
                </div>
                <br>
                <table id="sampledata" class="display sampledata table table-hover" style="width:100%">
                    <thead class="thead-light">
                        <tr>
                            <th class="admin-table">Item Id</th>
                            <th class="admin-table">Item Type</th>
                            <th class="admin-table">Container</th>
                            <th class="admin-table">Volume</th>
                            <th class="admin-table">Quantity</th>
                            <th class="admin-table">To use</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td><input name="itemid[]" value="{{ $item->itemId }}" hidden>{{ $item->itemId }}</td>
                                <td>{{ $item->itemName }}</td>
                                <td>{{ $item->containerType }}</td>
                                <td>{{ $item->volumeCapacity }}</td>
                                <td class="qty">{{ $item->quantity }}</td>
                                <td><input class="qtyinput" type="number" name="borrowqty[]" min="0" max="{{ $item->quantity }}" value="0"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>




<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            
          </div>
          <div class="modal-body">
            <h3>Proceed with the transaction?</h3>
            <form action="{{ route('inventoryupdate') }}" method="post" class="bookingInput">
                {{ csrf_field() }}
                <input id="ids" name="itemid" hidden>
                <input id="quantities" name="borrowqty" hidden>
                <input type="submit" class="accept" value="Proceed">
            </form>
          </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#sampledata').DataTable();

        $("#myModal").on('show.bs.modal', function(e) {
            var quantity = $("input[name='borrowqty[]']").map(function(){return $(this).val();}).get();
            var ids = $("input[name='itemid[]']").map(function(){return $(this).val();}).get();
            $('#ids').val(ids);
            $('#quantities').val(quantity);
        });

        $(".qtyinput").change(function(){
            var siblingqty = $(this).closest("td").siblings(".qty").text(); //get the quantity of the row
            // alert(siblingqty);
            if(parseInt(siblingqty) < parseInt($(this).val())){
                alert("Input Value must be lower the current quantity");
                $(this).val(0);
            }
        });
    });
    /*$("#transact").click(function(){
        var quantity = $("input[name='borrowqty[]']").map(function(){return $(this).val();}).get();
        var ids = $("input[name='itemid[]']").map(function(){return $(this).val();}).get();
        alert(quantity);

        $.ajax({
            type: "POST",
            url: "/analyst/inventory/update",
            dataType:"json",
            data: { quantity : quantity, ids: ids },
            success: function(res){
                console.log(JSON.stringify(res));
            },
            error: function(res){
                console.log("error" + JSON.stringify(res));
            }
        });*/
        
        /*var form= $('<form action="/analyst/inventory/update" method="POST">' + 
            '<input type="hidden" name="itemids[]" value="' + ids + '">' +
            '<input type="hidden" name="qtys[]" value="' + quantity + '">' +
        '</form>').submit();
        $(document.body).append(form);

    })*/
</script>
@endsection
