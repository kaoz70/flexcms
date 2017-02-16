[![Code Climate](https://codeclimate.com/github/kaoz70/flexcms/badges/gpa.svg)](https://codeclimate.com/github/kaoz70/flexcms)
[![Test Coverage](https://codeclimate.com/github/kaoz70/flexcms/badges/coverage.svg)](https://codeclimate.com/github/kaoz70/flexcms/coverage)
[![Issue Count](https://codeclimate.com/github/kaoz70/flexcms/badges/issue_count.svg)](https://codeclimate.com/github/kaoz70/flexcms)
[![Build Status](https://semaphoreci.com/api/v1/kaoz70/web-flexcms/branches/master/shields_badge.svg)](https://semaphoreci.com/kaoz70/web-flexcms)
[![Build Status](https://travis-ci.org/kaoz70/flexcms.svg?branch=master)](https://travis-ci.org/kaoz70/flexcms)

# FlexCMS

Simple content management system

## Current version

0.0.1

## Installation
Copy the source to the localhost / server

Create a database with the file:

/sql/flexcms.sql

Remove this folder in production for security

### Database Configuration
Set up environment variables in the server (set with your own database credentials):
```
DB_USERNAME = "root"
DB_PASSWORD = "mysql"
DB_NAME = "flexcms"
DB_HOST = "localhost"
```

If your sever doesen't have a way to setup environment variables, create a .env file 
with the contents of .env.example and set the credentials there.

## Admin access

http://domain.com/login/

* User: miguel@dejabu.ec
* Pass: pass