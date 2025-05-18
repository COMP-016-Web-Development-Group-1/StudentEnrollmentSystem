# Student Enrollment System

## 🚀 Getting Started

1. Open your terminal and navigate to the location where you want to store the project:

    ```bash
    cd <PATH TO PROJECT LOCATION>
    ```

2. Clone the repository directly into the current directory:

    ```bash
    git clone https://github.com/COMP-016-Web-Development-Group-1/StudentEnrollmentSystem.git
    ```

3. Navigate into the project folder:

    ```bash
    cd StudentEnrollmentSystem
    ```

4. Copy the `config.example.php` file and rename it to `config.php`:

    ```bash
    cp config.example.php config.php
    ```

5. Open `config.php` and update any necessary environment credentials (like database settings).

6. Run database setup and seeding:

    ```bash
    php artisan migrate --seed
    ```

    ✅ This will:

    - Create the necessary database tables (`courses`, `students`)
    - Seed the database with example courses and student data

    ℹ️ You can also use other custom commands with `php artisan`, such as:

    | Command                    | Description                                        |
    | -------------------------- | -------------------------------------------------- |
    | `php artisan list`         | Show all available commands                        |
    | `php artisan migrate`      | Run database migrations                            |
    | `php artisan seed`         | Seed the database with sample data                 |
    | `php artisan fresh`        | Drop all tables and re-run migrations              |
    | `php artisan fresh --seed` | Drop all tables, re-run migrations, then seed data |
    | `php artisan clear`        | Drop all tables                                    |

7. **Run the project locally using either:**

    - **PHP's built-in development server:**

        ```bash
        php -S localhost:8888 -t public
        ```

        Then open your browser and go to:
        [http://localhost:8888/courses.php](http://localhost:8888/courses.php)

    - **Or XAMPP (if installed):**

        1. Move or copy the project folder into your XAMPP `htdocs` directory (usually `C:\xampp\htdocs` on Windows).
        2. Open **XAMPP Control Panel** and start **Apache**.
        3. Access the project by visiting:
           [http://localhost/StudentEnrollmentSystem/public/courses.php](http://localhost/StudentEnrollmentSystem/public/courses.php)

---

## 🤝 Contributing

### 🛠 Contribution Guide

1. Make sure you're on the **`main`** branch and it's up to date:

    ```bash
    git checkout main
    git pull origin main
    ```

2. Create a new branch from `main` using this format:

    ```bash
    git checkout -b <type>/<short-task-desc>
    ```

    **Branch Name Format:**
    `[type]/[short-task-desc]`

    **Examples:**

    - `feat/login-form`
    - `fix/navbar-alignment`
    - `docs/update-readme`

    **Types:**

    - `feat` → New feature
    - `fix` → Bug fix
    - `refactor` → Code changes that neither fix bugs nor add features
    - `chore` → Maintenance tasks like updating dependencies or configs
    - `docs` → Documentation changes

3. Confirm your current branch:

    ```bash
    git branch
    ```

4. Work on your task. Stage and commit changes using this format:

    ```bash
    git add -A
    git commit -m "<type>: <short-description>"
    ```

    **Examples:**

    - `feat: add login form`
    - `fix: correct navbar alignment`
    - `docs: update contributing section`

5. Push your changes to GitHub:

    ```bash
    git push origin <your-branch-name>
    ```

6. Open a Pull Request (PR) on GitHub:

    - Go to the [GitHub Repository Pull Request Tab](https://github.com/COMP-016-Web-Development-Group-1/StudentEnrollmentSystem/pulls)
    - Click **"New Pull Request"**
    - Set:

        - **Base:** `main`
        - **Compare:** your branch (e.g., `feat/login-form`)

    - Add a PR title and description of what you did

7. **Notify the team**

    Let the team know in the Messenger Group Chat once your PR is ready or if you need help.
