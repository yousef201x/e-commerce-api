# **API Documentation**

This document outlines the available API endpoints for user and admin authentication, along with rate limiting and token expiration handling.

---

## **Rate Limiting**

- **Middleware:** `AuthRateLimiter`  
  - Applies to all routes in this group to prevent abuse. Limits requests based on IP, (max request per minute 10).

- **Middleware:** `RateLimite`  
  - Applies to `api/user` to prevent abuse. Limits requests based on IP, (max request per minute 200).

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
- **Validation Error Response Example:**
    ```json
    {
       "errors": {
            "name": ["The name field is required."],
            "email": ["The email has already been taken."]
       }
    }
    ```

### 2. **Login User**
- **Domain:** `your-domain.com`  
- **Endpoint:** `api/login`  
- **Method:** `POST`  
- **Description:** Logs in a user and returns a token.  
- **Middleware:** `AuthRateLimiter`  
- **Request Headers:**
    ```plaintext
    Accept: application/json
    ```
- **Request Body:**
    ```json
    {
        "email": "user@example.com",
        "password": "YourPassword"
    }
    ```
- **Success Response Example:**
    ```json
    {
        "message": "Login successful",
        "token": "user-auth-token",
    }
    ```
- **Validation Error Response Example:**
    ```json
    {
        "errors": {
            "email": ["The email is incorrect."],
            "password": ["The password is incorrect."]
        }
    }
    ```

### 3. **Get User Profile**
- **Domain:** `your-domain.com`  
- **Endpoint:** `api/user`  
- **Method:** `GET`  
- **Description:** Returns the authenticated user's profile information.  
- **Middleware:** `auth:api`  
- **Request Headers:**
    ```plaintext
    Accept: application/json
    Authorization: Bearer <user-auth-token>
    ```
- **Success Response Example:**
    ```json
    {
        "id": 1,
        "name": "example",
        "email": "user@example.com"
    }
    ```
- **Unauthorized Response Example:**
    ```json
    {
        "message": "Unauthenticated."
    }
    ```

---

## **Token Expiration**

### 1. **Token Expiry Handling**
- **Description:** Access tokens will expire after a set period (usually 60 minutes). You need to log in again after the token expires to obtain a new one.
- **Error Response (Token Expired):**
    ```json
    {
        "message": "Token expired, please log in again."
    }
    ```

- **Auto Refresh (Optional)**: Consider implementing a refresh token mechanism if auto token refresh is required. This allows you to get a new access token without logging in again, typically using a refresh token with an API call.

---

## **Admin Authentication**

### 1. **Login as Admin**
- **Domain:** `your-domain.com`  
- **Endpoint:** `api/admin/login`  
- **Method:** `POST`  
- **Description:** Admin login to access administrative features.  
- **Middleware:** `AuthRateLimiter`  
- **Request Headers:**
    ```plaintext
    Accept: application/json
    ```
- **Request Body:**
    ```json
    {
        "email": "admin@example.com",
        "password": "AdminPassword"
    }
    ```
- **Success Response Example:**
    ```json
    {
        "message": "Admin login successful",
        "token": "admin-auth-token",
    }
    ```

### 2. **Get Admin Dashboard**
- **Domain:** `your-domain.com`  
- **Endpoint:** `api/admin/dashboard`  
- **Method:** `GET`  
- **Description:** Returns the admin dashboard information.  
- **Middleware:** `auth:api, is_admin`  
- **Request Headers:**
    ```plaintext
    Accept: application/json
    Authorization: Bearer <admin-auth-token>
    ```
- **Success Response Example:**
    ```json
    {
        "stats": {
            "users_count": 120,
            "active_sessions": 15
        }
    }
    ```
- **Unauthorized Response Example:**
    ```json
    {
        "message": "Unauthorized access."
    }
    ```

---

## **Token Management and Refreshing**

### 1. **Manual Token Refresh**
- **Domain:** `your-domain.com`  
- **Endpoint:** `api/token/refresh`  
- **Method:** `POST`  
- **Description:** Refreshes the user's access token when expired.  
- **Request Headers:**
    ```plaintext
    Accept: application/json
    Authorization: Bearer <expired-auth-token>
    ```
- **Success Response Example:**
    ```json
    {
        "message": "Token refreshed successfully.",
        "new_token": "new-auth-token",
    }
    ```
- **Error Response Example:**
    ```json
    {
        "message": "Invalid refresh token."
    }
    ```

---

## **Error Codes**

- **400:** Bad Request — Invalid input or request.
- **401:** Unauthorized — Missing or invalid token.
- **403:** Forbidden — Insufficient permissions to perform action.
- **404:** Not Found — The requested resource does not exist.
- **500:** Internal Server Error — Something went wrong on the server.

---
