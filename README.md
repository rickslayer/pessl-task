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

# Solution 

<p align="center"><img src="https://www.metos.at/wp-content/uploads/2020/06/Pessl-Logo_metos_homepage.jpg"></p>
<h1 align="center"> Send an alert asynchronos by e-mail</h1>


## Lumen Framework

- I used the Lumen framework because I believe he is fast enough to do the job.
<p align="center"><img src="https://res.cloudinary.com/prra/image/upload/v1599065168/benchmark-microframework_l365r6.png"></p>

## Redis Cache
- For the proporse of this app, I used Redis to control and send e-mail asynchronos
<p align="center">
<a href="https://stackshare.io/stackups/amazon-sqs-vs-redis">
    <img src="https://res.cloudinary.com/prra/image/upload/v1599175341/redisvxAWS_qlyaax.png">
</a>
</p>

## Architecture
<p align="center">
<a href="#">
    <img src="https://res.cloudinary.com/prra/image/upload/v1599175839/arch_ydoirt.png">
</a>
</p>
1.  **Payloads**

    -Database, or service that provides payloads from the weather station
    -I mocked up a solution for simulates this service
    
2.  **User manually call payloads**
    
    - I created an endpoint where it is possible to call one payload at a time and see the result of the converted payload
    - I created a form where the user can save the parameters in cache (Redis) and it is linked to his email (just a plus)
    - To access the frontend **`http://localhost:8001/front`**
    <p align="center">
    <img src="https://res.cloudinary.com/prra/image/upload/v1599176885/parameters_h1dwbi.png">
    - Endpoint: **`pessl.localhost:8001/api/`** - I used port 8001 because 8000 is usually busy
    </p>
    - Behind the code:
    ```php
        app/public/front/index.html
    ```
    
2.  **Cron to call payloads**
    - ```php
    
    ```
3.  **API**
4.  **Process Payload and Create Alert**
5.  **Redis Queue**
6.  **Process Queue and Send Email**

