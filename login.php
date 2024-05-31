<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .center-screen {
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100vh;
        }
    </style>
</head>
<body>
    <div class="container center-screen">
        <div class="card mx-auto" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Login</h5>
                <form id="loginForm">
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" aria-describedby="emailHelp">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password">
                    </div>
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Submit</button>
                </form>
                <p id="message" class="mt-3"></p>
            </div>
        </div>
    </div>
    
    
    
    
    
    
    
    <!-- Scripts For All JS Scripts -->   
    <?php require('scripts/php/lib_link_bt.php') ?>
    <script>
        $(document).ready(function() {
            $('#loginForm').on('input', function() {
                var email = $('#email').val();
                var password = $('#password').val();

                if (validateEmail(email) && password.length > 0) {
                    $('#submitBtn').prop('disabled', false);
                } else {
                    $('#submitBtn').prop('disabled', true);
                }
            });

            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                var email = $('#email').val();
                var password = $('#password').val();

                $.ajax({
                    url: 'scripts/php/check_credentials.php',
                    type: 'post',
                    data: {email: email, password: password},
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.debug);
                            window.location.href = 'dashboard.php';
                        } else {
                            $('#message').text(response.message).addClass('text-danger');
                        }
                    }
                });
            });
        });

        function validateEmail(email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }
    </script>
</body>
</html>
