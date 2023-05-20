# Agenda Web Application

Agenda is a web-based application built using the Laravel framework and MySQL scheme. The application is designed using the Software Engineering Agile concept and developed in multiple phases. The project's first milestone is described in this document: [Milestone 1](https://docs.google.com/document/d/1vSQoD6_-r65wgjhFiZe2GmxnIJ4UtVdnLElvT9Ldgm8/edit?usp=sharing), the second in [Milestone 2](https://docs.google.com/document/d/1K5hD3mflWh634G8IcuHsaX-zv9V6C_FXI0dvXH-6uyI/edit?usp=sharing), and the third and final in [Milestone 3](https://docs.google.com/document/d/1MZXZV5_oBU9DroGZhBPrgjgiI7U5zvW0Vts_8dMYjcs/edit?usp=sharing).

## Getting Started
To run the Agenda web application, follow these steps:

1. Install PHP and MySQL on your machine.
2. Clone the repository to your local machine using the following command: git clone https://github.com/username/agenda.git
3. Navigate to the project directory using the command-line interface.
3. Install the required dependencies using the command: composer install
4. Copy the .env.example file to .env using the command: cp .env.example .env
5. Generate an application key using the command: php artisan key:generate
6. Update the .env file with your MySQL database credentials.
7. Run the database migrations using the command: php artisan migrate
8. Start the development server using the command: php artisan serve
9. Open your web browser and navigate to http://localhost:8000 to access the Agenda web application.

## System Components

### Views

The application has the following views:

#### - Guest Component

Includes a page for guests that has information about the website feedback form.

#### - Authentication Component

Includes the registration form for new accounts and the login form for existing accounts.

#### - Layout Component

One view includes the layout of the authentication, and the other one includes the layout of notes, diaries, and tasks.

#### - Agenda Component

Includes Task Component, Notes Component, and Diaries Component.

#### - Tasks Component

Includes created categories and default categories on the side of the screen. Once a task is created, the user chooses whether it’s categorized or uncategorized. Each task may include multiple steps, both of which can be modified and can have a deadline. The user may add collaborators to any task either as a copy of the task or for the collaborator to view the task details and progress.

#### - Notes Component

All notes are shown with each one’s title and a part of the description. If a category is chosen, all notes of this category are shown. Same as Tasks, with the exception that it doesn’t include any steps or a deadline.

#### - Diary Component

Shown as two opposite pages. Doesn’t include any categories. Once a page is finished, it becomes read-only. The user can bookmark any written page.

#### - Setting Component

Includes setting user information. Setting application themes. Clear history or clear account data.

### Controllers

#### Authentication Component

Includes three controllers:

- Login Controller.
- Register Controller.
- Change Information Controller.

#### Agenda Component

Includes three components:

- Tasks Component.
- Diaries Component.
- Notes Component.

#### Tasks Component

Includes two controllers:

- Tasks Controller uses methods from Steps Controller.
- Steps Controller.

#### Diary Component

Includes diary pages.

### Models

#### User Component

Includes:

- Task Component.
- Notes Component.
- Diary Component.

#### User Information Component

The user’s password is encrypted for the user's privacy.

#### Task Component

Includes all needed task data. Has two multi-valued attributes: each task has multiple steps, and each task may have multiple collaborators.

#### Notes Component

Includes all needed notes data. Has one multi-valued attribute as it may have multiple collaborators.

#### Diary Component

Includes information about every page. Whether the page is bookmarked. Doesn’t support any collaborators as it’s private.

## Functionalities

The Agenda application has the following functionalities:

- The user creates a new account with a unique username and password. The required data are first name, last name, birth date, and gender. He can log in using the username and password.
- The application has 3 sections:
  - To-do: Tasks can be added, edited, pinned, and deleted.
    - The task must have a name and category. In the case of uncategorized tasks, they will be added by default to the (Uncategorized) category.
    - The task can have a description, steps, deadline, collaborators, comments, and priority.
    - Collaborators can find their shared tasks in the (Assigned to me) category.
    - Tasks are sorted by their creation date by default. Users can also sort tasks by deadline or name.
    - Tasks are marked as completed if the user marked them or completed their steps.
    - The user’s progress is shown.
  - Notes: Notes can be added, edited, pinned, and deleted.
    - The notes must have a title and category. In case of uncategorized notes, they will be added by default to the (Uncategorized) category.
    - Notes are sorted by their modified date by default. Users can also sort tasks by title.
  - Diaries:
    - Diaries are notebook-like.
    - Users can write, edit and clear the pages.
    - Users can add bookmarks to pages.

## Possible Users

Anyone who:

- has an internet connection.
- wants to manage their tasks, notes, or keep diaries.

## Technology Stack

- Front-end: HTML, CSS, JavaScript, Bootstrap.
- Back-end: PHP.
- Database: MySQL.
- Framework: Laravel.

## Screenshots

Here are some screenshots of the Agenda application:

![Home Page](https://github.com/FarahMourad/Agenda/assets/58910478/76bc1796-02c7-4900-9663-ab0907c07923)

![Sign Up Page](https://github.com/FarahMourad/Agenda/assets/58910478/deca5d39-0766-49b7-9fbb-8d620122a88d)

![Sign In Page](https://github.com/FarahMourad/Agenda/assets/58910478/da8e0b1c-0e37-497e-9aae-020abf126d43)

![Add Task Page](https://github.com/FarahMourad/Agenda/assets/58910478/a5387bf1-d56d-4f30-b69c-bf1f5bc8e025)

![Tasks Page](https://github.com/FarahMourad/Agenda/assets/58910478/e18875c4-e1cf-45c3-a444-54f0f2549a55)

![Add Note Page](https://github.com/FarahMourad/Agenda/assets/58910478/a4f3cd7a-d2da-4416-8546-d61dff805ed8)

![Notes Page](https://github.com/FarahMourad/Agenda/assets/58910478/9531914a-1df7-444e-bac9-75d62a4f95bc)

![Diary Cover Page](https://github.com/FarahMourad/Agenda/assets/58910478/dd1ee0b4-daae-46db-a832-044cecc335c9)

![Diary Opened Page](https://github.com/FarahMourad/Agenda/assets/58910478/28aeb38e-2c10-4513-8517-26fbbdadfef9)

![Settings Page](https://github.com/FarahMourad/Agenda/assets/58910478/b99825a7-1f10-4d3b-9680-a37b08f4c5ad)

## Conclusion

By applying the Agile concept and using the Laravel framework and MySQL scheme, the Agenda web application is efficient, user-friendly, and meets the needs of users who want to manage their tasks, notes, or keep diaries.

## Contributing
To contribute to the project, follow these steps:

1. Fork the repository.
2. Clone the forked repository to your local machine.
3. Create a new branch for your changes using git checkout -b your-branch-name.
4. Make your changes to the code.
5. Test your changes thoroughly.
6. Commit your changes using git commit -m "Your commit message".
7. Push your changes to your forked repository using git push origin your-branch-name.
8. Create a pull request in the original repository.
