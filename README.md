# Introduction
## Overview
Geo-social Network focuses on providing location-based service to users, for example, check-in and finding nearby places for the current location. The project aims to develop a Geo-social Network as mobile and web application for HKUST students that would allow them to check-in and find their friends nearby, and to communicate on the personal status through postings. The web application acts as a thin client such that no additional software is needed to be installed for providing service and functionality.

In this project, a document-based NoSQL(Not only SQL) database – MongoDB[3] is used in constructing the system. MongoDB differs from typical relational database base that it organizes data in form of separated documents instead of rows in tables.

It also support a special type of index called Geospatial index that facilitate the searching of nearby location with the given coordinates (longitude and latitude). Besides the database structure, efficiency to retrieve data is also one major concern. Extra effort will be placed on designing the document structure to avoid joins to tradeoff efficiency with space by duplicating some of the values across different collections (tables).

To provide students with better location-based service experience, we use the HTML5 together with 3G/WiFi technology to acquire the location of the terminals (e.g. phones, laptops). Knowing the location of the users, we can provide more location specific service to our users.

Our goal is to create a Geo-Social Network for HKUST students to share their experience, as well as making new friends throughout their university life.

## Objectives
Geo-social Network provides location-based service to users. Functions are provided based on the current user location. In order to facilitate user check-in, collection of location points in UST would have to be done in order to find out the venue that user is located.

The system consists of four major components:
- Database (MongoDB): a commercial document based database which is used in industry supporting Geospatial Indexing which can be used to find nearby location for the specified coordinates(latitude and longitude).
- System API with RESTful Web Service (Implemented in java)
- Web Server
- Graphical User Interface

The functions are grouped into different pages as the following:
1. Home Page
2. Wall Page
3. Profile Page
4. Friend Page
5. Notification Page
6. Event Page
7. Check-in Page

The system will have to record all the user check-ins and search for check-ins having shortest distance for the current user location and return the related users. Every users and events are provided with a “Wall” so that users and their friends can post comments and also replying the others. They may also create events for their friends to join.

## Literature Survey
### Foursquare
Foursquare is a location-based application that allows users to check-in at different places. For every check-in, users can perform actions to interact with other users and to share their experience. It also rewards users with points and “badges” for every check-in. In return, users can get coupons or discounts as the number of days of check-ins is achieved.

### Google+
Google+ is a Social Networking Services that help users to manage their friends in different social circles. Other functions of Google+ are quite similar to that of Facebook. But it does have a post recommendation that let users to explore some funny or special posts every day. Besides, Google+ can bridge with other service provided by Google such as Google Calendar, Play Store and Google Docs etc.

### RESTful Web Services
A RESTful web service is a web service implemented using HTTP and the principles of REST. It basically means that each unique URL is a representation of some object and emphasis on readability. Given that RESTful doesn’t require message header like SOAP, it therefore saves bandwidth for data transfer. The main advantages of RESTful web services are: Lightweight, Human Readable Results and easy to setup.

### Haversine Formula
Haversine Formula is used for calculating the distance between two points on a sphere. With this formula, it makes us possible to get the distance between two users with their latitude and longitude location