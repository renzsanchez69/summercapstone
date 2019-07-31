<head>
    
    <style type="text/css">
@media print {
    #printbtn {
        display :  none;
    }
}
</style> 
    <style>
div.test1
{
border: 1.5px solid #000;
}
div.div1{
  width: 350px;
  height: 157px;
  border: 2px solid black;
}

div.div2{
  width: 100x;
  height: 157px;  
  border: 2px solid black;
  padding: 10px;
}
h4{
    margin:2px;
}
p{
    margin:2px;
}

.row::after {
    content: "";
    clear: both;
    display: table;
  }
            [class*="col-"] {
                float: left;
                padding: 7px;
                border: 0px;
            }

            
            
            .col-1 {width: 8.33%;}
            .col-2 {width: 16.66%;}
            .col-3 {width: 25%;}
            .col-4 {width: 33.33%;}
            .col-5 {width: 41.66%;}
            .col-6 {width: 50%;}
            .col-7 {width: 58.33%;}
            .col-8 {width: 66.66%;}
            .col-9 {width: 75%;}
            .col-10 {width: 83.33%;}
            .col-11 {width: 91.66%;}
            .col-12 {width: 100%;}
            
        .row{
            
        }
        .col-12{
            padding: 0px;
        }
        .col-4{
            padding: 0px;
        }
        .col-6{
            padding: 0px;
        }
        h2{
            margin-top:60px;
        }
        b.thicker {
            font-weight: 900;
        }
        .header {
            margin: 5px;
        }
        b{
            font-size:13px;
        }
        p{
            font-size:11px;
            font-weight: 500;
            margin: 0px;
        }
        .row{
            margin:0px;
            padding: 0px;
        }
    </style>
</head>
<body>
    
<button id="printbtn" onclick="history.go(-1);">Back </button>
<input id ="printbtn" type="button" value="Print this page" onclick="window.print();">
 @foreach($samples as $p)
    
    <div class="row">
        <div class="col-3">
            <br><br>
            <div>{!! DNS1D::getBarcodeHTML ($p->laboratoryCode, 'C128A',1,60) !!}</div>
            <br>
            <h2 class="header">{{ $p->laboratoryCode}}
            @if($p->remarks == "rush" || $p->remarks == "Rush")
                <br>
                Rush
            @endif
            </h2>        
        </div>
        <div class="col-1"></div>    
        <div class="col-7 div1">
            <p> <b style="font-size:14px">USC WATER LABORATORY  &emsp; &emsp; RIS#:
            @foreach($clients as $client)
                @if($client->clientId == $p->risNumber)
                    {{$client->risNumber}}
                @endif
            @endforeach
             </b> </p>
            <center><p><b>CHAIN OF CUSTODY SLIP</p></center>
            <div class="row">
                <div class="col-6"><p><b>Lab Code:</b> {{ $p->laboratoryCode }}</p></div>
                <div class="col-6"><p><b>Sample Type:</b> {{ $p->sampleType }}</p></div>
            </div>
            <div class="row">
                <p><b> Client's Code:</b> {{ $p->clientsCode }}</p>
            </div>
            <div class="row">
                <p><b>Date Submitted: </b>{{  date("F j, Y g:m A", strtotime($p->created_at)) }}</p>
            </div>
            <div class="row">
                <p><b>Date collected:</b> {{  date("F j, Y g:m A", strtotime($p->sampleCollection)) }}</p>
            </div>
            <div class="row">
                <p><b>Analysis Required:</b> 
                        @foreach($p->parameters as $para)
                        {{$para->analysis}},
                        @endforeach
                </p>
            </div>
            
        </div> 
    </div>
    <br>
    
    @endforeach  
 

    
   
      
   

<script>
function myFunction() {
  window.print();
}
</script>
</html>