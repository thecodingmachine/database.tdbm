TDBM internals
==============

AbstractTDBMObject
------------------

All beans extend the `AbstractTDBMObject` class.
This class represents a bean. A bean is usually linked to one row in one table but can be linked to many rows in many
tables is DB inheritance is used (a primary key of one table is also a foreign key).

Therefore, a `AbstractTDBMObject` object contains:
 
- A list of `$dbRows` (A DbRow object represent a row in one table)
    - As soon as the object leaves the "detached" mode, this array is supposed to be sorted from parent to child table.
- `$status`: the status of the current object

A `DbRow` object contains the following properties:

- `$dbRow`: the data contained by the object, as an array of column => value
- `$primaryKeys`: the list of primary keys to access this object, as an array of column => value
- `$status`: the status of the current object

Statuses
--------

`AbstractTDBMObject` and `DbRow` can have the following statuses:

- `TDBMObjectStateEnum::STATE_DETACHED`: when an object has been created with the `new` keyword and is not yet
  aware of the `TDBMService` instance.
    - `DbRow::$primaryKeys`: *empty*
    - `TDBMService::$toSaveObjects`: *no*
    - `TDBMService::$objectStorage`: *no*
- `TDBMObjectStateEnum::STATE_NEW`: when an object is known from `TDBMService` but not yet stored in database.
  This happens after calling the `TDBMService->attach` method on a detached object.
    - `DbRow::$primaryKeys`: *empty*
    - `TDBMService::$toSaveObjects`: *yes*
    - `TDBMService::$objectStorage`: *no*
- `TDBMObjectStateEnum::STATE_LOADED`: when an object has been loaded from `TDBMService` and is not modified.
  This happens after calling the `TDBMService->getObject` for instance.
      - `DbRow::$primaryKeys`: *set*
      - `TDBMService::$toSaveObjects`: *no*
      - `TDBMService::$objectStorage`: *yes*
- `TDBMObjectStateEnum::STATE_DIRTY`: when an object has been loaded from `TDBMService` and is modified.
  This happens after calling the `TDBMService->getObject` for instance, then calling a setter on the bean.
      - `DbRow::$primaryKeys`: *set*
      - `TDBMService::$toSaveObjects`: *yes*
      - `TDBMService::$objectStorage`: *yes*
- `TDBMObjectStateEnum::NOT_LOADED`: when an object has been lazy-loaded from `TDBMService` and no getter has been
  called on it.
      - `DbRow::$primaryKeys`: *set*
      - `TDBMService::$toSaveObjects`: *no*
      - `TDBMService::$objectStorage`: *yes*
- `TDBMObjectStateEnum::DELETED`: when an object has been deleted using `TDBMService->delete`.
      - `DbRow::$primaryKeys`: it depends on original state (before call to delete)
      - `TDBMService::$toSaveObjects`: *no*
      - `TDBMService::$objectStorage`: *no*
