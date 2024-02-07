<main class='login'>
    <div class='container'>
        <div class='bg-container'>
            <img src='./img/login-bg.png' alt='Maid Background'>
        </div>
        <div class='content-container'>
            <div class="content">
                <div class='logo-container'>
                    <a href="./index.php">
                        <img src="./img/logo.png" id='logo'>
                        <h4>HOUSE CONNECT</h4>
                    </a>
                </div>
                <h2>Welcome to House Connect!</h2>
                <form action='' method='POST'>
                    <div class='input-container'>
                        <div class='input'>
                            <label>Email</label>
                            <input type='email' name='email' required> 
                        </div>
                        <div class='input'>
                            <label>Password</label>
                            <input type='password' name='password' required> 
                        </div>
                    </div>
                    <button class='orange-white-btn' type='submit' name='submit' value='1' style='padding: 0.5rem 3rem'>LOG IN</button>
                    <span class='label'>Don't have an account? <a class='orange-text' href='./registration.php'>Register now</a></span>
                </form>
            </div>
        </div>
    </div>
</main>