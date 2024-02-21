<main class='registration'>
    <div class='container'>
        <div class='bg-container'>
            <img src='./img/registration-bg.jpg' alt='Maid Background'>
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
                            <label>Full Name</label>
                            <div class='two-inputs'>
                                <input type='text' name='fname' placeholder='First Name' required> 
                                <input type='text' name='lname' placeholder='Last Name' required> 
                            </div>
                        </div>
                        <div class='input'>
                            <label>Sex</label>
                            <div class='two-inputs'>
                                <label><input type='radio' name='sex' value='Male' checked required> Male</label>
                                <label><input type='radio' name='sex' value='Female' required> Female</label>
                            </div>
                        </div>
                        <div class='input'>
                            <label>Birthdate</label>
                            <input type='date' name='birthdate' required> 
                        </div>
                        <div class='input'>
                            <label>Contact Number</label>
                            <input type='number' name='contact-no' required> 
                        </div>
                        <div class='input'>
                            <label>Address</label>
                            <input type='text' name='address' required> 
                        </div>
                        <div class='input'>
                            <label>Email</label>
                            <input type='email' name='email' required> 
                        </div>
                        <div class='input'>
                            <label>Password</label>
                            <input type='password' name='password' required> 
                        </div>
                        <div class='input'>
                            <label>User Type</label>
                            <div class='two-inputs'>
                                <label><input type='radio' name='sex' value='Worker' checked required> Worker</label>
                                <label><input type='radio' name='sex' value='Employer' required> Employer</label>
                            </div>
                        </div>
                    </div>
                    <button class='orange-white-btn' type='submit' name='submit' value='1'>Register Account</button>
                    <a class='orange-text' href='./login.php'>Already have an account</a>
                </form>
            </div>
        </div>
    </div>
</main>