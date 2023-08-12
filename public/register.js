document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault();
    var name = document.getElementById('registerName').value;
    var email = document.getElementById('registerEmail').value;
    var password = document.getElementById('registerPassword').value;
    var confirmPassword = document.getElementById('registerConfirmPassword').value;

    console.log('name: ' + name);
    console.log('Email: ' + email);
    console.log('Password: ' + password);
    console.log('confirmPassword: ' + confirmPassword);
    data = {
            name: name,
            email: email,
            password: password,
            password_confirmation: confirmPassword
        };

    fetch("/api/register", {
        credentials: "same-origin",
        mode: "same-origin",
        method: "post",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            name: name,
            email: email,
            password: password,
            password_confirmation: confirmPassword
        })
    })
    .then(response => {
        console.log(response.json());
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        response => response.json()
    })
    .then(result => {
        window.location.href = '/home';
        return response.json()
    })
    .catch((error) => {
        console.error('Error:', error);
        document.getElementById('errors').innerText = error; // Этот блок отображает ошибку на странице
    });
});