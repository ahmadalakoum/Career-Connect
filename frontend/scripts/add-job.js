document.getElementById("jobForm").addEventListener("submit",async (e)=>{
    e.preventDefault();
    //check if user is authenticated
    const userID= localStorage.getItem("userID");
    if(!userID){
        window.location.href='login.html';
    }
    const title= document.getElementById("title").value.trim();
    const description = document.getElementById("description").value.trim();
    const salary = document.getElementById("salary").value.trim();
    const category = document.getElementById("category").value.trim();
    const deadline = document.getElementById("deadline").value;
    const location = document.getElementById("location").value.trim();
    const message = document.getElementById("error-message");
    message.textContent="";
    if(!title || !description || !salary || !category || !deadline || !location){
        message.textContent="All fields are required";
        message.classList.remove("d-none");
    }   
    const addJobUrl= `${config.apiBaseUrl}/add-job`;
    const response = await axios.post(addJobUrl,{
        title,
        description,
        salary,
        category,
        deadline,
        location
    },{
        headers:{
            "Authorization":`Bearer ${userID}`
        }
    });
    if(response.data.status=="success"){
        window.location.href="view-jobs";
    }else{
        message.textContent=response.data.message;
    }
})