# bunq-msg-app - BunqsApp

A simple messaging app built for the take home assessment for the role of junior back end engineer @ Bunq.

![BunqsApp Screenshot](public_html/assets/bunqsApp-img.png "BunqsApp in action!")

## Setup

- Install / Run composer:
    - To install Composer locally, run the installer in your project directory. See the [download page](https://getcomposer.org/download/) for instructions.
    OR
    - Programatically: bash script (UNIX Utilities) provided in: `scripts/composer-setup.sh`
        - Run `composer update && composer dump-autoload -o` to confirm dependencies are up to date and autoload files were generated successfully.


- Create fresh DB  migrations by deleting db/messages_db.db and running:
    - `sqlite3 messages_db.db` in the dir `db/`.
    - Run `begin immediate;` in the sqlite3 shell (a disk I/O error will not prevent the .db file from being created.);
    - `cd db/migrations && php create_messages_table.php` 

- Ensure dir containing SQLite3 .db file (/db) is writable.
- If step above errors, advising .db it not writable, change perms on file directly:
`sudo chmod -R a+rwX db/messages_db.db`

- Ensure dir containing write logic (/app) is writable.


- [optional] Set/change .htaccess file (template/example provided) if running apache server and required.
