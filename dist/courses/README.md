Instructions
============
Naming Convention
-----------------
The folders inside the `courses/` folder must follow a naming convention. Sub-folders must start with "course" and then are suffixed with the courseId (primary key column from the "courses" table). That folder must then contain the course structure in a file named the same as the folder, and with a `.php` suffix. Example: If the courseId for your new course is 8, then the directory structure will look like this:

    dist
        courses
            course8
                course8.php
                page1.html
                page2.html
                etc...
                
