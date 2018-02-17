<p align="center"><img  alt="UNN logo" src ="http://www.unn.edu.ng/wp-content/uploads/2015/03/UNN_Logo.jpg" />
<br><h1 align="center">The UNN API</h1></p>
 
 <p align="center">An unofficial API for the <a href="http://unnportal.unn.edu.ng">University of Nigeria student portal</a></p>
 
## Why use this?
Supposing you're building an app (for instance, a voting app), and you want to restrict it to UNN students. Simply ask them to login with their UNN Portal details, and pass them to this API to verify their identity. (Please do not store those credentials :pray:).

## How to Use

There's only one endpoint: `https://unn-api.herokuapp.com/students/auth`

Make a POST request with the following parameters (form-data or application/json content types acceptable)

- username: The student s unnportal.unn.edu.ng username. Example: 2013/123456
- password: The student s unnportal.unn.edu.ng password

If the login was successful, you should get a response like this (note the capitalization):
```
{
  "status": "success",
  "data": {
      "surname": "ADEBAYO",
      "first_name": "SHALVAH",
      "middle_name": "",
      "sex": "Male",
      "mobile": "080xxxxxxxx",
      "email": "FIRSTNAME.LASTNAME.REGNO@UNN.EDU.NG",
      "department": "ELECTRONIC ENGINEERING",
      "level": "200 LEVEL",
      "entry_year": "2010-2011",
      "grad_year": "2019-2020",
      "matric_no": "201x/xxxxxx",
      "jamb_no": "xxxxxxxxxx"
  }
}
```
For a failed login, you should get a response like this:

```json
{
    "status": "error",
    "message": "Login failed. Please check your credentials and try again."
}
```

Note: for this API to work, it has to make 3 web requests to the UNN portal. To optimize repeat requests, the API caches student details for a period of 4 hours. This means if you are able to login successfully, subsequent API calls will load your details from cache. You can explicitly force it to re-authenticate by sending a `Cache-Control: no-cache` header

## Packages
Building a PHP app? Instead of making requests manually, you could use [this package](https://github.com/shalvah/unnportal-php). Makes working with this API a breeze!

(PS. feel free to build packages for other languages, and holla at me to list them here)

## Contribution
If you would like to help improve this API, first of all, let me say thanks! All you need to do:
- Fork the project
- Clone your fork to your local machine
- Make your changes
- Commit your changes and push
- Open a pull request
I'll attend to all PRs as soon as I can!

## If you like this...
Please star and share! Thanks!
