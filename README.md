#Bongo Fixture Loader

Simple fixture loader for mongo db.

##Installation
```
composer require-dev bonesmccoy/bongo-fixture-loader
```
##Configuration
Create a yaml file into /projectRoot/config/config.test.yml

```
config:
  db:
    db_name: messages_test
    host: localhost
    port: 27017
    username: ''
    password: ''
    connect: true

  fixtures:
      paths:
        - tests/Bones/Message/Fixtures

  
```

Create one or more fixtures in the configured path:

```
<collectionname>
    - object 1
    - object 2
    - object 3
```

Example of a message collection:

```
messages:
    - {"_id" : 1, "conversation" : 1, "sender" : 1, "recipient" : [ {"id" : 2 }, {"id": 3 }, {"id": 4} ], 'title' : 'title', 'body' : 'body', 'date' : '2016-03-04 12:00:00' }
    - {"_id" : 2, "conversation" : 1, "sender" : 2, "recipient" : [ {"id" : 1 }, {"id": 3 }, {"id": 4} ], 'title' : 'title', 'body' : 'body', 'date' : '2016-03-04 12:00:00' }
    - {"_id" : 3, "conversation" : 1, "sender" : 3, "recipient" : [ {"id" : 2 }, {"id": 1 }, {"id": 4} ], 'title' : 'title', 'body' : 'body', 'date' : '2016-03-04 12:00:00' }
    - {"_id" : 4, "conversation" : 1, "sender" : 4, "recipient" : [ {"id" : 2 }, {"id": 3 }, {"id": 1} ], 'title' : 'title', 'body' : 'body', 'date' : '2016-03-04 12:00:00' }
    - {"_id" : 5, "conversation" : 2, "sender" : 3, "recipient" : [ {"id" : 2 } ], 'title' : 'title', 'body' : 'body', 'date' : '2016-03-04 12:00:00' }
    - {"_id" : 6, "conversation" : 2, "sender" : 2, "recipient" : [ {"id" : 1 } ], 'title' : 'title', 'body' : 'body', 'date' : '2016-03-04 12:00:00' }
```

##Usage
```
bin/bongo-load

```

##Requirement

- PHP > 5.4
- mongo extension
- symfony/yaml
