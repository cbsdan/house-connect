<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Connect</title>
    <link rel="icon" href="./img/favicon.ico" type="image/x-icon">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="./styles/variables.css">
    <link rel="stylesheet" href="./styles/home.css">
    <link rel="stylesheet" href="./styles/includes.css">
    <link rel="stylesheet" href="./styles/media_query.css">
    <link rel="stylesheet" href="./styles/default.css">

    <link rel="stylesheet" href="./styles/worker_styles/application.css">
    <link rel="stylesheet" href="./styles/worker_styles/default.css">

    <!-- JavaScript -->
    <script src='./scripts/worker.js' defer></script>

</head>
<body>
    <header class='logged-in'>
        <div class='content logged-in'>
            <div class='top'>
                <a href="./index.php" class='logo-container'>
                    <img src="./img/logo.png" id='logo'>
                    <h4>HOUSE CONNECT</h4>
                </a>
            </div>
            <div class='bottom empty'>
                <div class="navigation-container">
                    <nav class='w-100'>
                        <a href='#' class='c-light m-l-auto'>LOG OUT</a>
                    </nav>
                </div>
            </div>
            <div class='bottom hidden' id='nav-worker'>
                <div class='navigation-container'>
                    <nav >
                        <a href='#' class='c-light'>CURRENT EMPLOYER</a>
                        <a href='#' class='c-light'>WORK HISTORY</a>
                        <a href='#' class='c-light'>SALARY PAYMENT</a>
                        <a href='#' class='c-light'>ACCOUNT PROFILE</a>
                    </nav>
                    <nav>
                        <a href='#' class='c-light'>LOG OUT</a>
                    </nav>
                </div>
            </div>
            <div class='bottom hidden' id='nav-employer'>
                <div class='navigation-container'>
                    <nav>
                        <a href='#' class='c-light'>NAV NAME</a>
                    </nav>
                    <nav>
                        <a href='#' class='c-light'>LOG OUT</a>
                    </nav>
                </div>
            </div>
        </div>
    </header>
