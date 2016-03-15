Bongo Fixture Loader
====================

.. image:: https://travis-ci.org/bonesmccoy/bongo-fixture-loader.svg?branch=master
    :target: https://travis-ci.org/bonesmccoy/bongo-fixture-loader

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


Example of a message collection:

.. code-block:: yaml

    messages:
        - {"_id" : 1, "conversation" : 1, "sender" : 1, "recipient" : [ {"id" : 2 }, {"id": 3 }, {"id": 4} ], 'title' : 'title', 'body' : 'body', 'date' : '2016-03-04 12:00:00' }
        - {"_id" : 2, "conversation" : 1, "sender" : 2, "recipient" : [ {"id" : 1 }, {"id": 3 }, {"id": 4} ], 'title' : 'title', 'body' : 'body', 'date' : '2016-03-04 12:00:00' }
        - {"_id" : 3, "conversation" : 1, "sender" : 3, "recipient" : [ {"id" : 2 }, {"id": 1 }, {"id": 4} ], 'title' : 'title', 'body' : 'body', 'date' : '2016-03-04 12:00:00' }
        - {"_id" : 4, "conversation" : 1, "sender" : 4, "recipient" : [ {"id" : 2 }, {"id": 3 }, {"id": 1} ], 'title' : 'title', 'body' : 'body', 'date' : '2016-03-04 12:00:00' }
        - {"_id" : 5, "conversation" : 2, "sender" : 3, "recipient" : [ {"id" : 2 } ], 'title' : 'title', 'body' : 'body', 'date' : '2016-03-04 12:00:00' }
        - {"_id" : 6, "conversation" : 2, "sender" : 2, "recipient" : [ {"id" : 1 } ], 'title' : 'title', 'body' : 'body', 'date' : '2016-03-04 12:00:00' }


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
