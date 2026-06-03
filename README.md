[![Open in Visual Studio Code](https://classroom.github.com/assets/open-in-vscode-2e0aaae1b6195c2367325f4f02e2d04e9abb55f0b24a779b69b11b9e10269abc.svg)](https://classroom.github.com/online_ide?assignment_repo_id=23282747&assignment_repo_type=AssignmentRepo)

# ltw07g04

## Features

**All users:**
- [X] Register a new account.
- [X] Log in and out.
- [X] Edit their profile, including name, username, password, and profile photo.

**Members:**
- [X] Browse the schedule of available fitness classes, filtering by type, trainer, day, or time.
- [X] Enroll in and cancel enrollment from upcoming classes, subject to capacity limits.
- [X] View trainer profiles, including their specializations and the classes they teach.
- [X] Check the current availability of equipment in the main training area.
- [X] Leave ratings and reviews for classes they have attended.

**Trainers:**
- [X] Manage their public profile, including bio, specializations, and certifications.
- [X] View the roster of members enrolled in their classes.
- [X] Track and manage their assigned class schedule.

**Admins:**
- [X] Manage members and trainers (create, update, and deactivate accounts).
- [X] Manage the class catalog (create, edit, and remove classes) and assign trainers to them.
- [X] Manage equipment in the main training area (add, update availability status, and remove items).
- [X] Elevate a user to admin status.
- [X] Oversee and ensure the smooth operation of the entire system.

**Extra:**
- [X] Membership Plans
- [X] Pet Team and Rooms 
- [X] Payment mockup
- [X] Classes' pictures
- [X] Password recovery
- [X] Tracking of number of workouts and weekly streak
- [X] Ranking of most frequented classes in members' profile
- [X] Newsletter subscription
- [X] Individual pet profiles
- [X] Homepage live statistics 

## Running

    sqlite3 database/database.db < database/database.sql
    sqlite3 database/database.db < database/populate.sql
    php -S localhost:9000

## Credentials

- admin/p4s5w0rd
- member/1234
- trainer/1234

## Other relevant credentials 
**Trainers:**  
 - daenerys_t/1234
 - jon_snow/1234

**Pet trainer:** 
 - leonor_a/1234

**Members:**  
 - rita_s/1234


**Info**
- The badges we used for the Achievments Section were taken from https://www.koolbadges.co.uk/
- We used Generative AI to populate the databases, allowing us to focus on the development of the website whilst it filled the database, giving the work a more filled look
