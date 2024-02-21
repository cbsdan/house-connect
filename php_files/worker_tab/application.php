<main class='worker'>
    <div class='container application'>
        <div class='content'>
            <div class='steps-container'>
                <div class='guideline'></div>
                <div class='steps'>
                    <div class="step 1 active">
                        <p>Step 1</p>
                        <span class='label'>Personal Information</span>
                    </div>
                    <div class="step 2">
                        <p>Step 2</p>
                        <span class='label'>Documents Submission</span>
                    </div>
                    <div class="step 3">
                        <p>Step 3</p>
                        <span class='label'>Documents Verification</span>
                    </div>
                    <div class="step 4">
                        <p>Step 4</p>
                        <span class='label'>Payment</span>
                    </div>
                </div>
            </div>
            <div class='form-container'>
                <div class='title'>
                    <img src='./img/user-icon.png'>
                    <h3>Personal Information</h3>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class='left'>
                        <div class='input-container'>
                            <label for="selectWorkType">Select Work Type</label>
                            <select name="workType" id='selectWorkType'>
                                <option value="Nanny">Nanny</option>
                                <option value="Cook">Cook</option>
                                <option value="Maid">Maid</option>
                                <option value="Gardener">Gardener</option>
                                <option value="Driver">Driver</option>
                            </select>
                        </div>  
                        <div class='input-container'>
                            <label for="inputYrsOfExp">Years of Experience</label>
                            <input id='inputYrsOfExp' name='yearsOfExperience' type="number" min=0 max=100 required>
                        </div>  
                        <div class='input-container'>
                            <label for="inputHeight">Height (cm)</label>
                            <input id='inputHeight' name='height' type="number" min=0 max=1000 required>
                        </div>  
                        <div class='input-container'>
                            <label for="imageUpload">Choose an image to upload</label>
                            <input type="file" id="imageUpload" name="profilePic" accept="image/jpeg, image/png, image/jpg" >
                        </div>  
                    </div>
                    <button class='right' type='submit'>NEXT</button>
                </form>
            </div>
        </div>
    </div>
</main>