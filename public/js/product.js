function add_cart(id, maxQuantity) {

    var options = [];
    $("#addCartModal #product-id").val(id);

    for (i = 0; i < maxQuantity; i++) {
        options.push(`<option value="${i + 1}">${i + 1}</option>`)
    }

    $("#addCartModal #product-qty").html(options.join(""));

    $("#addCartModal #product-qty").val(1);

    $("#addCartModal").modal();
}


function add_cart_detail(id) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    var qty = $('#product-qty').val();
    $.ajax({
        url: '/cart/post',
        method: 'post',
        data: {
            product_id: id,
            product_qty: qty

        },
        success: function (result) {
            update_cart();
        },
        error: function (data) {
            console.log("Error: ", data);
            console.log("Errors->", data.errors);
        }
    });
}

function add_cart_home() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    var qty = $('#product-qty').val();
    var id = $('#product-id').val();

    $.ajax({
        url: '/cart/post',
        method: 'post',
        data: {
            product_id: id,
            product_qty: qty

        },
        success: function (result) {
            update_cart();
        },
        error: function (data) {
            console.log("Error: ", data);
            console.log("Errors->", data.errors);
        }
    });
}

function delete_cart_item(id) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        url: '/cart/delete',
        method: 'post',
        data: {
            product_id: id
        },
        success: function (result) {
            update_cart();

            if ($('#cart-page-row-' + id).length > 0) {
                $('#cart-page-row-' + id).remove();
            }
        },
        error: function (data) {
            console.log("Error: ", data);
            console.log("Errors->", data.errors);
        }
    });

}

function update_cart() {
    $('#cart-list').empty();
    $.ajax({
        type: 'get',
        url: '/cart/retrieve',
        success: function (result) {
            //document.getElementById("total_items").value=response;
            $("#cart-qty").text(result["product_count"]);
            $("#cart-summary-qty").text(result["product_count"]);

            var price = result["product_subtotal"].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            $("#cart-subtotal").text(price);
            $('#cart-list').append(unescape(result["product_html"]));
        },
        error: function (data) {
            console.log("Error: ", data);
            console.log("Errors->", data.errors);
        }
    });

}

function update_seller_requests() {

    $.ajax({
        type: 'get',
        url: '/sellers/retrieve',
        success: function (result) {
            //document.getElementById("total_items").value=response;
            $("#seller-requests").text(result["requests"]);
            $("#product-requests").text(result["productReqests"]);
        },
        error: function (data) {
            console.log("Error: ", data);
            console.log("Errors->", data.errors);
        }
    });

}

$(document).ready(function () {
    update_cart();
    update_seller_requests();
});

