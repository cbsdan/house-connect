const stepsIndicators = document.querySelectorAll('.steps .step');
const formContainers = document.querySelectorAll('.form-container');
const nextStepBtns = document.querySelectorAll('.form-container button')

formContainers.forEach((formContainer, i)=>{
    const nextBtn = formContainer.querySelector('button');

    nextBtn.addEventListener('click', ()=>{
        !formContainer.classList.contains('hidden') ? formContainer.classList.add('hidden') : '';
        
        stepsIndicators.forEach((stepsIndicator)=>{
            stepsIndicator.classList.remove('active');
        })

        if (formContainer.classList.contains('step1')) {
            formContainers[i+1].classList.remove('hidden');
            stepsIndicators[i+1].classList.add('active');
        } else if (formContainer.classList.contains('step2')) {
            formContainers[i+1].classList.remove('hidden');
            stepsIndicators[i+1].classList.add('active');
        } 
    })
})



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

console.log(detailPreviewBtns);

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
