# API Documentation pour les Événements

## Event Management

### List Events - `GET /api/events`

Get a paginated list of events.

**Query Parameters:**

- `limit`: Number of events per page (default: 10)
- `page`: Page number (default: 1)

**Headers:**

```text
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456
```

**Response:**

```json
{
  "data": [
    {
      "id": 1,
      "name": "BMCE Invest Conference 2025",
      "description": "Annual investment conference for issuers and investors",
      "start_date": "2025-07-17",
      "end_date": "2025-07-18",
      "location": "Casablanca, Morocco",
      "is_active": true,
      "created_at": "2025-01-01T12:00:00.000000Z",
      "updated_at": "2025-01-01T12:00:00.000000Z"
    }
  ],
  "links": {},
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "path": "/api/events",
    "per_page": 10,
    "to": 1,
    "total": 1
  }
}
```

### Search Events - `GET /api/events/search/query`

Search and filter events.

**Query Parameters:**

- `q`: Search term (searches name, description, location)
- `active`: Filter by active status (true, false)
- `date_from`: Filter events starting on or after this date (YYYY-MM-DD)
- `date_to`: Filter events ending on or before this date (YYYY-MM-DD)
- `current`: Show only current events (events happening today)
- `sort_by`: Sort field (name, start_date, end_date, location, created_at)
- `sort_dir`: Sort direction (asc, desc)
- `limit`: Number of events per page (default: 10)
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
      "name": "BMCE Invest Conference 2025",
      "description": "Annual investment conference for issuers and investors",
      "start_date": "2025-07-17",
      "end_date": "2025-07-18",
      "location": "Casablanca, Morocco",
      "is_active": true
    }
  ],
  "links": {},
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "path": "/api/events/search",
    "per_page": 10,
    "to": 1,
    "total": 1
  }
}
```

### Event Statistics - `GET /api/events/stats/overview`

Get statistics about events.

**Headers:**

```
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456
```

**Response:**

```json
{
  "total": 3,
  "active": 1,
  "inactive": 2,
  "current": 1,
  "upcoming": 2,
  "past": 0,
  "recent": [
    {
      "id": 3,
      "name": "Investment Roadshow",
      "start_date": "2025-11-20",
      "end_date": "2025-11-22",
      "location": "Marrakech, Morocco",
      "is_active": false
    }
  ],
  "nextEvents": [
    {
      "id": 2,
      "name": "Financial Markets Workshop",
      "start_date": "2025-09-15",
      "end_date": "2025-09-17",
      "location": "Rabat, Morocco",
      "is_active": false
    }
  ]
}
```

### Get Event - `GET /api/events/{id}`

Get details about a specific event.

**Headers:**

```
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456
```

**Response:**

```json
{
  "id": 1,
  "name": "BMCE Invest Conference 2025",
  "description": "Annual investment conference for issuers and investors",
  "start_date": "2025-07-17",
  "end_date": "2025-07-18",
  "location": "Casablanca, Morocco",
  "is_active": true,
  "created_at": "2025-01-01T12:00:00.000000Z",
  "updated_at": "2025-01-01T12:00:00.000000Z"
}
```

### Create Event - `POST /api/events`

Create a new event.

**Headers:**

```
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456
```

**Request:**

```json
{
  "name": "Digital Banking Summit",
  "description": "Summit for digital banking innovation",
  "start_date": "2025-10-15",
  "end_date": "2025-10-17",
  "location": "Tangier, Morocco",
  "is_active": false
}
```

**Response:**

```json
{
  "id": 4,
  "name": "Digital Banking Summit",
  "description": "Summit for digital banking innovation",
  "start_date": "2025-10-15",
  "end_date": "2025-10-17",
  "location": "Tangier, Morocco",
  "is_active": false,
  "created_at": "2025-01-01T12:00:00.000000Z",
  "updated_at": "2025-01-01T12:00:00.000000Z"
}
```

### Update Event - `PUT /api/events/{id}`

Update an existing event.

**Headers:**

```
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456
```

**Request:**

```json
{
  "name": "Digital Banking Summit 2025",
  "location": "Tangier Convention Center, Morocco",
  "is_active": true
}
```

**Response:**

```json
{
  "id": 4,
  "name": "Digital Banking Summit 2025",
  "description": "Summit for digital banking innovation",
  "start_date": "2025-10-15",
  "end_date": "2025-10-17",
  "location": "Tangier Convention Center, Morocco",
  "is_active": true,
  "created_at": "2025-01-01T12:00:00.000000Z",
  "updated_at": "2025-01-01T12:00:00.000000Z"
}
```

### Toggle Event Active Status - `PATCH /api/events/{id}/toggle-active`

Toggle the active status of an event.

**Headers:**

```
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456
```

**Response:**

```json
{
  "message": "Event status updated successfully",
  "is_active": false
}
```

### Get Event Dates - `GET /api/events/{id}/dates`

Get all dates for an event.

**Headers:**

```
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456
```

**Response:**

```json
{
  "dates": [
    "2025-10-15",
    "2025-10-16",
    "2025-10-17"
  ],
  "start_date": "2025-10-15",
  "end_date": "2025-10-17"
}
```

### Delete Event - `DELETE /api/events/{id}`

Delete an event.

**Headers:**

```
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456
```

**Response:**

```text
204 No Content
```
