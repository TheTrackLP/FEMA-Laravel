<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet" />
    <title>Registration</title>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <style>
        body{
            background-image: linear-gradient(to bottom right, #00AFB9, #FED9B7);
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        label{
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container rounded bg-white mt-5 mb-5">
        <form action="{{route('store.borrower')}}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3 border-right">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right">Personal Information</h4>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6 mb-3">
                                <label class="labels mb-2">First Name:</label>
                                <input type="text" class="form-control" name="firstname" placeholder="First Name">
                                @error('firstname')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="labels mb-2">Middle Name:</label>
                                <input type="text" class="form-control" name="middlename" placeholder="Middle Name">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="labels mb-2">Surname:</label>
                                <input type="text" class="form-control" name="lastname"  placeholder="Surname">
                                @error('lastname')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="labels mb-2">Date of Birth:</label>
                                <input type="date" name="date_birth" id="" class="form-control" name="date_birth">
                                @error('date_birth')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="labels mb-2">Contact #:</label>
                                <input type="text" class="form-control" name="contact_no" placeholder="Contact #">
                                @error('contact_no')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="labels mb-2">Address:</label>
                                <textarea name="address" id="" cols="30" rows="6" class="form-control"></textarea>
                                @error('address')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right">Profile Settings:</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="labels mb-2">Employee ID:</label>
                                <input type="text" class="form-control" name="emp_id" placeholder="Employee ID">
                                @error('emp_id')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="labels mb-2">Shared Capital:</label>
                                <input type="text" class="form-control" name="shared_capital" placeholder="Shared Capital">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="labels mb-2">Department:</label>
                                <input type="text" class="form-control" name="dept_id" placeholder="Department">
                                @error('dept_id')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="labels mb-2">Years in Service:</label>
                                <input type="text" class="form-control" name="years_service" placeholder="additional details">
                                @error('years_service')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="labels mb-2">Username:</label>
                                <input type="text" class="form-control" name="username" placeholder="Username">
                                @error('username')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="labels mb-2">Email:</label>
                                <input type="text" class="form-control" name="email" placeholder="Email">
                                @error('email')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="labels mb-2">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="*******">
                                @error('password')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="labels mb-2">Confirm Password:</label>
                                <input type="password" class="form-control" name="confirm_password" placeholder="*******">
                                @error('confirm_password')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-5 text-center">
                            <button class="btn btn-danger px-4 float-start" type="button">Back</button>
                            <button class="btn btn-success px-4 float-end" type="submit">Register</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
<script>
    @if(Session::has('message'))
    var type = "{{ Session::get('alert-type','info') }}"
    switch(type){
       case 'info':
       toastr.info(" {{ Session::get('message') }} ");
       break;

       case 'success':
       toastr.success(" {{ Session::get('message') }} ");
       break;

       case 'warning':
       toastr.warning(" {{ Session::get('message') }} ");
       break;

       case 'error':
       toastr.error(" {{ Session::get('message') }} ");
       break; 
    }
    @endif
</script>