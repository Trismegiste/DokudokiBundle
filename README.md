DokudokiBundle
==============

Yet another MongoDB database layer

What
----

It's a simple database layer. It is intended for **advanced user** of MongoDB
who know and understand the growth of a model on a schemaless database.

Without constrains, you can do anything and if you are not cautious, your 
collections will turn into CHAOS.

Why
---

Because ODM is a fraud. Turning MongoDB into an ORM-like abstraction is the worst
thing you can do on a NoSQL database. I have a mixed opinion about ORM
because of efficiency but the speed in the development process of the model is
obvious. But when it comes to ODM, you loose all the features of both world : 

 * rich document of MongoDB because the query generator sux
 * speed of MongoDB : because of multiple collections with many silly DBRef
 * schemaless of MongoDB : because you freeze the model in classes
 * speed of JOIN of RDBMS
 * constraints of RDBMS (references and types)

In fact, ODM is a bad ORM.

Remember, ORM is an emulation of an Object database (ODBMS) onto a RDBMS.
The impedance is good because there is almost a bijection between table and a class.
Doctrine ODM is an emulation of an ORM onto a MongoDB, you miss all advantages of
NoSQL. And don't talk me about @EmbeddedDocument, it fails whenever you tried
something above the simple use case.

How
---

Key features :
 * **Rich documents** by Hell !
 * Only a few root entites : 2, 3 or 4, 10 max for a full e-commerce app !
 * One app <=> One collection, Keep It Simple, Stupid
 * Forget 1NF, 2NF and 3NF. You gain versioning and easy cloning
 * Transform a tree of objects to tree of arrays and vice versa

It is simple : you make a model divided in few parts without circular reference, 
and you store it. It's like serialization but in MongoDB.

All non static properties are stored in a way you can query easily with the
powerfull (but very strange I admit) query language of MongoDB.

See the PHPUnit test for example.
