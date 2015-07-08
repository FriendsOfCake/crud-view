Introduction
============

CRUD View is an Admin Generator for CakePHP built on top of the CRUD plugin. It
is flexible enough that it can be used only for certain parts of your
applications and aims to be flexible enough so it requires little configuration.

The core philosophy behind CRUD and CRUD view is that you only need to deal with
aspects of your applications. This means that you should be able to listen for
events in order to modify how it looks and how it behaves.

Another goal of CRUD View is that its parts should be replaceable where
possible, but it should offer a sane, ready-to-use Admin interface by default
only by providing the database it needs to work with.

When to use CRUD View
---------------------

* When you need to implement an Admin interface that is generated from the
  Backend. If you want to create your interface using only javascript, please
  only use the CRUD plugin as it will help you creating the required API.

* When you want to take care about the rules of your data processing and not too
  much how the interface is going to look like.

* If you prefer tweaking, overriding and configuring instead of doing
  everything from scratch.

Status
------

This plugin is still in early development status, things may change suddenly,
but it can be used in real projects already.
