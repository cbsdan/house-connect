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
        } else if (formContainer.classList.contains('step3')) {
            formContainers[i+1].classList.remove('hidden');
            stepsIndicators[i+1].classList.add('active');
        } 
    })
})

