document.addEventListener("DOMContentLoaded", () => {
    const navbarNav = document.getElementById("navbarNav");
    const username = localStorage.getItem("username");
    const role = localStorage.getItem("role");

    if (username) {
        // If user is logged in, update navbar
        navbarNav.innerHTML = `
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link text-white" href="index.html">Home</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="">About Us</a></li>
                <li class="nav-item"><a href='profile.html' class="nav-link text-white fw-bold">Welcome ${username}</a></li>
                <li class="nav-item"><button class="btn btn-danger btn-sm ms-2" id="logoutBtn">Logout</button></li>
            </ul>
        `;
        if(role ==="employer"){
            navbarNav.innerHTML=`
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link text-white" href="index.html">Home</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="view-jobs.html">View Jobs</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="add-job.html">Add Job</a></li>
                <li class="nav-item"><a href='profile.html' class="nav-link text-white fw-bold">Welcome ${username}</a></li>
                <li class="nav-item"><button class="btn btn-danger btn-sm ms-2" id="logoutBtn">Logout</button></li>
            </ul>
            `
        }else{
            navbarNav.innerHTML=`
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link text-white" href="index.html">Home</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="jobs.html">View Jobs</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="applications.html">My applications</a></li>
                <li class="nav-item"><a href='profile.html' class="nav-link text-white fw-bold">Welcome ${username}</a></li>
                <li class="nav-item"><button class="btn btn-danger btn-sm ms-2" id="logoutBtn">Logout</button></li>
            </ul>
            `
        }

        // Logout function
        document.getElementById("logoutBtn").addEventListener("click", () => {
            localStorage.removeItem("userID"); 
            localStorage.removeItem("username"); 
            localStorage.removeItem("role"); 
            window.location.reload(); 
        });
    }
});