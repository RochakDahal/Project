document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const checkoutForm = document.getElementById('checkoutForm');

    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            const identifier = document.getElementById('identifier').value;
            const password = document.getElementById('password').value;
            if (!identifier || !password) {
                e.preventDefault();
                alert('Please fill in all fields');
            }
        });
    }

    if (registerForm) {
        registerForm.addEventListener('submit', (e) => {
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!username || !emailRegex.test(email) || password.length < 6) {
                e.preventDefault();
                alert('Please provide a valid username, email, and password (minimum 6 characters)');
            }
        });
    }

    if (checkoutForm) {
        checkoutForm.addEventListener('submit', (e) => {
            const paymentMethod = document.getElementById('payment_method').value;
            if (!paymentMethod) {
                e.preventDefault();
                alert('Please select a payment method');
            }
        });
    }
});