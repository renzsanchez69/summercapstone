@extends('layouts.base')

@section('content')

<!-- BREADCRUMB -->
<div id="breadcrumb" class="section">
  <!-- container -->
  <div class="container">
    <!-- row -->
    <div class="row">
      <div class="col-md-12">
        <ul class="breadcrumb-tree">
          <li><a href="{{ route('admin') }}">Admin</a></li>
          <li class="active">All Sellers</li>
        </ul>
      </div>
    </div>
    <!-- /row -->
  </div>
  <!-- /container -->
</div>
<!-- /BREADCRUMB -->

<!-- SECTION -->
<div class="section">
  <!-- container -->
  <div class="container">

    <div class="row">
      <div class="col-sm">
        <h3 class="title">Sellers</h3>
      </div>
    </div>

    <!-- row -->
    <div class="row">

      <!-- STORE -->
      <div id="store" class="col-sm">

        <!-- sellers table -->
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name</th>
              <th scope="col">Location</th>
              <th scope="col">Products Sold</th>
              <th scope="col">Stars</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
          @foreach ($sellers as $seller)
            <tr>
              <th scope="row">{{ $seller->id }}</th>
              <td>
                <a href="{{ route('adminSellersDetails', ['id' => $seller->id]) }}">
                  <?php echo $seller->user['name'] ?>
                </a>
                @if (empty($seller->read_at))
                  <span class="badge badge-secondary">New</span>
                @endif
              </td>
              <td>{{ $seller->location }}</td>
              <td>{{ $seller->products_sold }}</td>
              <td>{{ $seller->stars }}</td>
            @if ($seller->status == 0)
              <td>For Review</td>
            @elseif ($seller->status == 1)
              <td>Active</td>
            @elseif ($seller->status == -1)
              <td>Inactive</td>
            @elseif ($seller->status == -2)
              <td>Disapproved</td>
            @endif
            </tr>
          @endforeach
          </tbody>
        </table>

        <!-- Next and Previous links -->
        <div class="row text-center">
          {{ $sellers->links() }}
        </div>

      </div>
      <!-- /STORE -->
    </div>
    <!-- /row -->
  </div>
  <!-- /container -->
</div>
<!-- /SECTION -->

@endsection

@section('scripts')
  @if (Auth::check())
    @if (Auth::user()->isAdmin())
      @include('layouts.pusher')
      <script>
        var channel = pusher.subscribe('sellerRequests');
        channel.bind('sellerRequestsEvent', function(data) {
          location.reload();
        });
      </script>
    @endif
  @endif
@endsection