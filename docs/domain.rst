.. _top:
.. title:: Domain

`Back to index <index.rst>`_

======
Domain
======

.. contents::
    :local:

Get domain availability
```````````````````````

.. code-block:: php
    
    $result = $client->domain->check([
        'example.com'
    ]);


Get domain info
```````````````

.. code-block:: php
    
    $result = $client->domain->info('example.com');


Register new domain
```````````````````

.. code-block:: php
    
    // param
    $name = 'example.com';
    $hostObj = 'namespace.nl';
    $registrant = '42-FOOBAR';
    $adminHandle = '42-FOOBAR';
    $techHandle = '42-FOOBAR';
    $domainPw = '';
    
    // optional
    $period = 1;
    $periodUnit = 'y'; // possible values (m/y)
    
    $result = $client->domain->create($name, $hostObj, $registrant, $adminHandle, $techHandle, $domainPw, $period, $periodUnit);


`Back to top <#top>`_