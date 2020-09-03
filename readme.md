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

## Lumen Framework

- I used the Lumen framework because in the benchmark with other micro-framework his got 1900 request per second
<img src="https://res.cloudinary.com/prra/image/upload/v1599065168/benchmark-microframework_l365r6.png">

## Redis Cache
- For the proporse of this app, I used Redis to control and send e-mail asyncronosly