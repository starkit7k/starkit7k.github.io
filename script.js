const formOpenBtn = document.querySelector("#form-open"),
home = document.querySelector(".home"),
formContainer = document.querySelector(".form_container0"),
formCloseBtn = document.querySelector(".form_close"),
signupBtn = document.querySelector("#signup"),
loginBtn = document.querySelector("#login"),
pwShowHide= document.querySelectorAll(".pw_hide");

formOpenBtn.addEventListener("click", () => home.classList.add("show"))
formCloseBtn.addEventListener("click", () => home.classList.remove("show"))

pwShowHide.forEach((icon) => {
    icon.addEventListener("click", () => {
        let getPeInput = icon.parentElement.querySelector("input");
        if(getPeInput.type === "password"){
            getPeInput.type="text";
            icon.classList.replace("uil-eye-slash", "uil-eye");
        }else{
            getPeInput.type="password";
            icon.classList.replace("uil-eye", "uil-eye-slash");
        }
    });
});

signupBtn.addEventListener("click", (e) =>{
    e.preventDefault();
    formContainer.classList.add("active")
});

loginBtn.addEventListener("click", (e) =>{
    e.preventDefault();
    formContainer.classList.remove("active")
});

// navigation section

function showsidebar(){
    const sidebar = document.querySelector('.navbar')
    sidebar.style.display = 'flex'
}

function closesidebar(){
    const sidebar = document.querySelector('.navbar')
    sidebar.style.display = 'none'
}

