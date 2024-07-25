const container = document.getElementById("container");
const registerBtn = document.getElementById("register");
const loginBtn = document.getElementById("login");

registerBtn.addEventListener('click', function(){
    container.classList.add('active');
});

loginBtn.addEventListener('click', function(){
    container.classList.remove('active');
});