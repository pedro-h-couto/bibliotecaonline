const API_URL = 'http://localhost:8080';

function getToken() {
    return localStorage.getItem('token');
}

function logout() {
    localStorage.removeItem('token');
    window.location.href = 'login.html';
}

function checkAuth() {
    const token = getToken();
    if (!token) {
        window.location.href = 'login.html';
    }
}

function authHeaders() {
    return {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + getToken()
    };
}

function showMessage(id, text, type) {
    const el = document.getElementById(id);
    if (el) {
        el.textContent = text;
        el.className = type;
        setTimeout(() => { el.className = ''; el.textContent = ''; }, 5000);
    }
}

if (!window.location.pathname.includes('login.html')) {
    checkAuth();
}
