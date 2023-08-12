document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    var email = document.getElementById('loginEmail').value;
    var password = document.getElementById('loginPassword').value;

    console.log('Email: ' + email);
    console.log('Password: ' + password);

    fetch("/api/login", {
        credentials: "same-origin",
        mode: "same-origin",
        method: "post",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            email: email,
            password: password
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