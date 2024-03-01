const stepsIndicators = document.querySelectorAll('.steps .step');
const formContainers = document.querySelectorAll('.form-container');
const nextStepBtns = document.querySelectorAll('.form-container button')

const imagePreviewEl = document.querySelector('.image-preview');
const imagePreviews = document.querySelectorAll('.image-preview');
const imageEl = document.querySelector('.image-preview img');

if (imagePreviews) {
    imagePreviews.forEach((imagePreviewEl)=>{
        imagePreviewEl.addEventListener('click', (event)=>{
            event.stopPropagation();
            imagePreviewEl.classList.contains('active') ? imagePreviewEl.classList.remove('active') : imagePreviewEl.classList.add('active');
        })
    })
} else if (imagePreviewEl) {
    imagePreviewEl.addEventListener('click', ()=>{
        imagePreviewEl.classList.contains('active') ? imagePreviewEl.classList.remove('active') : imagePreviewEl.classList.add('active');
    })
}


const editBtn = document.querySelector('.edit-profile');
const saveBtn = document.querySelector('.save-changes');

const viewOnlyProfile = document.querySelector('.info.view-only');
const editableProfile = document.querySelector('.info.editable');

if (editBtn) {
    editBtn.addEventListener('click', ()=>{
        editBtn.classList.add('hidden');
        saveBtn.classList.remove('hidden');
        viewOnlyProfile.classList.add('hidden');
        editableProfile.classList.remove('hidden');
    })
}

if (saveBtn) {
    saveBtn.addEventListener('click', ()=>{
        saveBtn.classList.add('hidden');
        editBtn.classList.remove('hidden');
        editableProfile.classList.add('hidden');
        viewOnlyProfile.classList.remove('hidden');
    })
}

const detailPreviewBtns = document.querySelectorAll('.open-detail-preview');
const detailPreview = document.querySelector('.details-preview');
const preview = document.querySelector('.details-preview .preview');


if (detailPreview && detailPreviewBtns) {
    detailPreviewBtns.forEach((detailPreviewBtn)=>{
        detailPreviewBtn.addEventListener('click', ()=>{
            detailPreview.classList.remove('hidden');
        })    

    });

    detailPreview.addEventListener('click', ()=>{
        detailPreview.classList.add('hidden');
    })
}

if (preview) {
    preview.addEventListener('click', (event)=>{
        event.stopPropagation();
    })
}

const viewBtns = document.querySelectorAll('.view-btn');
const personalInformation = document.querySelector('.personal-information'); //The window that will show after clicking view button

const userIdEl = document.querySelector('.userId');
const userProfileEl = document.querySelector('.userProfile');
const userFnameEl = document.querySelector('.userFname');
const userLnameEl = document.querySelector('.userLname');
const userEmailEl = document.querySelector('.userEmail');
const userPasswordEl = document.querySelector('.userPassword');
const userTypeEl = document.querySelector('.userType');
const userSexEl = document.querySelector('.userSex');
const userBirthdateEl = document.querySelector('.userBirthdate');

if (viewBtns) {
    viewBtns.forEach((viewBtn)=>{
        viewBtn.addEventListener('click', (event)=>{
            event.stopPropagation();

            const id = viewBtn.querySelector('.idUser').textContent;
            const postData = {
                idUser: id 
            }
            $.ajax({
                url: '../database/fetch_user_info.php', // Path to your PHP script
                type: 'POST',
                data: postData,
                dataType: 'json',
                success: function(data) {
                    personalInformation.classList.remove('hidden');
                    data = data[0];

                    userIdEl.value = data.idUser;
                    userFnameEl.value = data.fname;
                    userLnameEl.value = data.lname;
                    userEmailEl.value = data.email;
                    userPasswordEl.value = data.password;
                    userTypeEl.value = data.userType;
                    userSexEl.value = data.sex;
                    userBirthdateEl.value = data.birthdate;

                    console.log(data); // Output data to console for testing
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        })
    })
}

const cancelBtn = document.querySelector('.cancelBtn');

if (cancelBtn) {
    cancelBtn.addEventListener('click', ()=>{
        personalInformation.classList.add('hidden');
    })
}


//Employer JS
function findWorker() {
    // Get the form data
    var formData = new FormData(document.getElementById('workerForm'));

    // Send a request to a PHP script to fetch a random worker
    fetch('fetch_worker.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(workerData => {
        // Display the fetched worker information
        displayWorker(workerData);
    })
    .catch(error => {
        console.error('Error fetching worker:', error);
    });
}

function displayWorker(workerData) {
    // Update the HTML elements with the fetched worker information
    // Replace the placeholders with actual data from the workerData object

    // Example:
    document.querySelector('.text-box[name="workerName"]').innerText = workerData.name;
    document.querySelector('.text-box[name="workerAge"]').innerText = workerData.age;
    document.querySelector('.text-box[name="workerSex"]').innerText = workerData.sex;
    // ... and so on

    // Show the hidden elements
    document.querySelector('.info').classList.remove('hidden');
    document.querySelector('.find-worker-btn').classList.add('hidden');
    document.querySelector('.find-another-worker-btn').classList.remove('hidden');
}