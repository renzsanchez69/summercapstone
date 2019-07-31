@extends('layouts.clientapp')

@section('content')


<div class="w3-container" id="contact">
      <!--Section heading-->
    <h3 class="w3-center">
        CONTACT
    </h3>
      <!--Section description-->
    <p class="w3-center w3-large">
        Lets get in touch. Send us a message:
    </p>
  <div class="row">
    <!--Grid column-->
      <div class="col-lg-6">
        <!--Form with header-->
          <div class="card ">
              <div class="card-body">
                  <!--Header-->
                    <div class="form-header w3-center">
                        <h2>
                          <i class="fa fa-pencil"></i> 
                            Write to us:
                        </h2>
                    </div>
                        <p class="w3-center">Got a question? We'd love to hear from you.</p>
                        <p class="w3-center">Send us a message and we'll respond as soon as possible</p>
                          <!--Body-->
                        <form id="contact" method="post" action="{{ route('contact.store')}}">
                          @csrf
                          @if (Session::has('flash_message'))
                            <div class="alert alert-success">{{ Session::get('flash_message') }}</div>
                          @endif
                    <div class="lg-form">
                        <i class="fa fa-user"></i>
                        <label for="form-name">Your name</label>
                        <input type="text" class="form-control" name="name" required>
                          @if ($errors->has('name'))
                            <small class="form-text invalid-feedback">{{ $errors->first('name') }}</small>
                          @endif
                    </div>
                    <div class="lg-form">
                        <i class="fa fa-envelope"></i>
                        <label for="form-email">Your email</label>
                        <input type="email" class="form-control" name="email" required>
                          @if ($errors->has('email'))
                            <small class="form-text invalid-feedback">{{ $errors->first('email') }}</small>
                          @endif
                    </div>
                    <div class="lg-form">
                        <i class="fa fa-comment"></i>
                        <label for="form-Subject">Message</label>
                        <textarea name="message" class="form-control" maxlenght="120" required></textarea>
                          @if ($errors->has('message'))
                            <small class="form-text invalid-feedback">{{ $errors->first('message') }}</small>
                          @endif        
                    </div>
                        <button class="w3-button w3-info" type="submit">
                          <i class="glyphicon glyphicon-send"></i> SEND MESSAGE
                        </button>
                        <br>
                      @if(Session::has('flash_feedback_added'))
                          <div class="alert alert-success col-md-12" id="flash-msg">
                              <a class="close" data-dismiss="alert">×</a>
                              <strong><i class="fa fa-check-circle-o" style="font-size:25px;"></i>&nbsp{!!Session::get('flash_feedback_added')!!}</strong> 
                          </div>
                      @endif
             </div>
          </div>
      </div>
      
      <div class="col-lg-6 text-center ">
        <!--Google map-->
          <img class="img-responsive img-fluid rounded mx-auto d-block" src="{{ asset('img/map.PNG') }}" style="border: 1px solid rgba(0, 0, 0, 0.125);">   
        <br>
        <!--Buttons-->
        <div class="row text-center">
            <div class="col-lg-4">
                <i class="glyphicon glyphicon-map-marker fa-fw w3-xlarge w3-text-red"></i>
                <address style="font-size: 11px;">
                  Room 320, 3rd Floor Bunzel Building,
                  University of San Carlos Talamban Campus, Nasipit Talamban
                  Cebu City Philippines 6000
                </address>
            </div>
            <div class="col-lg-3">
                <i class="glyphicon glyphicon-earphone fa-fw w3-xlarge w3-text-red"></i>
                <p style="font-size: 11px;">(63 32)345 3811<br>
                Mon-Fri 7:30AM-4:30PM<br>
                Sat 7:30AM-11:30AM</p>
            </div>
            <div class="col-lg-2">
                <i class="glyphicon glyphicon-phone fa-fw w3-xlarge w3-text-red"></i>
                <p style="font-size: 11px;">(63 32) 230 0100
                loc 110</p>
            </div>
            <div class="col-lg-3">
                <i class="glyphicon glyphicon-envelope fa-fw w3-xlarge w3-text-red"></i>
                <p style="font-size: 13px;">waterlab@usc.edu.ph</p>
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
  <a href="{{ url('/') }}">uscwaterlab.tech</a>- All rights served
</div>
<!-- Copyright -->

</footer>
<!-- Footer -->
  @endsection