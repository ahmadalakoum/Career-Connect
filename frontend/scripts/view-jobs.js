document.addEventListener("DOMContentLoaded", async () => {
    const userID = localStorage.getItem("userID");

    if (!userID) {
        window.location.href = "login.html";
        return;
    }

    const jobsTable = document.getElementById("jobsTable");

    async function fetchJobs() {
        try {
            const response = await axios.get(`${config.apiBaseUrl}/employer-jobs`, {
                headers: { "Authorization": `Bearer ${userID}` }
            });

            const jobs = response.data.job;
            jobsTable.innerHTML = "";

            if (jobs.length === 0) {
                jobsTable.innerHTML = `<tr><td colspan="7" class="text-center">No jobs posted yet.</td></tr>`;
                return;
            }

            jobs.forEach(job => {
                const row = document.createElement("tr");

                row.innerHTML = `
                    <td>${job.title}</td>
                    <td>${job.description}</td>
                    <td>${job.salary}</td>
                    <td>${job.category}</td>
                    <td>${job.deadline}</td>
                    <td>${job.location}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editJob('${job.id}')">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteJob('${job.id}')">Delete</button>
                    </td>
                `;

                jobsTable.appendChild(row);
            });
        } catch (error) {
            console.error("Error fetching jobs:", error);
            alert("Failed to load jobs.");
        }
    }

    window.editJob = (jobID) => {
        window.location.href = `edit-job.html?id=${jobID}`;
    };

    window.deleteJob = async (jobID) => {
        if (!confirm("Are you sure you want to delete this job?")) return;

        try {
            await axios.get(`${config.apiBaseUrl}/delete?job_id=${jobID}`, {
                headers: { "Authorization": `Bearer ${userID}` }
            });

            alert("Job deleted successfully!");
            fetchJobs();
        } catch (error) {
            console.error("Error deleting job:", error);
            alert("Failed to delete job.");
        }
    };

    fetchJobs();
});