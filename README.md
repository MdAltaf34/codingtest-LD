# Laravel API Authentication Documentation

This project implements API authentication using **Laravel Sanctum**.  
All endpoints return **JSON** responses.

---

## Base URL
`http://127.0.0.1:8000/api`

---

## 1. Register a User

### Endpoint
`POST /api/register`

### Description
Registers a new user, hashes their password, and issues a temporary API token.

### Request Headers
```

Content-Type: application/json
Accept: application/json

````

### Request Body Example
```json
{
  "name": "Md Altaf",
  "email": "mdaltaf@example.com",
  "password": "password123"
}
````

### Validation Rules

* `name`: required, string, max:255
* `email`: required, email, unique
* `password`: required, min:8

### Success Response 

```json
{
  "status": "success",
  "message": "User registered successfully.",
  "user": {
    "id": 1,
    "name": "Md Altaf",
    "email": "mdaltaf@example.com"
  },
  "token": "1|abcdEfghIjkLMNOPqrsTUvWXyz",
  "token_type": "Bearer"
}
```

### Error Response 

```json
{
  "status": "error",
  "message": "Validation failed.",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

---

## 2. Login

### Endpoint

`POST /api/login`

### Description

Authenticates a user and issues a temporary API token.

### Request Headers

```
Content-Type: application/json
Accept: application/json
```

### Request Body Example

```json
{
  "email": "mdaltaf@example.com",
  "password": "password123"
}
```

### Success Response 

```json
{
  "status": "success",
  "message": "Login successful.",
  "user": {
    "id": 1,
    "name": "Md Altaf",
    "email": "mdaltaf@example.com"
  },
  "token": "1|abcdEfghIjkLMNOPqrsTUvWXyz",
  "token_type": "Bearer"
}
```

### Error Response 

```json
{
  "status": "error",
  "message": "Invalid credentials."
}
```

---

## 3. Logout

### Endpoint

`POST /api/logout`

### Description

Revokes the current user’s API token and logs them out.

### Request Headers

```
Content-Type: application/json
Accept: application/json
Authorization: Bearer <your_token_here>
```

*No body is required.*

### Success Response 

```json
{
  "status": "success",
  "message": "Logged out successfully."
}
```

### Error Response (Invalid Token)

```json
{
  "message": "Unauthenticated."
}
```

---

## Notes on Authentication

* Use the token from `register` or `login` in the `Authorization` header for protected routes.
* Format:

```
Authorization: Bearer your_token_here
```