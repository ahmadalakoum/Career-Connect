document.addEventListener("DOMContentLoaded", async () => {
    const userID = localStorage.getItem("userID");

    if (!userID) {
        window.location.href = 'login.html'; // Redirect to login if not authenticated
        return;
    }

    try {
        // Fetch available jobs
        const response = await axios.get(`${config.apiBaseUrl}/jobs`, {
            headers: {
                "Authorization": `Bearer ${userID}`
            }
        });

        const jobsList = document.getElementById("jobsList");
        if (response.data.status === "success" && response.data.jobs.length > 0) {
            response.data.jobs.forEach(job => {
                // Create a card for each job
                const jobCard = document.createElement("div");
                jobCard.classList.add("card", "mb-3");
                jobCard.innerHTML = `
                    <div class="card-body">
                        <h5 class="card-title">${job.title}</h5>
                        <p class="card-text">${job.description}</p>
                        <p class="card-text"><strong>Salary:</strong> ${job.salary}</p>
                        <p class="card-text"><strong>Location:</strong> ${job.location}</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#jobModal" data-job-id="${job.id}">
                            View Details
                        </button>
                    </div>
                `;
                jobsList.appendChild(jobCard);
            });
        } else {
            jobsList.innerHTML = '<p class="text-center">No jobs available at the moment.</p>';
        }

        // Add event listeners for opening job details in a modal
        const jobButtons = document.querySelectorAll("[data-bs-target='#jobModal']");
        jobButtons.forEach(button => {
            button.addEventListener("click", async (e) => {
                const jobID = e.target.getAttribute("data-job-id");
                console.log(jobID);
                const jobResponse = await axios.get(`${config.apiBaseUrl}/job?job_id=${jobID}`, {
                    headers: {
                        "Authorization": `Bearer ${userID}`
                    }
                });

                if (jobResponse.data.status === "success") {
                    const job = jobResponse.data.job;
                    document.getElementById("modalTitle").textContent = job.title;
                    document.getElementById("modalDescription").textContent = job.description;
                    document.getElementById("modalSalary").textContent = job.salary;
                    document.getElementById("modalCategory").textContent = job.category;
                    document.getElementById("modalLocation").textContent = job.location;
                    document.getElementById("modalDeadline").textContent = job.deadline;

                    // Handle Apply Button
                    const applyBtn = document.getElementById("applyBtn");
                    applyBtn.addEventListener("click", async () => {
                        const resumeFile = document.getElementById("resumeUpload").files[0];

                        if (!resumeFile) {
                            alert("Please upload your resume before applying.");
                            return;
                        }
                        const formData = new FormData();
                        formData.append("resume", resumeFile);
                        const applyResponse = await axios.post(`${config.apiBaseUrl}/apply?job_id=${job.id}`,formData, {
                            headers: {
                                "Authorization": `Bearer ${userID}`,
                                 "Content-Type": "multipart/form-data"
                            }
                        });

                        if (applyResponse.data.status === "success") {
                            alert("Successfully applied for the job!");
                            const jobModal = document.getElementById('jobModal');
                            const modalInstance = bootstrap.Modal.getInstance(jobModal);
                            modalInstance.hide();
                        } else {
                            alert("Failed to apply for the job.");
                        }
                    });
                }
            });
        });

    } catch (error) {
        console.error("Error fetching jobs:", error);
        alert("Error loading jobs. Please try again later.");
    }
});