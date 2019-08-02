<script src="https://js.pusher.com/4.4/pusher.min.js"></script>
<script>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;
    
    // var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
    //     cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
    //     forceTLS: true
    // });

    var pusher = new Pusher('7ba8f3085b7a3b40fc17', {
      cluster: 'ap1',
      forceTLS: true
    });
    // var pusher_event = 'App\Events\SellerRequestsUpdate';
    // var channel = pusher.subscribe('my-channel');
    
    // channel.bind('my-event', function(data) {
    //     alert(JSON.stringify(data));
    // });
</script>