# Task Manager Project

## Installation of the project

1. Clone the repository
   ```bash
   git clone https://github.com/nossh/taskmanager.git
   cd taskmanager 
   ```

2. Install dependencies

 	```bash
   	composer install
	npm install && npm run dev
   	```
3. Set up .env file and update your MySQL credentials

4. Run migrations
 	```bash
   	php artisan migrate
   	```

5. Start server
 	```bash
   	php artisan serve
   	```

Then visit http://localhost:8000/taskmanager
