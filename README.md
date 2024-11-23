
---

## **User Authentication**

### 1. **Login as User**
- **Endpoint:** `/login`  
- **Method:** `POST`  
- **Description:** Authenticates a user and returns an access token.  
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
- **Response Example:**
    ```json
    {
        "status": "success",
        "token": "your-auth-token",
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "user@example.com"
        }
    }
    ```

---

### 2. **Register as User**
- **Endpoint:** `/register`  
- **Method:** `POST`  
- **Description:** Registers a new user.  
- **Request Headers:**
    ```plaintext
    Accept: application/json
    ```
- **Request Body:**
    ```json
    {
        "name": "John Doe",
        "email": "user@example.com",
        "password": "password123",
        "password_confirmation": "password123"
    }
    ```
- **Response Example:**
    ```json
    {
        "status": "success",
        "message": "User registered successfully.",
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "user@example.com"
        }
    }
    ```

---

### 3. **Logout as User**
- **Endpoint:** `/logout`  
- **Method:** `POST`  
- **Description:** Logs out the authenticated user.  
- **Middleware:** `auth:sanctum`  
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

---

## **Admin Authentication**

### 1. **Login as Admin**
- **Domain:** `dashboard.your-domain.com`  
- **Endpoint:** `/login`  
- **Method:** `POST`  
- **Description:** Authenticates an admin and returns an access token.  
- **Request Headers:**
    ```plaintext
    Accept: application/json
    ```
- **Request Body:**
    ```json
    {
        "email": "admin@example.com",
        "password": "adminpassword123"
    }
    ```
- **Response Example:**
    ```json
    {
        "status": "success",
        "token": "admin-auth-token",
        "admin": {
            "id": 1,
            "name": "Admin User",
            "email": "admin@example.com"
        }
    }
    ```

---

### 2. **Register as Admin**
- **Domain:** `dashboard.your-domain.com`  
- **Endpoint:** `/register`  
- **Method:** `POST`  
- **Description:** Registers a new admin.  
- **Request Headers:**
    ```plaintext
    Accept: application/json
    ```
- **Request Body:**
    ```json
    {
        "name": "Admin User",
        "email": "admin@example.com",
        "password": "adminpassword123",
        "password_confirmation": "adminpassword123"
    }
    ```
- **Response Example:**
    ```json
    {
        "status": "success",
        "message": "Admin registered successfully.",
        "admin": {
            "id": 1,
            "name": "Admin User",
            "email": "admin@example.com"
        }
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

---

## **Rate Limiting**
- **Middleware:** `AuthRateLimiter`  
- Applies to all routes in this group to prevent abuse. Limits requests based on IP.
