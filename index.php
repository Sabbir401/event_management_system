<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="./assets/logo.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Event Management System</title>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="assets/logo.webp" width="40" height="40" alt="Logo"> Event Manager</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#how-it-works">How It Works</a></li>
                    <li class="nav-item"><a class="nav-link" href="#testimonials">Testimonials</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="./views/register.php">Signup</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="./assets/hero1.jpg" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <a href="./views/register.php"><button class="btn btn-primary">Sign Up</button></a>
                    <p>Some representative placeholder content for the first slide.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="./assets/hero2.jpg" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                <a href="./views/register.php"><button class="btn btn-primary">Sign Up</button></a>
                    <p>Some representative placeholder content for the second slide.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="./assets/hero3.jpg" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                <a href="./views/register.php"><button class="btn btn-primary">Sign Up</button></a>
                    <p>Some representative placeholder content for the third slide.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Features Section -->
    <section id="features" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Why Choose Our Event Management System?</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card h-100 shadow">
                        <div class="card-body">
                            <h5 class="card-title">Seamless Event Creation</h5>
                            <p class="card-text">Plan and create events in just a few clicks, with customizable details.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow">
                        <div class="card-body">
                            <h5 class="card-title">Real-Time Attendee Registration</h5>
                            <p class="card-text">Let your attendees register online and manage their details efficiently.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow">
                        <div class="card-body">
                            <h5 class="card-title">Analytics & Insights</h5>
                            <p class="card-text">Track attendee numbers, view event metrics, and generate reports.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- How It Works Section -->
<section id="how-it-works" class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">How It Works</h2>
        <div class="row g-4">
            <div class="col-md-3 text-center">
                <div class="p-4 border rounded shadow-sm h-100">
                    <img src="assets/signup-icon.png" alt="Sign Up Icon" class="mb-3" width="60">
                    <h4>Sign Up</h4>
                    <p>Create your account in just a few minutes and get started.</p>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="p-4 border rounded shadow-sm h-100">
                    <img src="assets/create-event-icon.png" alt="Create Event Icon" class="mb-3" width="60">
                    <h4>Create Events</h4>
                    <p>Set up all event details like dates, times, venues, and more.</p>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="p-4 border rounded shadow-sm h-100">
                    <img src="assets/manage-attendees-icon.png" alt="Manage Attendees Icon" class="mb-3" width="60">
                    <h4>Manage Attendees</h4>
                    <p>Easily register attendees, make changes, or monitor participation.</p>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="p-4 border rounded shadow-sm h-100">
                    <img src="assets/track-success-icon.png" alt="Track Success Icon" class="mb-3" width="60">
                    <h4>Track Success</h4>
                    <p>Analyze insights and feedback to improve your future events.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section id="testimonials" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">What Our Users Say</h2>
        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div class="text-center p-4 shadow rounded bg-light">
                                <p class="mb-3">"The best event management tool I've ever used! It saved me hours of work and made my events look professional."</p>
                                <figcaption  class="blockquote-footer">Jane D., <cite title="Company">Event Planner</cite></figcaption >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div class="text-center p-4 shadow rounded bg-light">
                                <p class="mb-3">"Simplified my work and provided excellent support. Highly recommended for organizers."</p>
                                <figcaption  class="blockquote-footer">Mark T., <cite title="Company">Corporate Event Manager</cite></figcaption >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div class="text-center p-4 shadow rounded bg-light">
                                <p class="mb-3">"An outstanding platform! The attendee management feature is a game changer for me."</p>
                                <figcaption  class="blockquote-footer">Emily R., <cite title="Company">Community Organizer</cite></figcaption >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</section>


    <!-- About Us Section -->
    <section id="about" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Our Vision</h2>
            <p class="text-center">Our mission is to revolutionize event management by providing a simple, intuitive platform that helps organizers create, manage, and grow their events effortlessly.</p>
        </div>
    </section>

    <!-- Footer -->
    <?php include './views/footer.php'; ?>
</body>

</html>