document.getElementById("login-form").addEventListener('submit',async(e)=>{
    e.preventDefault();

    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();
    const error = document.getElementById("error-message");

    // Clear previous error
    error.classList.add("d-none");
    error.textContent = "";

    const validationError = validateLogin( email, password);
    if (validationError) {
        error.textContent = validationError;
        error.classList.remove("d-none");
        return;
    }

    try {
        const loginURL = `${config.apiBaseUrl}/login`;
        const response = await axios.post(loginURL, {
            email,
            password
        });
        if (response.data.status === "success") {
            console.log(response.data.user);
            localStorage.setItem("userID",response.data.user.id);
            localStorage.setItem("username",response.data.user.username);
            localStorage.setItem("role",response.data.user.role);
            window.location.href="index.html";
            
        } else {
            error.textContent= response.data.message;
            error.classList.remove("d-none");

        }
    } catch (err) {
        error.textContent = err.message || "An error occurred. Please try again.";
        error.classList.remove("d-none");
    }

});

function validateLogin(email, password) {
    if (!email || !password) {
        return "All fields are required";
    }
    if (!email.includes("@") || !email.includes(".")) {
        return "Enter a valid email address";
    }
    if (password.length < 6) {
        return "Password must be at least 6 characters long";
    }
    return null; 
}
