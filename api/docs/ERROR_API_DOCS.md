# Error Responses API Documentation

## Validation Error (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email field is required."
    ]
  }
}
```

## Unauthorized (401)
```json
{
  "message": "Unauthenticated."
}
```

## Forbidden (403)
```json
{
  "message": "This action is unauthorized."
}
```

## Not Found (404)
```json
{
  "message": "Resource not found."
}
```
