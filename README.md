Hackathon 001 (with Atoto.cz) - image storage server
=============

## Github url
#### https://github.com/VyvojariSobe/hackathon-001-image-storage

## Test url
#### http://hackathon-001.vyvojarisobe.cz

## Upload new image
 - `POST` to http://hackathon-001.vyvojarisobe.cz/image/
 - Params:
   * url `string` - `http://hackathon-001.vyvojarisobe.cz/test.jpg`
   * content `string` - `[IMAGE CONTENT IN BASE64]`
 - Content types:
    - `application/x-www-form-urlencoded` - standard form data
    - `application/json` - json in request body

### Example (standard form data)
```
url=http%3A%2F%2Fhackathon-001.vyvojarisobe.cz%2Ftest.jpg
```
or
```
content=[IMAGE CONTENT IN BASE64]
```

### Example (json)
```json
{"url": "http://hackathon-001.vyvojarisobe.cz/test.jpg"}
```
or
```json
{"content": "[IMAGE CONTENT IN BASE64]"}
```

#### Result (`json`):
```json
{"key":"77615240eee222785fa472f901c255d7","filesize":1017958,"updated":1457874692}
```

## Retrieve image
 - `GET` to http://hackathon-001.vyvojarisobe.cz/image/{imageKey}[/{profile}]

## Retrieve image info
 - `GET` to http://hackathon-001.vyvojarisobe.cz/info/{imageKey}

#### Result (`json`):
 ```json
 {"key":"77615240eee222785fa472f901c255d7","filesize":1017958,"updated":1457874692}
 ```

## Delete original image and all stored profiles
 - `DELETE` to http://hackathon-001.vyvojarisobe.cz/image/{imageKey}

## Delete image in specific profile
 - `DELETE` to http://hackathon-001.vyvojarisobe.cz/image/{imageKey}/{profile}

## How can I specify profiles and can I use placeholder?
Yes, look into `app/config.php`.

introduce gitflow
