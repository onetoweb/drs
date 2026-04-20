.. _top:
.. title:: Host

`Back to index <index.rst>`_

====
Host
====

.. contents::
    :local:


Check host availability
```````````````````````

.. code-block:: php
    
    $result = $client->host->check([
        'namespace.nl'
    ]);


Get host info
`````````````

.. code-block:: php
    
    $result = $client->host->info('namespace.nl');


`Back to top <#top>`_