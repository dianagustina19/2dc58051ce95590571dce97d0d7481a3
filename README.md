# Email Queue System

This repository contains a simple email queue system written in PHP. The system allows you to queue emails for sending and process the queue to send the emails.

## System Design

### Components

1. **API Endpoint (`api.php`)**
    - Handles incoming requests to queue emails.
    - Authenticates requests using JWT.

2. **Database (`database.php`)**
    - Manages the connection to the PostgreSQL database.
    - Stores queued emails.

3. **JWT (`jwt.php`)**
    - Generates and validates JSON Web Tokens (JWT) for authentication.

4. **Middleware (`middleware.php`)**
    - Authenticates incoming API requests using JWT.

5. **Email Sending (`send-email.php`)**
    - Contains the function to send emails using SMTP.

6. **Queue Processor (`queue.php`)**
    - Processes the email queue and sends pending emails.

7. **JWT Token Generator (`generate_token.php`)**
    - Generates a JWT token.

### Workflow

1. **Queueing Emails**
    - Clients send a POST request to the `api.php` endpoint with the email details (recipient, subject, body) and an Authorization header containing a valid JWT.
    - The `api.php` script authenticates the request and inserts the email into the `emails` table with a status of `false`.

2. **Processing the Queue**
    - The `queue.php` script runs continuously, checking the `emails` table for emails with a status of `false`.
    - For each email, it calls the `send_email` function from `send-email.php`.
    - If the email is sent successfully, it updates the status to `true`.

### Database Schema

The database has a single table `emails`:

| Column    | Type    | Description                 |
|-----------|---------|-----------------------------|
| id        | SERIAL  | Primary key                 |
| recipient | TEXT    | Email recipient             |
| subject   | TEXT    | Email subject               |
| body      | TEXT    | Email body                  |
| status    | TEXT    | Email status (false = queued, true = sent) |

## Running the System

### Prerequisites

- PHP
- PostgreSQL
- Composer

### Setup

1. Clone the repository:
    ```sh
    git clone https://github.com/dianagustina19/fbfc3debc376e761e0e8ee09c61695b0.git
    cd fbfc3debc376e761e0e8ee09c61695b0
    ```

2. Install dependencies:
    ```sh
    composer install
    ```

3. Set up the database:
    - Create a PostgreSQL database and user.
    - Create the `emails` table using the provided schema.

4. Configure the `.env` file with your database and SMTP settings.

### Running the API

1. **Start the PHP built-in server**:
    ```sh
    php -S localhost:8000
    ```

2. **Generate a JWT Token**:
    - Run the following command to generate a JWT token (expires in 1 hour):
        ```sh
        php generate_token.php
        ```

3. **Send a request to the API**:
    - **Using `curl` from the command line**:
        ```sh
        curl --location --request POST "http://localhost/2dc58051ce95590571dce97d0d7481a3/api.php" \
        --header "Authorization: Bearer <your_jwt_token>" \
        --header "Content-Type: application/json" \
        --data-raw '{
            "receipt": "example@example.com",
            "subject": "Test Subject",
            "body": "Test Body"
        }'
        ```
        Replace `<your_jwt_token>` with the JWT token generated in step 2.

    - **Using Postman**:
        - **Method**: `POST`
        - **URL**: `http://localhost/2dc58051ce95590571dce97d0d7481a3/api.php`
        - **Headers**:
            - Key: `bearer`
            - Value: `Bearer <your_jwt_token>`
        - **Body**:
            - Select `raw` and `JSON` format
            - Enter the following JSON data:
                ```json
                {
                    "receipt": "example@example.com",
                    "subject": "Test Subject",
                    "body": "Test Body"
                }
                ```

4. **Process the email queue**:
    - After submitting the email, it won't be sent immediately.
    - To send the email, run the following command to process the email queue:
        ```sh
        php queue.php
        ```