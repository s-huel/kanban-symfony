# Kanban Board App
A fully functional Kanban Board built with Symfony, MySQL, Doctrine and Twig. 
This app allows users to manage tasks using a flexible drag-and-drop interface.
Tasks can be organized into lanes, edited, moved and deleted with ease. 
The app includes an activity log to track user actions.

# Features
- Lane Management: Users can add, modify or remove lanes for organizing tasks.
- Task Management: Users can add, modify or delete tasks with a simple interface.
- Drag-and-Drop: Tasks can be easily moved between lanes using drag-and-drop functionality.
- Activity Log: The app keeps track of user actions, providing a history of changes made to tasks and lanes.
- User Authentication: Users can register, log in and log out.

# Tech Stack
- Backend: Symfony (PHP Framework)
- Database: MySQL, Doctrine (ORM)
- Frontend: Twig (Template Engine), JavaScript (fetching and drag-and-drop)

# Installation

## Prerequisites
- PHP >= 8.0
- Composer
- MySQL or MariaDB

## Clone the repository
```git clone git@github.com:s-huel/kanban-symfony.git```

## Install dependencies
Run the following command to install the required dependencies, make sure you are in the root directory of the project.
- ```composer install```

## Configure the database
For this step there are 2 options: through Doctrine or a GUI tool like phpMyAdmin or Sequel Ace. 
I will go through both options.

### Option 1: Using Doctrine
1. Configure the database connection in the .env file. (also see [example](.env.example))
   ```DATABASE_URL=mysql://username:password@127.0.0.1:3306/kanban_db?charset=utf8mb4```
2. Create the database, this automatically creates the database with the right name.:
   ```php bin/console doctrine:database:create```
3. Create the tables using:
   ```php bin/console doctrine:migrations:migrate```


### Option 2: Using a GUI tool
If you prefer to use a GUI tool like phpMyAdmin or Sequel Ace, you can create the database manually.
1. Create a new database: ```kanban_db```.
2. Configure the database connection in the .env file. (also see [example](.env.example))
```DATABASE_URL=mysql://username:password@127.0.0.1:3306/kanban_db?charset=utf8mb4```
3. Import the sql file delivered with the repo: [kanban_board.sql](/public/db/kanban_db_2025-04-05.sql).

## Run the application
1. Start the Symfony server after this you will be able to access the application:
   ```php bin/console server:start```

## How it works
The Kanban Board has four entities: User, Lane, Task and ActivityLog.

1. **User**: 
  - User can Register. Usually the first step in the application.
  - After registering, the user is redirected to the Login page. They login and get redirected to the Kanban Board.
  - The user can log out at any time using the button in the navbar.
2. **Lane**: 
  - The user can add a lane by clicking on the "Add Lane" button.
  - The user can edit the lane name by clicking on the "Edit" button of a lane.
  - The user can delete the lane by clicking on the "Delete" button of a Lane.
3. **Task**: 
  - The user can add a task by clicking on the "Add Task" button.
  - The user can edit the task name by clicking on the "Edit" button of a task.
  - The user can delete the task by clicking on the "Delete" button of a task.
  - The user can move the task to an another lane drag-and-drop.
  - User can add priority label to the task (is added while creating task, can be removed or updated and added again).
4. **ActivityLog**: 
  - The user can see the activity log by clicking on the "Activity Log" button inside of the navigation bar.
  - The activity log shows the history of changes made to tasks and lanes.

## Endpoints
These are the endpoints that are used in the web application. 
Also the only necessary ones for using the application.
The other ones are used in the backend, this includes the create, update and delete of the tasks and lanes.

- ```/register``` - Register a new user.
- ```/login``` - Login as user.
- ```/kanban``` - Kanban Board. (Home page)
- ```/activity-log``` - Activity log page.

## Future Features and Improvements
This project is a portfolio exam for school, but that does not keep me from moving on with the project.
This will most likely happen in a different repository! Which means that this is a future project in progress.
Because of this I still need to change a lot for it to be exactly as I want it to be!

1. User permissions & roles
 - Description: Implement user roles (admin, user) to manage permissions for different actions.
2. Workspace management
 - Description: Allow users to create and manage multiple workspaces for different projects.
3. Board sharing & collaboration
 - Description: Enable users to share boards with others for collaborative task management. As of now everyone is located on the same board :)
4. Notifications
 - Description: Implement a notification system to alert users about task updates, comments, etc.
5. Drag and drop improvements
 - Description: Enhance the drag-and-drop functionality for better user experience.
6. Task comments
 - Description: Allow users to add comments to tasks for better collaboration.
7. Task due dates & calendar integration
 - Description: Implement due dates for tasks and integrate with a calendar for better task management.
8. Checklists within tasks
 - Description: Allow users to create checklists within tasks for better task management.
9. Task attachments
 - Description: Allow users to attach files to tasks for better documentation.
10. Real-time syncing
 - Description: Implement real-time syncing of tasks and lanes across multiple devices.
11. Dark mode
 - Description: Implement a dark mode for better user experience.
12. Kanban board templates
 - Description: Provide pre-defined templates for different types of Kanban boards. 
13. Sprint Boards
 - Description: Implement a sprint board for agile project management.