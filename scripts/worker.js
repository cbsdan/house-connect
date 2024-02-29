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


const displayUsersBtn = document.querySelector('.nav.display-users');
const verifyUsersBtn = document.querySelector('.nav.verify-users');

const userAccounts = document.querySelector('.table-result.user-accounts')
const verifyAccounts = document.querySelector('.table-result.verify-users')

if (displayUsersBtn) {
    displayUsersBtn.addEventListener('click', ()=>{
        displayUsersBtn.classList.add('fw-bold');
        verifyUsersBtn.classList.remove('fw-bold');
        userAccounts.classList.remove('hidden');
        verifyAccounts.classList.add('hidden');
    })
}

if (verifyUsersBtn) {
    verifyUsersBtn.addEventListener('click', ()=>{
        verifyUsersBtn.classList.add('fw-bold');
        displayUsersBtn.classList.remove('fw-bold');
        verifyAccounts.classList.remove('hidden');
        userAccounts.classList.add('hidden');
    })
}