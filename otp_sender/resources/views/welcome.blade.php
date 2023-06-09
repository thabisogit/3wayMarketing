<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
        
    

        <style>
            body{background-color:#372727}.height-100{height:100vh}.card{width:400px;border:none;height:300px;box-shadow: 0px 5px 20px 0px #d2dae3;z-index:1;display:flex;justify-content:center;align-items:center}.card h6{color:red;font-size:20px}.inputs input{width:40px;height:40px}input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button{-webkit-appearance: none;-moz-appearance: none;appearance: none;margin: 0}.card-2{background-color:#fff;padding:10px;width:350px;height:100px;bottom:-50px;left:20px;position:absolute;border-radius:5px}.card-2 .content{margin-top:50px}.card-2 .content a{color:red}.form-control:focus{box-shadow:none;border:2px solid red}.validate{border-radius:20px;height:40px;background-color:red;border:1px solid red;width:140px}
        </style>
        <script>
            document.addEventListener("DOMContentLoaded", function(event) {
  
            function OTPInput() {
            const inputs = document.querySelectorAll('#otp > *[id]');
            for (let i = 0; i < inputs.length; i++) { inputs[i].addEventListener('keydown', function(event) { if (event.key==="Backspace" ) { inputs[i].value='' ; if (i !==0) inputs[i - 1].focus(); } else { if (i===inputs.length - 1 && inputs[i].value !=='' ) { return true; } else if (event.keyCode> 47 && event.keyCode < 58) { inputs[i].value=event.key; if (i !==inputs.length - 1) inputs[i + 1].focus(); event.preventDefault(); } else if (event.keyCode> 64 && event.keyCode < 91) { inputs[i].value=String.fromCharCode(event.keyCode); if (i !==inputs.length - 1) inputs[i + 1].focus(); event.preventDefault(); } } }); } } OTPInput();

                
            });
        </script>
    </head>
    <body class="antialiased">
        <div class="row col-lg-12">
            <div class="row col-lg-6">
            <form method="GET" action="/proxy/8000/send-otp">
    @csrf
    <div class="container height-100 d-flex justify-content-center align-items-center"> 
        <div class="position-relative"> 
            <div class="card p-2 text-center"> 
                <h6>Please enter your email <br> to request for OTP</h6> 
                <!-- <div> <span>A code has been sent to</span> <small>*******9897</small> 
            </div>  -->
            <input type="text" name="email" class="form-control mt-2" placeholder="enter email address"/>
             
            <div class="row col-sm-12 mt-4">
                <div class="col-sm-6"> <button type="submit" class="btn btn-danger px-4 validate">Send OTP</button></div>
                <div class="col-sm-6"> <button class="btn btn-danger px-4 validate">Validate</button></div>
            </div>
    </div> 
        
    <div class="card-2 mb-0"> 
        <div class="content d-flex justify-content-center align-items-center"> 
            <span>Didn't get the code</span> 
            <button type="submit" class="btn btn-danger px-4 validate">Re-send OTP</button> 
        </div> </div> </div>

</div>
</form>
            </div>

            <div class="col-lg-6">
            <form method="GET" action="/proxy/8000/verify-otp">
    @csrf
    <div class="container height-100 d-flex justify-content-center align-items-center"> 
        <div class="position-relative"> 
            <div class="card p-2 text-center"> 
                <h6>Please enter your email & your <br> one time OTP for verification</h6> 
            
            <input type="text" name="email" class="form-control mt-2" placeholder="enter email address"/>
            <br>
            <input type="hidden" name="otp" id="otp_pin" class="form-control mt-2"/>
            <div id="otp" name="otp_pin" class="inputs d-flex flex-row justify-content-center mt-2"> 
                <input class="m-2 text-center form-control rounded code" type="text" id="first" maxlength="1" /> 
                <input class="m-2 text-center form-control rounded code" type="text" id="second" maxlength="1" /> 
                <input class="m-2 text-center form-control rounded code" type="text" id="third" maxlength="1" /> 
                <input class="m-2 text-center form-control rounded code" type="text" id="fourth" maxlength="1" /> 
                <input class="m-2 text-center form-control rounded code" type="text" id="fifth" maxlength="1" /> 
                <input class="m-2 text-center form-control rounded code" type="text" id="sixth" maxlength="1" /> 
            </div> 
            
            <div class="col-sm-6"> <button class="btn btn-danger px-4 validate">Verify OTP</button></div>
            
    </div> 
        </form>
            </div>
        </div>
    
        <script>
            $(document).ready( function (){
                $('.code').on('blur',function (){
                    $('#otp_pin').val(
                        $('#first').val()+
                        $('#second').val()+
                        $('#third').val()+
                        $('#fourth').val()+
                        $('#fifth').val()+
                        $('#sixth').val()
                        );
                })
            });
        </script>
    </body>
</html>
