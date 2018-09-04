# Doctor Booking App

## Introduction

This simple application uses the Zend 2 Framework MVC layer and module
systems. The application has 2 modules:
- Booking
- BookingRest (REST API)

To view an easy to read diff between the default Zend skeleton application and my work, click [here](https://github.com/tomcornall/booking-app/compare/a8a68f...master).

## Setup

You need to use [Composer](https://getcomposer.org/) to install the project. Run the following command from the project's root directory to install required packages:

```bash
$ composer install
```

Once installed, we also recommend that you initially use development mode, which you can enable using:

```bash
$ composer development-enable
```

Create a server using the built-in composer script which makes a local PHP server:

```bash
$ composer serve
```

## Database Setup

The database used is [SQLite](https://www.sqlite.org/). The Schema is saved under the data directory and can be setup easily:

```
$ sqlite3 data/booking.db < data/schema.sql

// Note you might need to use `sqlite` instead of the `sqlite3` version depending on your system
```

The Schema is as follows:

| Field name |	Type |	Null? |	Notes |
| ---------- | ----- | ------ | ----- |
| id | integer | No | Primary key, auto-increment
| username | varchar(80) | No | |
| reason | varchar(255) | No | |
| start_date | date | No | Format: `Y-m-d\TH:i` |
| end_date | date | No | Format: `Y-m-d\TH:i` |


## API Docs

### GET /api/booking

Gets a list of bookings.

`http GET localhost:8080/api/booking`

Response:

```json
{
    "data": [
        {
            "id": "1",
            "username": "John Johnson",
            "reason": "Back pain",
            "start_date": "2018-10-20T08:00",
            "end_date": "2018-10-20T10:00"
        },
        {
            "id": "2",
            "username": "Jack Jimson",
            "reason": "Teeth stuff",
            "start_date": "2019-02-01T01:10",
            "end_date": "2019-11-01T01:01"
        }
    ]
}
```

### GET /api/booking/:id

Gets a single booking, by ID.

`http GET localhost:8080/api/booking/2`

Response:

```json
{
    "data": {
        "id": "2",
        "username": "Jack Jimson",
        "reason": "Teeth stuff",
        "start_date": "2019-02-01T01:10",
        "end_date": "2019-11-01T01:01"
    }
}
```

### POST /api/booking

Creates a booking.

`http POST localhost:8080/api/booking`

Request Body:

```json
{
  "username": "Test Name",
  "reason": "Itchy",
  "start_date": "2018-10-20T08:00",
  "end_date": "2018-10-20T10:00"
}
```

Response:

```json
{
    "data": {
        "id": "2",
        "username": "Test Name",
        "reason": "itchy raATE11",
        "start_date": "2018-10-20T08:00",
        "end_date": "2018-10-20T10:00"
    }
}
```

### PUT /api/booking/:id

Updates a booking.

`http PUT localhost:8080/api/booking/2`

Request Body:

```json
{
  "username": "Test Name<script></script>",
  "reason": "Itchy",
  "start_date": "2018-10-20T08:00",
  "end_date": "2018-10-20T10:00"
}
```

Response:
```json
{
    "data": {
        "id": "2",
        "username": "Test Name",
        "reason": "Itchy",
        "start_date": "2018-10-20T08:00",
        "end_date": "2018-10-20T10:00"
    }
}
```

*Note the filtered out tag.

### DELETE /api/booking/:id

Deletes a booking.

`http DELETE localhost:8080/api/booking/2`

Response:
```json
{
    "data": "deleted"
}
```

## Running Unit Tests

To run the unit tests, use phpunit from the project's root directory:

```bash
$ ./vendor/bin/phpunit
```

And filter out specific tests:

```
$ ./vendor/bin/phpunit --testsuite BookingRest
```
