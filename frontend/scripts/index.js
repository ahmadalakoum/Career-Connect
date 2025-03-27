document.addEventListener("DOMContentLoaded", () => {
    const navbarNav = document.getElementById("navbarNav");
    const username = localStorage.getItem("username");

    if (username) {
        // If user is logged in, update navbar
        navbarNav.innerHTML = `
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link text-white" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">About Us</a></li>
                <li class="nav-item"><span class="nav-link text-white fw-bold">Welcome ${username}</span></li>
                <li class="nav-item"><button class="btn btn-danger btn-sm ms-2" id="logoutBtn">Logout</button></li>
            </ul>
        `;

        // Logout function
        document.getElementById("logoutBtn").addEventListener("click", () => {
            localStorage.removeItem("userID"); 
            localStorage.removeItem("username"); 
            localStorage.removeItem("role"); 
            window.location.reload(); 
        });
    }
});