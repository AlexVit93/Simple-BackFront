# A web-based user management application

**Task**: Implementation of a web application with user authentication, registration, and administration that meets the requirements:

- Manage user statuses (active/blocked).
- Multiple record selection via checkboxes.
- Authorization check before each action.
- Guarantee the uniqueness of the email through a unique index in the database.

---

### 🛠 Main functionality:

1. **Authentication and Registration**:

- Log in/log out.
  - Registration with data validation (at least 1 character in the password).
  - Blocked users cannot log in.

2. **Administrative panel**:

- A table of users sorted by the time of the last login.
  - Mass operations: lock, unlock, delete.
  - Visualization of statuses (active/blocked).

3. **Security**:

- Password hashing (bcrypt).
  - Verification of authorization before each request.
  - CSRF protection (via sessions).

---

### 🧩 Technology stack:

| Component    | Technologies                                |
| ------------ | ------------------------------------------- |
| **Frontend** | React, React-Bootstrap, Axios, React Router |
| **Backend**  | PHP 8+, RedBeanPHP (ORM), MySQL             |
| **Database** | The `users` table with a unique email index |
| **Server**   | Nginx, PHP-FPM                              |

---

### 🔑 Key Features:

- **Toolbar** above the action table:  
  ![Panel](media/toolbar_example.png)
- Buttons: `Block' (text), `Unblock' (icon), `Delete' (icon).
- **Responsive design** (support for mobile devices via Bootstrap).
- **Dynamic reloading** of data after actions.
- **Error handling**:
- Pop-up notifications for incorrect data.
  - Redirection to the login page in the absence of authorization.
