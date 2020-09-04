Dear candidate.

Imagine this scenario. An IoT device that measures various weather data is sending payloads to our servers. Our goal is to parse the payload that is packed in a binary format and store it in our database. While we are analysing the data we can also react on the various parameters.

Your task is to create an alert system that sends an email to a customer if a value exceeds a user defined threshold. Examples could be:
- battery level drops below below 2300 mV
- relative humidity level exceeds 90%
- air temperature drops below 13 degrees C
- others

Enclosed in this folder you will find 3 files.
- payloads holds examples of binary packed data from the station
- DataParser.php is a helper class with static methods to decode the station data into a more readable format
- example.php is an example of how to use the DataParser method

!!! Two important requests
- the service for dispatching emails should be asynchronous, separated from the service that is ingesting device data. In production environments we need to be able to respond to the IoT device as soon as possible and cannot wait for emails to (potentially many) clients to be dispatched

- make sure the client does not get too many alerts. If the device sends data every 15 minutes, he should not be alerted every time, even if the condition is met.

For achieving your task you can use any additional service that you might need. You can use any method to deliver payloads to your script - POST data, API request, CLI

In case of any questions, don't hesitate to ask.

# 🧠 Solution 

<p align="center"><img src="https://www.metos.at/wp-content/uploads/2020/06/Pessl-Logo_metos_homepage.jpg"></p>
<h1 align="center"> Send an alert asynchronos by e-mail</h1>


## ⚒ Lumen Framework

- I used the Lumen framework because I believe he is fast enough to do the job.
<p align="center"><img src="https://res.cloudinary.com/prra/image/upload/v1599065168/benchmark-microframework_l365r6.png"></p>

## ⚒ Redis Cache
- For the proporse of this app, I used Redis to control and send e-mail asynchronos
- Also is super fast.
<p align="center">
<a href="https://stackshare.io/stackups/amazon-sqs-vs-redis">
    <img src="https://res.cloudinary.com/prra/image/upload/v1599175341/redisvxAWS_qlyaax.png">
</a>
</p>

## 🏠 Archtecture
<p align="center">
<a href="#">
    <img src="https://res.cloudinary.com/prra/image/upload/v1599175839/arch_ydoirt.png">
</a>
</p>

1.  **Payloads**

    - Database, or service that provides payloads from the weather station
    - I mocked up a solution for simulates this service
    
2.  **User manually call payloads**
    
    - I created an endpoint where it is possible to call one payload at a time and see the result of the converted payload
    - I created a form where the user can save the parameters in cache (Redis) and it is linked to his email (just a plus)
    - To access the frontend **`http://localhost:8001/front`**
    <p align="center">
    <img src="https://res.cloudinary.com/prra/image/upload/v1599240933/inicio_sini7s.gif">

    - Endpoint: **`pessl.localhost:8001/api/`** - I used port 8001 because 8000 is usually busy
    </p>

    - Behind the code:
    ```php
        app/public/front/index.html
    ```
    **`When you change the e-mail, I get info from Cache to fill all parameters automatically`**
    
2.  **Command to call payloads**

    ```shell
        php artisan cron:checkPayloadCommand
    ```
3.  **API**

    - Api to get payloads **`pessl.localhost:8001/api/payload`**
    - Api to get and post user data **`pessl.localhost:8001/api/user`** 

4.  **Process Payload and Create Alert**

    - Place where I do the logic to process the payload and check parameters 
    - Create an alert or not
    - Dispatch to queue
    - Code:
    ```php
        app/Services/AlertService.php
        app/Services/PayloadService.php
        app/Services/LogService.php
    ```

5.  **Redis Queue**

    - I used Redis keys to control alert send
    - I used Redis Queue for send e-mails

6.  **Process Queue and Send Email**

    - The special feature from Lumen call queue:listen resolve the queue
    ```shell
        php artisan queue:listen
    ```

## 🚀 Infrastructure and Install

1.  **Install dependencies.**

    I use docker/docker-compose to runs all environment 
     ```shell
    # check docker / docker-compose version
    docker --version && docker-compose --version
    ```
    This environment contains:
    - nginx
    - php7.3
    - redis
    - phpredisadmin

2. **Start the project**

    ```shell
    #probably will take an while util install all dependencies
    docker-compose build

    #up the containers
    docker-compose up -d

    #create vendor folder
    composer update

    #run the lumen service
    php -S localhost:8001 -t public

    #checkup the containers IPS
    sudo docker inspect -f '{{.Name}} - {{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' $(sudo docker ps -aq) 

    #to run the payload service
    php artisan cron:checkPayloadCommand

    #to consumes the queue
    php artisan queue:listen

    #to see logs from sended e-mails
    php artisan cron:logs

    ```

3. **Environment Variables**
   **`See the complete .env file`** 
   ```shell
    #redis connetion
    QUEUE_CONNECTION=redis
    REDIS_CLIENT=predis
    
    REDIS_HOST=localhost
    REDIS_PASSWORD=null
    REDIS_PORT=6379
    #name of queue
    REDIS_QUEUE_NAME=send-email-alert

    #api key from sendgrid to delivery fast emails
    SEND_GRID_API_KEY=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

    #default parameters from user
    PARAMETER_BATERY_MIN=2300
    PARAMETER_RELATIVE_HUMIDITY_MAX=90
    PARAMETER_AIR_TEMPERATURE_MIN=13
    PARAMETER_DEW_POINT_MIN=90
    
    #send payload frequency by seconds
    SEND_PAYLOAD_FREQUENCY=15

    #default value for send e-mail frequency in hours
    SEND_EMAIL_FREQUENCY=8

    #main e-mail
    MAIN_EMAIL=paulo@actio.net.br

    # to test if a payload is match with de max or min parameters
    PAYLOAD_TO_TEST=93F9gAFwAG8AAJ0DQANaCQAAmAe\/BL0ExAToA+gD6APo\/+j\/6P++BLwEAAAAAA8=
   ```

4. **Unit Tests**

   ```shell
    #to run unit tests
    vendor/bin/phpunit

    #check if payload parameters its respect the user parameters
    #change the PAYLOAD_TO_TEST env variable
    vendor/bin/phpunit --filter parameters_check_up
   ```
   <p align="center"><img src="https://res.cloudinary.com/prra/image/upload/v1599248368/ezgif-7-5262f84f3f35_hfs03c.gif"></p>

5. **Lint code**
    ```shell
    #to lint the code
    vendor/bin/phplint
   ```

## 💫 Use

    ```shell
    # Acess the frontend to input some parameters
    pessl.localhost:8001/front

    # Endpoint payload
    pessl.localhost:8001/api/payload

    # Endpoint user data
    pessl.localhost:8001/api/user?email=${EMAIL}

    # To acess php Redis Admin
    pessl.localhost:8003
    
    ```