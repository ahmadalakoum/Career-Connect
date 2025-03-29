document.addEventListener("DOMContentLoaded",async ()=>{
    //check if user is authenticated
    const userID= localStorage.getItem("userID");
    if(!userID){
        window.location.href='login.html';
    }
    const profileBaseUrl= `${config.apiBaseUrl}/user/profile`;
    const response = await axios.get(profileBaseUrl,{
        headers:{
            "Authorization":`Bearer ${userID}`
        }
    });
    console.log(response.data);
    const username = document.getElementById("username");
    const email = document.getElementById("email");
    const role = document.getElementById("role");
    if(response.data.status=="success"){
        username.value=response.data.user.username;
        email.value=response.data.user.email;
        role.value=response.data.user.role;
    }
    const updateBtn = document.getElementById("updateBtn");
    let isEditing = false;

    updateBtn.addEventListener("click", () => {

        if (!isEditing) {
            username.disabled = false;
            email.disabled = false;
            updateBtn.textContent = "Save";
            updateBtn.classList.add("btn-success");
            updateBtn.classList.remove("btn-primary");
        } else {
            // Save data
            saveProfile();
        }

        isEditing = !isEditing;
    });

    async function saveProfile() {
        const updateUrl = `${config.apiBaseUrl}/user/update`;
        const username = document.getElementById("username").value;
        const email = document.getElementById("email").value;

        try {
            const updateResponse = await axios.post(updateUrl, {
                username,
                email
            }, {
                headers: {
                    "Authorization": `Bearer ${userID}`
                }
            });

            console.log(updateResponse.data);

            // Disable fields after saving
            document.getElementById("username").disabled = true;
            document.getElementById("email").disabled = true;
            updateBtn.textContent = "Update";
            updateBtn.classList.add("btn-primary");
            updateBtn.classList.remove("btn-success");
            localStorage.setItem("username",username);
            window.location.reload();
        } catch (error) {
            console.error("Error updating profile:", error);
            alert("Failed to update profile.");
        }
    }

})