<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <section class="vh-100 mt-5">
        <div class="mask d-flex align-items-center h-100 gradient-custom-3">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                        <div class="card" style="border-radius: 15px;">
                            <div class="card-body p-5">
                                <h2 class="text-uppercase text-center mb-5">Create an account</h2>
                                <div class="container mt-3">
                                    <div id="message"></div>
                                </div>

                                <form id="register-form">
                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="username">Username</label>
                                        <input type="text" name="username" id="username" class="form-control form-control-lg" required />
                                    </div>

                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="email">Your Email</label>
                                        <input type="email" name="email" id="email" class="form-control form-control-lg" required />
                                    </div>

                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="password">Password</label>
                                        <input type="password" name="password" id="password" class="form-control form-control-lg" required />
                                    </div>

                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="confirm_password">Repeat your password</label>
                                        <input type="password" name="confirm_password" id="confirm_password" class="form-control form-control-lg" required />
                                    </div>

                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="btn btn-success btn-block btn-lg gradient-custom-4 text-body">Register</button>
                                    </div>

                                    <p class="text-center text-muted mt-5 mb-0">Already have an account? <a href="./login.php"
                                            class="fw-bold text-body"><u>Login here</u></a></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            $("#register-form").on("submit", function(event) {
                event.preventDefault();

                $.ajax({
                    url: "../actions/register_action.php",
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        if (response.status === "success") {
                            $("#message").html('<div class="alert alert-success">' + response.message + '</div>');
                            $("#register-form")[0].reset();
                        } else {
                            $("#message").html('<div class="alert alert-danger">' + response.message + '</div>');
                        }
                    },
                    error: function() {
                        $("#message").html('<div class="alert alert-danger">An error occurred while processing your request.</div>');
                    }
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>