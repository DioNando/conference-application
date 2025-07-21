# User API Documentation

## List Users - `GET /api/users`
Get a paginated list of users.

**Query Parameters:**
- `limit`: Number of users per page (default: 10)
- `page`: Page number (default: 1)

**Headers:**
```
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "first_name": "John",
      "last_name": "Doe",
      "email": "john.doe@example.com",
      "role": "admin",
      "is_active": true
    }
  ],
  "links": {},
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "path": "/api/users",
    "per_page": 10,
    "to": 1,
    "total": 1
  }
}
```

## Search Users - `GET /api/users/search/query`
Search and filter users.

**Query Parameters:**
- `q`: Search term (searches first_name, last_name, email, username)
- `role`: Filter by role (admin, manager, user, guest)
- `active`: Filter by active status (true, false)
- `sort_by`: Sort field (first_name, last_name, email, created_at, last_login)
- `sort_dir`: Sort direction (asc, desc)
- `limit`: Number of users per page (default: 10)
- `page`: Page number (default: 1)

**Headers:**
```
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "first_name": "John",
      "last_name": "Doe",
      "email": "john.doe@example.com"
    }
  ],
  "links": {},
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "path": "/api/users/search",
    "per_page": 10,
    "to": 1,
    "total": 1
  }
}
```

## User Statistics - `GET /api/users/stats/overview`
Get statistics about users.

**Headers:**
```
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456
```

**Response:**
```json
{
  "total": 100,
  "active": 95,
  "inactive": 5,
  "roles": {
    "admin": 2,
    "manager": 8,
    "user": 85,
    "guest": 5
  },
  "recent": [],
  "lastActive": []
}
```

## Get User - `GET /api/users/{id}`
Get details about a specific user.

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
  "username": "johndoe",
  "phone": "+1234567890",
  "role": "admin",
  "is_active": true,
  "created_at": "2025-01-01T12:00:00.000000Z",
  "updated_at": "2025-01-01T12:00:00.000000Z",
  "last_login": "2025-01-01T12:00:00.000000Z",
  "preferences": {
    "theme": "dark",
    "language": "en",
    "notifications": true,
    "emailNotifications": false
  }
}
```

## Create User - `POST /api/users`
Create a new user.

**Headers:**
```
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456
```

**Request:**
```json
{
  "firstName": "Jane",
  "lastName": "Doe",
  "email": "jane.doe@example.com",
  "username": "janedoe",
  "phone": "+9876543210",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "user"
}
```

**Response:**
```json
{
  "id": 2,
  "first_name": "Jane",
  "last_name": "Doe",
  "email": "jane.doe@example.com",
  "username": "janedoe",
  "phone": "+9876543210",
  "role": "user",
  "is_active": true,
  "created_at": "2025-01-01T12:00:00.000000Z",
  "updated_at": "2025-01-01T12:00:00.000000Z"
}
```

## Update User - `PUT /api/users/{id}`
Update an existing user.

**Headers:**
```
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456
```

**Request:**
```json
{
  "firstName": "Jane",
  "lastName": "Smith",
  "email": "jane.smith@example.com",
  "role": "manager",
  "isActive": true
}
```

**Response:**
```json
{
  "id": 2,
  "first_name": "Jane",
  "last_name": "Smith",
  "email": "jane.smith@example.com",
  "username": "janedoe",
  "phone": "+9876543210",
  "role": "manager",
  "is_active": true,
  "created_at": "2025-01-01T12:00:00.000000Z",
  "updated_at": "2025-01-01T12:00:00.000000Z"
}
```

## Update User Password - `PUT /api/users/{id}/password`
Update a user's password.

**Headers:**
```
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456
```

**Request:**
```json
{
  "currentPassword": "password123",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

**Response:**
```json
{
  "message": "Password updated successfully"
}
```

## Update User Preferences - `PATCH /api/users/{id}/preferences`
Update a user's preferences.

**Headers:**
```
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456
```

**Request:**
```json
{
  "theme": "dark",
  "language": "fr",
  "notifications": true,
  "emailNotifications": false
}
```

**Response:**
```json
{
  "message": "User preferences updated successfully",
  "preferences": {
    "theme": "dark",
    "language": "fr",
    "notifications": true,
    "emailNotifications": false
  }
}
```

## Delete User - `DELETE /api/users/{id}`
Delete a user.

**Headers:**
```
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456
```

**Response:**
```
204 No Content
```
