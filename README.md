# Running App
*Follow these steps to run the App:*

1. **Clone the Repository:**
   ```bash
    git clone https://github.com/your-username/your-laravel-app.git
   ```
2. **Navigate to the Project Directory:**
    ```bash
    cd the-raiz-app
    ```
3. **Install Dependencies**
    ```bash
    composer install
    ```

4. **Create a Copy of the .env File**
    ```bash 
    cp .env.example .env
    ```

5. **Generate Application Key**
    ```bash
    php artisan key:generate
    ```

6. **Configure Database**
    *Update the .env file with your database credentials.*
    <br>

7. **Run Migrations**
    ```bash
    php artisan migrate
    ```
8. **Generate the Kay to create token auth**
    ```bash
    php artisan passport:install
    ```
9. **Start the Development Server**
    ```bash
    php artisan serve
    ```

# Docs
*While the app is running, you can access to documentation interactive*
```
http://127.0.0.1:8000/docs/api
```
*Or Json Documentation format*
```
http://127.0.0.1:8000/docs/api.json
```

# Development
**helpful**
to create default user and product data, run the command:
```bash
php artisan db:seed
```
*Note: the user credential by default is:*
*User: dev@mail.com*
*Pass: 12345678*

# Get AccessToken
```bash
POST /api/oauth/token
Accept: application/json
Content-Type: application/json
{
    "grant_type": "password",
    "client_id": int,
    "client_secret": string,
    "username": "dev@mail.com",
    "password": "12345678"
}
```

# Refresh Token
```bash
POST /api/oauth/token
Accept: application/json
Content-Type: application/json
{
    "grant_type": "refresh_token",
    "refresh_token": string,
    "client_id": int,
    "client_secret": string
}
```
*The client_id and client_secret are generated when the command executes:*
```bash
php artisan passport:install
```

# Common errors:
ERROR: Personal access client not found. Please create one.
**Run the commant**
```bash
php artisan passport:client --password
```