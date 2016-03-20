Bongo Fixture Loader
====================

.. image:: https://travis-ci.org/bonesmccoy/bongo-fixture-loader.svg?branch=master
    :target: https://travis-ci.org/bonesmccoy/bongo-fixture-loader
    

.. image:: https://coveralls.io/repos/github/bonesmccoy/bongo-fixture-loader/badge.svg?branch=develop 
     :target: https://coveralls.io/github/bonesmccoy/bongo-fixture-loader?branch=develop

Bon(es)(mon)go fixture loader for mongodb.

I know that I'm reinventing the weel, but sometimes it's nice to behave like a caveman ;)

Installation
------------

.. code-block:: bash

    $ composer require-dev bonesmccoy/bongo-fixture-loader

Configuration
-------------

Create a yaml file into ```/yourprojectRoot/config/bongo.yml```

.. code-block:: yaml

    
    mongo_data_store:
        db_name: your_db_name
        host: localhost
        port: 27017
        username: ''
        password: ''
        connect: true
    
    fixtures:
        paths:
            - path/to/fixture/from/project/root



Create one or more fixtures in the configured path:

.. code-block:: yaml

    <collectionname>:
        - object 1
        - object 2
        - object 3

Special Field Syntax

- to get an ObjectId (or MongoId in PHP)

.. code-block:: yaml

    "_id" : "<id@{24 CHARS HEX STRING}>

- to get a DateTime object

.. code-block:: yaml

    "dateTimeField" : "<YYYY-MM-DD HH:mm:SS>"
    "dateField": "<YYYY-MM-DD>"

Example of a list of forum post, where the first is the parent of the second:

.. code-block:: yaml

    posts:
        - {"_id" : "<id@56eb45003639330941000001>", "parentId" : "<id@56eb45003639330941000001>", "senderId" : 1, 'title' : 'title  1', 'body' : 'body content 1', 'date' : '<2016-03-04 12:00:00>' }
        - {"_id" : "<id@56eb45003639330941000002>", "parentId" : "<id@56eb45003639330941000001>", "senderId" : 2, 'title' : 'title', 'body' : 'body content 2', 'date' : '<2016-03-04 13:00:00>' }

will be saved as :

.. code-block::

    { 
        "_id" : ObjectId('56eb45003639330941000001'),
        "parentId: ObjectId('56eb45003639330941000001'),
        "senderId": 1,
        ...
        ...
        "date" : ISODate('2016-03-04 12:00:00')
    }
    
    { 
        "_id" : ObjectId('56eb45003639330941000002'),
        "parentId: ObjectId('56eb45003639330941000001'),
        "senderId": 1,
        ...
        ...
        "date" : ISODate('2016-03-04 13:00:00')
    }
    

Usage
-----
Load fixtures:

.. code-block:: bash
    
    $ bin/bongo-load /path/to/bongo.yml


Requirements
------------

- PHP > 5.4
- mongo extension
- symfony/yaml

