# Authentication API Documentation

## Register - `POST /api/register`
Register a new user and receive an authentication token.

**Request:**
```json
{
  "firstName": "John",
  "lastName": "Doe",
  "email": "john.doe@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response:**
```json
{
  "user": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john.doe@example.com",
    "created_at": "2025-01-01T12:00:00.000000Z",
    "updated_at": "2025-01-01T12:00:00.000000Z"
  },
  "token": "1|abcdefghijklmnopqrstuvwxyz123456"
}
```

## Login - `POST /api/login`
Authenticate a user and receive a token.

**Request:**
```json
{
  "email": "john.doe@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "user": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john.doe@example.com",
    "created_at": "2025-01-01T12:00:00.000000Z",
    "updated_at": "2025-01-01T12:00:00.000000Z"
  },
  "token": "1|abcdefghijklmnopqrstuvwxyz123456"
}
```

## Logout - `POST /api/logout`
Revoke the current token.

**Headers:**
```
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456
```

**Response:**
```json
{
  "message": "Logged out successfully"
}
```

## Current User - `GET /api/me`
Get the current authenticated user.

**Headers:**
```
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456
```

**Response:**
```json
{
  "id": 1,
  "first_name": "John",
  "last_name": "Doe",
  "email": "john.doe@example.com",
  "created_at": "2025-01-01T12:00:00.000000Z",
  "updated_at": "2025-01-01T12:00:00.000000Z"
}
```
