# Help a Friend Backend

## Introduction
This is the backend for the **Help a Friend** mobile application, which connects persons with disabilities (OKU) with volunteers for real-time assistance and support. The backend is responsible for handling authentication, user requests, location tracking, and database management.

---

## Features
- User authentication (signup, login, password reset, profile update, and deletion)
- Manage OKU help requests and volunteer acceptance
- Real-time location tracking
- Forum system (posts, comments, likes)
- Leaderboard system to track volunteer contributions
- Emergency hotline support
- MySQL database integration

---

## Setup Instructions

### 1. Clone the Repository
```sh
git clone https://github.com/zec0816/helpafriend.git
cd helpafriend
```

### 2. Set Up MySQL Database (XAMPP)
1. Start **Apache** and **MySQL** from the XAMPP Control Panel.
2. Open `phpMyAdmin` (`http://localhost/phpmyadmin`).
3. Create a new database, e.g., `helpafriend_db`.
4. Import the provided SQL file:
   ```sh
   mysql -u root -p helpafriend_db < helpafriend.sql
   ```

### 3. Configure Database Connection
Edit `connection.php` and update the database credentials:
```php
$servername = "localhost";
$username = "root";
$password = ""; // Default password is empty in XAMPP
$database = "helpafriend_db";
```

### 4. Start the Backend Server
Run the backend on a local PHP server:
```sh
php -S localhost:8000
```
The backend will be accessible at `http://localhost:8000/`.

---

## API Endpoints
### User Authentication
- `POST /register.php` – Register a new user
- `POST /login.php` – Authenticate a user
- `POST /updateProfile.php` – Update user profile information
- `POST /deleteProfile.php` – Delete user account

### Help Requests
- `POST /store_location.php` – OKU user requests assistance
- `GET /get_nearby_oku.php` – Volunteers fetch available requests
- `POST /update_status.php` – Volunteers accept a request
- `GET /get_accepted_requests.php` – Fetch accepted help requests
- `GET /fetch_help_history.php` – Fetch OKU help history

### Forum
- `POST /submit_post.php` – Create a new forum post
- `GET /get_posts.php` – Retrieve forum posts
- `POST /submit_comment.php` – Add a comment to a post
- `GET /get_comment.php` – Retrieve comments for a post
- `POST /likes.php` – Like a post

### Leaderboard
- `GET /get_leaderboard.php` – Fetch volunteer rankings
- `GET /fetch_user_points.php` – Retrieve user points

### Emergency Hotline
- `GET /get_emergency_contacts.php` – Fetch emergency contact numbers
