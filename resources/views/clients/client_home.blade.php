@extends('layouts.clientapp')


<div class="bg-opacity">
@section('content')
<div class="container">
              <div class="row">
                  <div class="col-md-6 ">
                      <h2 class="titleText">ABOUT US</h2>
                      <br>
                      <p class="text-light containerRow">The University of San Carlos Water Laboratory offers analytical services for water, soil, ore, and other special samples. The Water Laboratory is accredited as a 
                      testing laboratory by the Department of Environment and Natural Resources (DENR) and the Department of Health (DOH).Construction and Materials Testing Laboratory
                      <br><br>
                      The USC Water Laboratory, which accepts third-party analysis and samples from the public for a fee, is attached to the Department of Chemistry.</p>
                  </div>
                  <div class="col-md-6">
                      <h2 class="w3-center titleText">News</h2>
                        <div class="panel panel-primary">
                                <div class="panel-body" >
                                  {!! $calendar_details->calendar() !!}
                                </div>
                        </div>
                  </div>
              </div>
          </div>
    </div>
                                                                   
            
  <!-- Footer -->
<footer class="page-footer font-small indigo text-white footerBar">

<!-- Footer Links -->
<div class="container text-center text-md-left">

  <!-- Grid row -->
  <div class="row">

    <!-- Grid column -->
    <div class="col-md-3 mx-auto">

      <!-- Links -->
      <h5 class="font-weight-bold text-uppercase mt-3 mb-4 ">About</h5>
          <p class="footerText">The Water Laboratory is accredited as a testing laboratory by the Department of Environment and Natural Resources (DENR) and the Department of Health (DOH).</p>
    </div>
    <!-- Grid column -->

    <hr class="clearfix w-100 d-md-none">

    <!-- Grid column -->
    <div class="col-md-3 mx-auto">

      <!-- Links -->
      <h5 class="font-weight-bold text-uppercase mt-3 mb-4 ">Address</h5>

      <p class="footerText">Room 320, 3rd Floor Bunzel Building, University of San Carlos Talamban Campus, 
                  Nasipit Talamban Cebu City Philippines 6000</p>
    </div>
    <!-- Grid column -->

    <hr class="clearfix w-100 d-md-none">

    <!-- Grid column -->
    <div class="col-md-3 mx-auto">

      <!-- Links -->
      <h5 class="font-weight-bold text-uppercase mt-3 mb-4 ">Contact Us </h5>

      <p class="footerText">Email:waterlab@usc.edu.ph<br>
         Phone: (63 32)345 3811<br>
         Fax: (63 32)230 0100 loc 110</p>
    </div>
    <!-- Grid column -->

    <hr class="clearfix w-100 d-md-none">

    <!-- Grid column -->
    <div class="col-md-3 mx-auto">

      <!-- Links -->
      <h5 class="font-weight-bold text-uppercase mt-3 mb-4 ">Feedbacks</h5>

      <p class="footerText">Please send us your ideas, bug reports, suggestions! <br>
                  Any feedback would be appreciated.</p>
      <ul class="list-unstyled">
        <li>
          <a href="{{ url('/contact') }}">Message Us<i class="glyphicon glyphicon-envelope fa-fw "></i></a>
        </li>
      </ul>

    </div>
    <!-- Grid column -->

  </div>
  <!-- Grid row -->

</div>
<!-- Footer Links -->
<h5 class="text-center"  style="font-size:25px; border-radius: 35px;">Follow us:
    <a href="https://www.facebook.com/pages/USC-Water-Laboratory/618035434997379"><i class="fa fa-facebook-official w3-hover-opacity " style=" font-size:25px; border-radius: 35px;"></i></a> 
</h5>
<!-- Copyright -->
<div class="footer-copyright text-center py-3">© 2019 Copyright:
  <a href="#">uscwaterlab.tech</a>- All rights served
</div>
<!-- Copyright -->

</footer>
<!-- Footer -->


              <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
              {!! $calendar_details->script() !!}
@endsection
