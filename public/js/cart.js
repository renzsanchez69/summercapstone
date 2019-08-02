function delete_cart_page_item(id) {
    $(".cart-number").prop('disabled', true);
    $(".cart-page-btn").prop('disabled', true);
    $(".cart-address").prop('disabled', true);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        url: '/cart/delete',
        method: 'post',
        data: {
            product_id : id
        },
        success: function(result) {
            update_cart();
            $('#cart-page-row-'+id).remove();
            $('#cart-page-total').text('K ' + result["product_subtotal"]);
            $('#cart-page-items').text('' + result["cart_items"]);
            $(".cart-number").prop('disabled', false);
            $(".cart-page-btn").prop('disabled', false);
            $(".cart-address").prop('disabled', false);
        },
        error: function (data) {
            console.log("Error: ", data);
            console.log("Errors->", data.errors);
            $(".cart-number").prop('disabled', false);
            $(".cart-page-btn").prop('disabled', false);
            $(".cart-address").prop('disabled', false);
    }});

}

$('.btn-number').click(function(e){
    e.preventDefault();

    fieldName = $(this).attr('data-field');
    type      = $(this).attr('data-type');
    var input = $("input[name='"+fieldName+"']");
    var currentVal = parseInt(input.val());
    if (!isNaN(currentVal)) {
        if(type == 'minus') {

            if(currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            }
            if(parseInt(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }

        } else if(type == 'plus') {

            if(currentVal < input.attr('max')) {
                input.val(currentVal + 1).change();
            }
            if(parseInt(input.val()) == input.attr('max')) {
                $(this).attr('disabled', true);
            }

        }
    } else {
        input.val(0);
    }
});

$('.cart-number').focusin(function(){
   $(this).data('oldValue', $(this).val());
});

$('.cart-number').change(function() {

    var minValue =  parseInt($(this).attr('min'));
    var maxValue =  parseInt($(this).attr('max'));
    var product_id = $( this ).attr('product_id');
    var product_qty = $(this).val();

    $(".cart-number").prop('disabled', true);
    $(".cart-page-btn").prop('disabled', true);
    $(".cart-address").prop('disabled', true);

    name = $(this).attr('name');

    if (product_qty >= minValue) {
        $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled');
    } else {
        $(this).val($(this).data('oldValue'));
    }

    if (product_qty <= maxValue) {
        $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled');

    } else {
        $(this).val($(this).data('oldValue'));
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        url: '/cart/update',
        method: 'post',
        data: {
            product_id : product_id,
            product_qty : product_qty
        },
        success: function(result) {
            update_cart();
            $('#cart-page-total').text('K ' + result["product_subtotal"]);
            $('#cart-page-product-total-'+product_id).text('K ' + result["product_total"]);
            $('#cart-page-items').text('' + result["cart_items"]);
            $(".cart-number").prop('disabled', false);
            $(".cart-page-btn").prop('disabled', false);
            $(".cart-address").prop('disabled', false);
        },
        error: function (data) {
            console.log("Error: ", data);
            console.log("Errors->", data.errors);
            var old_val = data.responseJSON.current_qty;
            alert(data.responseJSON.error);
            document.getElementById("cart-number-"+product_id).value = ""+old_val;
            $(".cart-number").prop('disabled', false);
            $(".cart-page-btn").prop('disabled', false);
            $(".cart-address").prop('disabled', false);
    }});

});

$(".cart-number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
