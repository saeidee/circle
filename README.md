# Circle

### The service for sending email

Installation
------------
Clone **circle** repository into your local environment:

```
git clone git@gitlab.com:saeid.saeidee/circle.git 
```

Run the following docker command

```
docker-compose build && docker-compose up -d
```

Enter to the circle-php-fpm container

```
docker exec -it circle-fpm bash
```
Copy .env file and install dependencies
```
cp .env.example .env
composer install
npm i && npm run dev
```
Set up your sendgrid and mailjet credentials in .env file
```
INITIAL_PREFERRED_MAIL_PROVIDER=sendgrid or mailjet
SENDGRID_SECRET=
MAILJET_SECRET=
SLACK_HOST=
```
Migrate the database
```
php artisan migrate
```
Start supervisor service for queued jobs to be proceed
```
service supervisor start
```
Or you may just run the following command instead of using supervisor
```
php artisan queue:work
```
You may also add cricle.saeidee.com to your host file since it is base url of our application.

Open you hosts file and add the following code:
```
127.0.0.1 cricle.saeidee.com
```

There is already a __deployed__ version of this application in AWS you may also check it.
http://circle.saeidee.com

__Note:__ please use the saeid.saeidee@gmail.com as sender (from) since this sender is registered for both 
providers sendgrid and mailjet.

Structure
------------
The structure of the project is not too complicated, but the important part of the structure is that we needed to 
have a fallback mechanism when one of our mail providers are down or not responding.
I chose __Circuit Breaker Pattern__ in which we will have separated circuit for each provider to check their status and healthiness. 

Circuits will have 3 statuses:

    - Close
    - Open
    - Half Open

When a circuit is Close it means it is healthy, and we can use it for further requests.
but when it is Open it means that there is a problem on receiving response from a provider, or we are receiving
some error responses, and when it is half open it means that it has some successful and unsuccessful requests,
so we will perform our business logics according to the circuits' status.
The flow of our system will be like this:

 - Request come to our application via (REST API or CLI)
 - Queue the sender campaign job
 - Insert some logs on DB 
 - Send the email with the initial preferred provider
 - Check the initial preferred provider circuit status
    - If it was close send the request
    - If it was open switch to another provider
    - If any of the providers are reached to max attempts, delay the sending request for 5 minutes and try again
 - When the email is sent by one of our provider we will update the related campaign log and make the status
as sent
 - If somehow our campaign sender job failed we will change the status of the campaign as failed.
 - End of operation.

__Note:__ When one of the circuits' status is changing we are sending alert message to slack, so we will be aware of it.

One more important thing is that for each campaign we are generating an uuid identifier, we are sending this uuid to 
the providers within the payload, this uuid can help us to track the campaign on the provider side or get 
the statistics like bounced users, delivered, clicks etc.

#### REST API

 - Sending a new campaign
 
```
curl --location --request POST 'circle.saeidee.com/api/v1/campaigns' \
--header 'Content-Type: application/json' \
--data-raw '{
    "campaign": "Winter days campaign",
    "subject": "Do you want to get more discount!",
    "from": {
        "email": "saeid.saeidee@gmail.com",
        "name": "Saeid Kanishka Saeidee"
    },
    "replyTo": {
        "email": "saeid.saeidee@gmail.com",
        "name": "Saeid Kanishka Saeidee"
    },
    "to": [
        {
            "email": "sk.saeidee@yahoo.com",
            "name": "Saeid Saeidee"
        },
        {
            "email": "saeid.saeidee@useinsider.com",
            "name": "Saeid Kanishka Saeidee"
        }
    ],
    "content": {
        "type": "text/plain",
        "value": "this is my new email from circle"
    }
}'
```
You can pass content type as __text/plain__ or __text/html__

 - Get the stats
```
curl --location --request GET 'circle.saeidee.com/api/v1/stats' \
--data-raw ''
```

#### CLI

You can also use CLI with the predefined structure.

__Structure of the payload should be like this:__

```
{ "campaign": "Campaign name", "subject": "something", "from": { "email": "saeid.saeidee@gmail.com", "name": "saeid saeidee" }, "replyTo": { "email": "example@example.com", "name": "example" }, "to": [ { "email": "example@example.com", "name": "example" }, { "email": "example@example.com", "name": "example" } ], "content": { "type": "text/plain", "value": "this is my new email from circle" } }
```
You can run the following commands:

First enter to the container
```
docker exec -it circle-fpm bash
```
then run the following command
```
php artisan circle:send-campaign '{ "campaign": "Campaign name", "subject": "something", "from": { "email": "saeid.saeidee@gmail.com", "name": "saeid saeidee" }, "replyTo": { "email": "example@example.com", "name": "example" }, "to": [ { "email": "example@example.com", "name": "example" }, { "email": "example@example.com", "name": "example" } ], "content": { "type": "text/plain", "value": "this is my new email from circle" } }'
```

If there was no problem with your structure of payload it will start sending your campaign.

You may check the panel to see your campaign sending status http://circle.saeidee.com/campaigns.


### Tests

The project has 100% of test coverage which includes __Unit Tests__ and __Integration Tests__.

You can use the following command to run the test.
```
 vendor/bin/phpunit --filter .
```

### Front-End
For front-end part I am using __Atomic Design__, where we have template components and data provider components,
as you can see from the codebase our data provider components are pages.

I used bootstrap-vue as a design system which has already builtin components. also, I used the vuex for manging the 
application state.

For emails which has html content I used codemirror package which is providing a nice editor for any programming
language.

If you had any question or problem please let me know via this email: _sk.saeidee@yahoo.com_
