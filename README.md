DokudokiBundle
==============

Yet another MongoDB database layer

What
----

It's a simple database layer with automatic mapping. 
It is intended for **advanced user** of MongoDB
who know and understand the growth of a model on a schemaless database.

Without constrains, you can do anything and if you are not cautious, your 
collections will turn into CHAOS.

Why
---

Because ODM is a fraud. Turning MongoDB into an ORM-like abstraction is the worst
thing you can do on a NoSQL database. I have a good opinion about ORM
because of the speed in the development process of the model, despite some pitfalls.
But when it comes to ODM, you loose the features of NoSQL and you don't have
the features of a RDBMS, here are some : 

 * rich document of MongoDB because the query generator sux
 * schemaless of MongoDB because you freeze the model in classes
 * speed of JOIN of RDBMS because you rely only on lazy loading
 * constraints of RDBMS (references and types) because there is none
 * atomicity : the only atomicity in MongoDB is on one document

Remember, ORM is an emulation of an Object database (ODBMS) onto a RDBMS.
The impedance is good because there is almost a bijection between table and a class
(except for inheritance where the system start to fail)
Doctrine ODM is an emulation of an ORM onto MongoDB, but it was never design
to be used in this way.

How
---

Key features :
 * **Rich documents** by Hell ! You have atomicity.
 * Stop to think 1 entity <=> 1 table
 * Only a few root entities : 2, 3 or 4, 10 max for a full e-commerce app !
 * 1 app <=> 1 collection
 * Forget 1NF, 2NF and 3NF. It's easier to deal with denormalized data in 
   MongoDB than to too many LEFT JOIN in MySQL
 * Thought like serialize/unserialize
 * Don't try to reproduce a search engine with your database : use Elastic Search
 * Don't try to store everything in collections : for dumb entities, use XML file

It is simple : you make a model divided in few parts without circular reference, 
and you store it. It's like serialization but in MongoDB.

All non static properties are stored in a way you can query easily with the
powerfull (but very strange I admit) language of MongoDB.

See the PHPUnit test for example.

FAQ
---

### How to map properties ?
All *object's* properties are stored. You have only one thing to do : 
implementing the Persistable interface.

### How can I remove some transient properties ?
You can't. Use decorator pattern if you really want to.

### How can I query for listing
Use the MongoCollection, you can't do more efficient than this low level layer

### How can I store pictures or PDF ?
Use a MongoBinData, it is stored as is

### Can I use something else than MongoId for primary key ?
No

### What about MongoDate ?
Any DateTime are converted into MongoDate and vice versa.

TODO
----

 * Storing DBRef properly but no automagic or whatsoever
 * Using aliasing instead of FQCN
 * Interfacing with MongoSapinBundle
 * Make a Trait for a "PersistableImpl"

MongoSapinBundle
----------------

I have made this another db layer for MongoDB. Why two layers ? Because they
are complementary. MongoSapinBundle is for "Form Driven Development" i.e
it is not only schema-less but also it is model-less : you don't need
model classes to store datas, but you can map entities on data later in the 
process of development. Warning it is full of magic methods, it is only for
prototyping.

When your model has evolve and grown, you can switch almost seamlessly 
on this db layer.

Why this silly name ?
---------------------

Well, I'm not good at finding names, that's why I tend to keep the most ridiculous
than the most serious. It stands for "Docu(ment) do" + ki to recall
dokidoki (loosely means "excitment", sounds like heartbeats) in japanese.
This one, I'm pretty sure it is unique ^_^