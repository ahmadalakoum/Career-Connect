# Career-Connect

## Project Overview

**Career-Connect** is a web application that connects job seekers and employers. Job seekers can sign up, log in, update their profile, browse available jobs, and apply by uploading their resumes. Employers can post jobs, view the listed jobs, and have the ability to edit and delete their job listings.

The project follows the **Repository Pattern** in the backend, providing a structured approach to database operations. It also implements **migrations and rollback** for easy and efficient database schema management.

### Technologies Used
- **Frontend**: HTML, CSS, Bootstrap, JavaScript
- **Backend**: PHP
- **Database**: MySQL

### Features

#### Job Seekers
- **Sign up**: Register with a unique username and password.
- **Login**: Secure login with authentication.
- **Update Profile**: Users can update their personal information and resume.
- **Browse Jobs**: Job seekers can view available job listings.
- **Apply for Jobs**: Upload a resume and apply to jobs.

#### Employers
- **Post Jobs**: Employers can create new job listings with details such as position, salary, and description.
- **View Listed Jobs**: Employers can view the jobs they have posted.
- **Edit and Delete Jobs**: Employers have full control over the jobs they've posted and can update or remove them as needed.

### Backend Structure
- **Repository Pattern**: The backend follows the repository pattern for organizing the database logic, making the code more maintainable and scalable.
- **Migrations & Rollback**: The database schema is version-controlled using migrations, and rollbacks are supported for easy management of changes.
