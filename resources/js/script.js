document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    var email = document.getElementById('loginEmail').value;
    var password = document.getElementById('loginPassword').value;

    console.log('Email: ' + email);
    console.log('Password: ' + password);
    // Здесь выполните авторизацию пользователя
});

document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault();
    var email = document.getElementById('registerEmail').value;
    var password = document.getElementById('registerPassword').value;

    console.log('Email: ' + email);
    console.log('Password: ' + password);
    // Здесь зарегистрируйте нового пользователя
});