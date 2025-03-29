document.addEventListener("DOMContentLoaded", async () => {
    const userID = localStorage.getItem("userID");

    if (!userID) {
        window.location.href = 'login.html'; // Redirect to login if not authenticated
        return;
    }

    try {
        // Fetch applied jobs for the user
        const response = await axios.get(`${config.apiBaseUrl}/view-applications`, {
            headers: {
                "Authorization": `Bearer ${userID}`
            }
        });

        const applicationsList = document.getElementById("applicationsList");

        if (response.data.status === "success" && response.data.applications.length > 0) {
            response.data.applications.forEach(application => {
                console.log(application);
                // Create a card for each applied job
                const jobCard = document.createElement("div");
                jobCard.classList.add("card", "mb-3");
                jobCard.setAttribute("data-application-id", application.application_id); // Add a data attribute to the job card
                jobCard.innerHTML = `
                    <div class="card-body">
                        <h5 class="card-title">${application.title}</h5>
                        <p class="card-text">${application.description}</p>
                        <p class="card-text"><strong>Salary:</strong> ${application.salary}</p>
                        <p class="card-text"><strong>Location:</strong> ${application.location}</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#jobDetailModal" data-job-id="${application.id}" data-application-id="${application.application_id}">
                            View Details
                        </button>
                    </div>
                `;
                applicationsList.appendChild(jobCard);
            });
        } else {
            applicationsList.innerHTML = '<p class="text-center">You have not applied to any jobs yet.</p>';
        }

        // Add event listener for opening job details in the modal
        const jobButtons = document.querySelectorAll("[data-bs-target='#jobDetailModal']");
        jobButtons.forEach(button => {
            button.addEventListener("click", async (e) => {
                const applicationID = e.target.getAttribute("data-application-id");
                const jobID = e.target.getAttribute("data-job-id");

                const jobResponse = await axios.get(`${config.apiBaseUrl}/job?job_id=${jobID}`, {
                    headers: {
                        "Authorization": `Bearer ${userID}`
                    }
                });

                if (jobResponse.data.status === "success") {
                    const job = jobResponse.data.job;
                    document.getElementById("jobDetailModalLabel").textContent = job.title;
                    document.getElementById("jobDescription").textContent = job.description;
                    document.getElementById("jobSalary").textContent = job.salary;
                    document.getElementById("jobLocation").textContent = job.location;
                    document.getElementById("jobCategory").textContent = job.category;
                    document.getElementById("jobDeadline").textContent = job.deadline;

                    // Delete button logic
                    const deleteBtn = document.getElementById("deleteBtn");
                    deleteBtn.addEventListener("click", async () => {
                        console.log(applicationID);
                        if (!confirm("Are you sure you want to delete this application?")) return;

                        try {
                            // DELETE request to remove the application
                            const deleteApplication = await axios.get(`${config.apiBaseUrl}/delete-application?id=${applicationID}`, {
                                headers: {
                                    "Authorization": `Bearer ${userID}`
                                }
                            });
                            console.log(deleteApplication);
                            if (deleteApplication.data.status === "success") {
                                alert("Application deleted successfully!");
                                window.location.reload();
                            } else {
                                alert("Failed to delete the application.");
                            }
                        } catch (error) {
                            console.error("Error deleting application:", error);
                            alert("Error deleting application. Please try again.");
                        }
                    });
                }
            });
        });
    } catch (error) {
        console.error("Error fetching applications:", error);
        alert("Error loading your applications. Please try again later.");
    }
});
