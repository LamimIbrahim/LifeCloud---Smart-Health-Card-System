const loginForm = document.getElementById("loginForm");
const loginMsg = document.getElementById("loginMsg");

// Helper to show errors
const showError = (id, msg) => {
    document.getElementById(`err-${id}`).textContent = msg;
};

const clearErrors = () => {
    document.querySelectorAll(".error").forEach(el => el.textContent = "");
};

loginForm.addEventListener("submit", (e) => {
    e.preventDefault();
    clearErrors();
    loginMsg.hidden = true;

    const uid = loginForm.uid.value.trim();
    const password = loginForm.password.value;
    let isValid = true;

    // Basic Validation
    if (!uid) {
        showError("uid", "Health Card or Email is required");
        isValid = false;
    }

    if (!password) {
        showError("password", "Password is required");
        isValid = false;
    }

    if (isValid) {
        // Simulate Login Check
        // Replace this logic with actual backend API call
        if (uid === "admin" && password === "12345678") {
            loginMsg.textContent = "Login Successful! Redirecting...";
            loginMsg.style.color = "green";
            loginMsg.hidden = false;

            // Redirect after 1 second
            setTimeout(() => {
                window.location.href = "dashboard.html"; // Target page
            }, 1000);
        } else {
            loginMsg.textContent = "Invalid credentials. Try again.";
            loginMsg.style.color = "red";
            loginMsg.hidden = false;
        }
    }
});