.. _top:
.. title:: Reseller

`Back to index <index.rst>`_

========
Reseller
========

.. contents::
    :local:


Check reseller availability
```````````````````````````

.. code-block:: php
    
    $result = $client->reseller->check([
        'resellerId'
    ]);


Get reseller info
`````````````````

.. code-block:: php
    
    $result = $client->reseller->info('resellerId');


`Back to top <#top>`_