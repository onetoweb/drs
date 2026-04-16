.. title:: Index

Index
=====

.. contents::
    :local:

===========
Basic Usage
===========

Setup

.. code-block:: php
    
    require 'vendor/autoload.php';
    
    use Onetoweb\Drs\Client;
    
    // param
    $privateKey = '/path/to/pk';
    $username = 42;
    $password = 'password';
    
    // optional passphrase for private key
    $passphrase = null;
    
    $client = new Client($username, $password, $privateKey, $passphrase);


========
Examples
========

* `Domain <domain.rst>`_
* `Reseller <reseller.rst>`_
* `Contact <contact.rst>`_
* `Host <host.rst>`_
