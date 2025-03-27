document.getElementById("signup-form").addEventListener("submit", async (e) => {
    e.preventDefault();

    const username = document.getElementById("username").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();
    const confirmPassword = document.getElementById("confirmPassword").value.trim();
    const role = document.getElementById("role").value.trim();
    const error = document.getElementById("error-message");

    // Clear previous error
    error.classList.add("d-none");
    error.textContent = "";

    // Validate form inputs
    const validationError = validateSignup(username, email, password, confirmPassword, role);
    if (validationError) {
        error.textContent = validationError;
        error.classList.remove("d-none");
        return;
    }

    try {
        const signupURL = `${config.apiBaseUrl}/signup`;
        const response = await axios.post(signupURL, {
            username,
            email,
            password,
            role,
            confirmPassword
        });

        if (response.data.status === "success") {
            window.location.href = "login.html";
        } else {
            error.textContent= response.data.message;
            error.classList.remove("d-none");

        }
    } catch (err) {
        error.textContent = err.message || "An error occurred. Please try again.";
        error.classList.remove("d-none");
    }
});

function validateSignup(username, email, password, confirmPassword, role) {
    if (!username || !email || !password || !confirmPassword || !role) {
        return "All fields are required";
    }
    if (!email.includes("@") || !email.includes(".")) {
        return "Enter a valid email address";
    }
    if (password.length < 6) {
        return "Password must be at least 6 characters long";
    }
    if (password !== confirmPassword) {
        return "Passwords do not match";
    }
    return null; 
}
