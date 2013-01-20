DokudokiBundle
==============

Yet another MongoDB database layer

What
----

It's a minimalistic database layer with automatic mapping. 
It is intended for **advanced user** of MongoDB
who know and understand the growth of a model on a schemaless database.

When I mean minimalistic, I mean the longest method takes about 20 lines. I think all
the NCLOC are shorter than the method UnitOfWork::createEntity in Doctrine 2.

Why
---

Because "ODM is a lie". Turning MongoDB into an ORM-like abstraction is the worst
thing you can do on a NoSQL database. I have a good opinion about ORM
because of speed in the model development process, despite some pitfalls.
But when it comes to ODM, you loose the features of NoSQL and you don't have
the features of a RDBMS, here are some : 

 * No rich document of MongoDB because the query generator sux
 * No schemaless of MongoDB because you freeze the model in classes
 * No JOIN then you must rely on slow lazy loading
 * No constraints of RDBMS (references and types) because there is none
 * No atomicity : the only atomicity in MongoDB is on one document

In fact ODM is slow ORM without ACID : what is the point ?

Remember, ORM is an emulation of an Object database (ODBMS) onto a RDBMS.
The impedance is good because there is almost a bijection between table and a class
(except for inheritance where the system start to fail)
Doctrine ODM is an emulation of an ORM onto MongoDB, but it was never design
to be used in this way.

How
---

Guidances :
 * **Rich documents** by Hell ! You have atomicity.
 * Stop to think 1 entity <=> 1 table
 * Only a few root entities : 2, 3 or 4, 10 max for a full e-commerce app !
 * 1 app <=> 1 collection
 * Forget 1NF, 2NF and 3NF. It's easier to deal with denormalized data in 
   MongoDB than to too many LEFT JOIN in MySQL
 * Thought like serialize/unserialize
 * Don't try to reproduce a search engine with your database : use Elastic Search
 * Don't try to store everything in collections : use XML files

It is simple : you make a model divided in few parts without circular reference, 
and you store it. It's like serialization but in MongoDB.

All non static properties are stored in a way you can query easily with the
powerfull (but very strange I admit) language of MongoDB.

See the PHPUnit tests for examples.

FAQ
---

### How to map properties ?
All *object's* properties are stored. You have only one thing to do : 
The root classes must implement the Persistable interface.

### What is a "root class"
It is a class stored in the collection, which contains the MongoId in the key '_id'.
All children in this class don't need to implement Persistable, they are
recursively stored.

### How can I remove some transient properties ?
You can't. But you can have a transient class with the interface Transient.
Use Decorator pattern or a State Pattern. Your model can do that.

### Can I make some cleaning before persistence ?
Like serialization, you can implement Cleanable with 2 methods : wakeup and sleep

### How can I query for listing ?
Use the MongoCollection, you can't be more efficient than this low level layer

### How can I store pictures or PDF ?
Use a MongoBinData in your model, it is stored as is

### Can I use something else than MongoId for primary key ?
No

### What about MongoDate ?
Any DateTime are converted into MongoDate and vice versa.

TODO
----

 * Storing DBRef properly but no automagic or whatsoever
 * Using aliasing instead of FQCN (I'm not sure if it is a good idea)
 * Interfacing with MongoSapinBundle
 * Make a Trait for a "PersistableImpl"

MongoSapinBundle
----------------

I have made this another db layer for MongoDB. Why two layers ? Because they
are complementary. MongoSapinBundle is for "Form Driven Development" i.e
it is not only schema-less but also it is model-less : There are "fake" classes
for rapid development but you can map entities on data later. 

Warning it is full of magic methods, it is only for
prototyping.

When your model has evolve and grown, you can switch almost seamlessly 
on this db layer.

Why this silly name ?
---------------------

Well, I'm not good at finding names, that's why I tend to keep the most ridiculous
than the most serious. It stands for "Docu(ment) do" + ki to recall
dokidoki (loosely means "excitment", sounds like heartbeats) in japanese.
This one, I'm pretty sure it is unique ^_^