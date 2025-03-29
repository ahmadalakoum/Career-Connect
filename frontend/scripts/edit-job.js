document.addEventListener("DOMContentLoaded", async () => {
    const userID = localStorage.getItem("userID");
    const jobID = new URLSearchParams(window.location.search).get("id");

    if (!userID || !jobID) {
        window.location.href = "login.html"; // Redirect if no user or job ID found
        return;
    }

    const titleInput = document.getElementById("title");
    const descriptionInput = document.getElementById("description");
    const salaryInput = document.getElementById("salary");
    const categoryInput = document.getElementById("category");
    const deadlineInput = document.getElementById("deadline");
    const locationInput = document.getElementById("location");

    try {

        const response = await axios.get(`${config.apiBaseUrl}/job?job_id=${jobID}`, {
            headers: { "Authorization": `Bearer ${userID}` }
        });

        if (response.data.status === "success") {
            const job = response.data.job;

            // Fill form with existing job details
            titleInput.value = job.title;
            descriptionInput.value = job.description;
            salaryInput.value = job.salary;
            categoryInput.value = job.category;
            deadlineInput.value = job.deadline;
            locationInput.value = job.location;
        } else {
            alert("Job not found.");
            window.location.href = "view-jobs.html";
        }
    } catch (error) {
        console.error("Error fetching job details:", error);
        alert("Failed to load job details.");
    }

    // Handle form submission for editing the job
    document.getElementById("editJobForm").addEventListener("submit", async (e) => {
        e.preventDefault();

        const updatedJob = {
            title: titleInput.value,
            description: descriptionInput.value,
            salary: salaryInput.value,
            category: categoryInput.value,
            deadline: deadlineInput.value,
            location: locationInput.value
        };

        try {
            const updateResponse = await axios.put(`${config.apiBaseUrl}/update?job_id=${jobID}`, updatedJob, {
                headers: { "Authorization": `Bearer ${userID}` }
            });

            if (updateResponse.data.status === "success") {
                alert("Job updated successfully!");
                window.location.href = "view-jobs.html"; // Redirect after success
            } else {
                alert("Failed to update job.");
            }
        } catch (error) {
            console.error("Error updating job:", error);
            alert("Error updating job.");
        }
    });
});
