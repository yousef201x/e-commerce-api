
---

## **Rate Limiting**
- **Middleware:** `AuthRateLimiter`  
- Applies to all routes in this group to prevent abuse. Limits requests based on IP , (max request per minute 10).

- **Middleware:** `RateLimite`  
- Applies to api/user to prevent abuse. Limits requests based on IP , (max request per minute 200). 

---

## **User Authentication**

### 1. **Register as User**
- **Domain:** `your-domain.com`  
- **Endpoint:** `api/register`  
- **Method:** `POST`  
- **Description:** Register a new user.  
- **Middleware:** `AuthRateLimiter` 
- **Request Headers:**
    ```plaintext
    Accept: application/json
    ```
- **Request Body:**
    ```json
    {
        "name" : "example",
        "email": "user@example.com",
        "password": "Must be at least 8 chars",
        "password_confirmation":"Must be at least 8 chars"
    }
    ```
- **Success Response Example:**
    ```json
    {
        "message": "Registration successful",
        "token": "your-auth-token",
    }
    ```

- **Validation error Response Example:**
    ```json
    {
       "errors":{
        "name":[
            "The name field is required."
        ],
        "email":[
            "The email field is required."
        ],
        "email":[
            "The email has already been taken."
        ],
        "password":[
            "The password field is required."
        ],
        "password":[
            "The password field confirmation does not match"
        ],
        "password":[
            "The password field must be at least 8 characters."
        ],
       }
    }
    ```

---

### 2. **Login as User**
- **Domain:** `your-domain.com`  
- **Endpoint:** `api/login`  
- **Method:** `POST`  
- **Middleware:** `AuthRateLimiter` 
- **Description:** Authenticates a user.  
- **Request Headers:**
    ```plaintext
    Accept: application/json
    ```
- **Request Body:**
    ```json
    {
        "email": "user@example.com",
        "password": "password123"
    }
    ```
- **Success Response Example:**
    ```json
    {
        "message": "Login successful",
        "token": "your-auth-token",
    }
    ```

- **Validation error Response Example:**
    ```json
    {
       "errors":{
        "email":[
            "The email field is required."
        ],
        "password":[
            "The password field is required."
        ],
        "password":[
            "The password field must be at least 8 characters."
        ],
       }
    }
    ```

- **Invalid credentials Response Example:**
    ```json
    {
        "message":"Invalid credentials"
    }
    ```

---

### 3. **Logout as User**
- **Domain:** `your-domain.com`  
- **Endpoint:** `api/logout`  
- **Method:** `POST`  
- **Description:** Logs out the authenticated user.  
- **Middleware:** `auth:sanctum`,`AuthRateLimiter`  
- **Request Headers:**
    ```plaintext
    Accept: application/json
    Authorization: Bearer your-auth-token
    ```
- **Response Example:**
    ```json
    {
        "status": "success",
        "message": "Logged out successfully."
    }
    ```

- **Invaild authentication token Response Example:**
    ```json
    {
        "message":"Unauthenticated."    
    }
    ```

---

### 4. **User Details**
- **Domain:** `your-domain.com`  
- **Endpoint:** `api/user`  
- **Method:** `GET`  
- **Description:** Returns user info.  
- **Middleware:** `auth:sanctum`,`RateLimit`  
- **Request Headers:**
    ```plaintext
    Accept: application/json
    Authorization: Bearer your-auth-token
    ```
- **Response Example:**
    ```json
    {
        "user": {
            "id": 1,
            "name": "User",
            "email": "example@mail.com",
            "email_verified_at": null,
            "created_at": "2024-11-20T16:20:20.000000Z",
            "updated_at": "2024-11-20T16:20:20.000000Z"
         }
    }
    ```

- **Invaild authentication token Response Example:**
    ```json
    {
        "message":"Unauthenticated."    
    }
    ```


### 5. **Get reset password link via email as user**
- **Domain:** `your-domain.com`  
- **Endpoint:** `api/password/email`  
- **Method:** `POST`  
- **Description:** Sends a reset password link via email.  
- **Middleware:** `AuthRateLimiter`  
- **Request Headers:**
    ```plaintext
    Accept: application/json
    ```
- **Response Example:**
    ```json
    {
        "message": "A password reset link has been successfully sent to your email"
    }
    ```

- **Validation errors response example:**
    ```json
    {
        "error": "Invalid email address provided.",
        "details": {
            "email": [
                "The email field is required."
            ]
        }
    }
    ```

- **Inserted email doesn't match our record reponse example:**
    ```json
    {
        "error": "We were unable to send a reset link to this email address."
    }
    ```
---

## **Admin Authentication**

### 1. **Register new Admin**
- **Domain:** `dashboard.your-domain.com`  
- **Endpoint:** `api/register`  
- **Method:** `POST`  
- **Description:** Registers new admin.  
- **Request Headers:**
    ```plaintext
    Accept: application/json
    ```
- **Request Body:**
    ```json
    {
        "name" : "example",
        "email": "admin@example.com",
        "password": "Must be at least 8 chars",
        "password_confirmation":"Must be at least 8 chars"
    }
    ```
- **Success Response Example:**
    ```json
    {
        "message": "Registration successful",
        "token": "your-auth-token",
    }
    ```

- **Validation error Response Example:**
    ```json
    {
       "errors":{
        "name":[
            "The name field is required."
        ],
        "email":[
            "The email field is required."
        ],
        "email":[
            "The email has already been taken."
        ],
        "password":[
            "The password field is required."
        ],
        "password":[
            "The password field confirmation does not match"
        ],
        "password":[
            "The password field must be at least 8 characters."
        ],
       }
    }
    ```

---

### 2. **Login as Admin**
- **Domain:** `dashboard.your-domain.com`  
- **Endpoint:** `/register`  
- **Method:** `POST`  
- **Description:** Authenticate an admin.  
- **Request Headers:**
    ```plaintext
    Accept: application/json
    ```
- **Request Body:**
    ```json
    {
        "email": "user@example.com",
        "password": "password123"
    }
    ```
- **Success Response Example:**
    ```json
    {
        "message": "Login successful",
        "token": "your-auth-token",
    }
    ```

- **Validation error Response Example:**
    ```json
    {
       "errors":{
        "email":[
            "The email field is required."
        ],
        "password":[
            "The password field is required."
        ],
        "password":[
            "The password field must be at least 8 characters."
        ],
       }
    }
    ```

- **Invalid credentials Response Example:**
    ```json
    {
        "message":"Invalid credentials"
    }
    ```

---

### 3. **Logout as Admin**
- **Domain:** `dashboard.your-domain.com`  
- **Endpoint:** `/logout`  
- **Method:** `POST`  
- **Description:** Logs out the authenticated admin.  
- **Middleware:** `VerifyAdminAuth`  
- **Request Headers:**
    ```plaintext
    Accept: application/json
    Authorization: Bearer admin-auth-token
    ```
- **Response Example:**
    ```json
    {
        "status": "success",
        "message": "Logged out successfully."
    }
    ```

- **Invaild authentication token Response Example:**
    ```json
    {
        "message":"Unauthenticated."    
    }
    ```

### 4. **Admin Details**
- **Domain:** `dashboard.your-domain.com`  
- **Endpoint:** `api/admin`  
- **Method:** `GET`  
- **Description:** Returns admin info.  
- **Middleware:** `auth:admin`,`RateLimit`  
- **Request Headers:**
    ```plaintext
    Accept: application/json
    Authorization: Bearer your-auth-token
    ```
- **Response Example:**
    ```json
    {
        "user": {
            "id": 1,
            "name": "User",
            "email": "example@mail.com",
            "email_verified_at": null,
            "created_at": "2024-11-20T16:20:20.000000Z",
            "updated_at": "2024-11-20T16:20:20.000000Z"
         }
    }
    ```


### 5. **Get reset password link via email as admin**
- **Domain:** `dashboard.your-domain.com`  
- **Endpoint:** `api/password/email`  
- **Method:** `POST`  
- **Description:** Sends a reset password link via email.  
- **Middleware:** `AuthRateLimiter`  
- **Request Headers:**
    ```plaintext
    Accept: application/json
    ```
- **Response Example:**
    ```json
    {
        "message": "A password reset link has been successfully sent to your email"
    }
    ```

- **Validation errors response example:**
    ```json
    {
        "error": "Invalid email address provided.",
        "details": {
            "email": [
                "The email field is required."
            ]
        }
    }
    ```

- **Inserted email doesn't match our record reponse example:**
    ```json
    {
        "error": "We were unable to send a reset link to this email address."
    }
    ```

---


