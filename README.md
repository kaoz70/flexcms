[![Code Climate](https://codeclimate.com/github/kaoz70/flexcms/badges/gpa.svg)](https://codeclimate.com/github/kaoz70/flexcms)
[![Test Coverage](https://codeclimate.com/github/kaoz70/flexcms/badges/coverage.svg)](https://codeclimate.com/github/kaoz70/flexcms/coverage)
[![Issue Count](https://codeclimate.com/github/kaoz70/flexcms/badges/issue_count.svg)](https://codeclimate.com/github/kaoz70/flexcms)
[![Build Status](https://travis-ci.org/kaoz70/flexcms.svg?branch=master)](https://travis-ci.org/kaoz70/flexcms)

# FlexCMS

Simple content management system, in the current state of the project its not ready to 
be used in production or even in development. Its still under heavy development.

Login:
![image](https://user-images.githubusercontent.com/79406/90838557-c27f5100-e31a-11ea-92e0-e35fa860c350.png)

Page config:
![image](https://user-images.githubusercontent.com/79406/90838802-754faf00-e31b-11ea-85f0-4df6de02b017.png)

Page images config:
![image](https://user-images.githubusercontent.com/79406/90838837-8ef0f680-e31b-11ea-9f91-9a9e9df4732b.png)

Image editor:
![image](https://user-images.githubusercontent.com/79406/90840196-f65c7580-e31e-11ea-8f62-3e160ad424d9.png)

## Current version

0.0.1a

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

If your sever doesn't have a way to setup environment variables, create a .env file 
with the contents of .env.example and set the credentials there.

## Admin access

http://domain.com/admin/

* User: miguel@dejabu.ec
* Pass: admin

## Development
From the console in the root folder do:
```
npm install
```
