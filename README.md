# DokudokiBundle [![Build Status](https://travis-ci.org/Trismegiste/DokudokiBundle.png?branch=master)](https://travis-ci.org/Trismegiste/DokudokiBundle)

![Yo dawg Xzibit](./Resources/doc/img/atomicity.jpg)

Atomicity in [MongoDB][*2] explained by Xzibit

## What

It's a minimalistic database layer with automatic mapping.
It is intended for **advanced users** of [MongoDB][*2]
who know and understand the growth of a model on a schemaless database.

When I mean minimalistic, I mean all
the NCLOC of the dbal itself are shorter than the infamous
method [UnitOfWork::createEntity][*1]
of Doctrine 2

Of course, features are also minimalistic. Don't expect the impossible. Nevertheless
there are some functionalities you don't find anywhere else.
In my quest for rapid development
and the "Don't repeat yourself", I try to make an agnostic DBAL which *helps*
you in the process to build an app regardless the model is finished or not.

## Why

Because, like the cake, "ODM is a lie". Turning MongoDB into an ORM-like
abstraction is the worst thing you can do against a NoSQL database.

With an ODM, you loose both NoSQL and RDBMS features, here are some :

 * No rich document of MongoDB because the query generator sux and the mapping is complex
 * No schemaless capability because you freeze the model in classes
 * No JOIN, you must rely on slow lazy loading
 * No constraint of RDBMS (references and types) because there is none
 * No atomicity : the only atomicity in MongoDB is on one document

In fact ODM is a slow [ORM][*5] without ACID : what is the point of using MongoDB ?

That's why I stop chasing the ["Mythical Object Database"][*3] and start hacking.

## Guidances

 * **Rich documents** by Hell ! You have atomicity.
 * Stop to think 1 entity <=> 1 table
 * Only a few root entities : 2, 3 or 4, 10 max for a full e-commerce app, not 200 !
 * 1 app <=> 1 collection
 * Forget 1NF, 2NF and 3NF. It's easier to deal with denormalized data in
   MongoDB than to too many LEFT JOIN in MySQL
 * Think like serialize/unserialize
 * Don't try to reproduce a search engine with your database : use [Elastic Search][*7]
 * Don't try to store everything in collections : use XML files

So, you make a model divided in few parts without circular reference,
and you store it. It's like serialization but in MongoDB.

All non static properties are stored in a way you can query easily with the
powerfull (but very strange I admit) language of MongoDB.

See the [PHPUnit tests][*12] for examples.

## Four modes of working

This DBAL has 4 stages, regarding the completion of the model classes.

The trick is you can migrate between these stages when you develop your app
and, for example, don't need to start over after a dirty prototype. You even
can generate a model with the data you had stored into collections
when you don't have one.

See [Examples of using this dbal in PHPUnit Tests][*12]

### Black Magic is black
If you have no model and a lot of forms to design, start with the "BlackMagic" stage.
It is full of magic methods, magic mapping and magic documents,
it's like working with mockup.

But be warned :
it is for prototyping and your database can turn back against you
if you are not careful. That's what I call "Form Driven Development".

See full example in [unit test][*13]

```php
// construct a form
$form = $this->formFactory
        ->createBuilder('magic_form', null, array('class_key' => 'product'))
        ->add('title')
        ->add('price')
        ->getForm();
// bind data to th form
$form->bind(array('title' => 'EF-85 L', 'price' => 2000));
$doc = $form->getData();
// getting the magic document
$this->assertInstanceOf('Trismegiste\DokudokiBundle\Magic\Document', $doc);
$this->assertEquals('product', $doc->getClassName());
$this->assertEquals('EF-85 L', $doc->getTitle());
// persistence with blackmagic repository
$this->blackmagic->persist($doc);
// restoring with blackmagic repository
$restore = $this->blackmagic->findByPk((string) $doc->getId());
$this->assertInstanceOf('Trismegiste\DokudokiBundle\Magic\Document', $restore);
$this->assertEquals('product', $restore->getClassName());
$this->assertEquals('EF-85 L', $restore->getTitle());
```

### Serialization could be enough
If you have a lot of nearly complete model classes and don't want configure anything,
use the "Invocation" stage. Only magic mapping and strict typing between objects
and documents.

But if you need to make complex queries or map-reduce, it can be
very dirty. This stage is usefull for RESTful app without GUI.

See full example in [unit test][*14]

```php
// simple object
$doc = new \Some\Sample\Product('EF-85 L', 2000);
// persisting
$this->invocation->persist($doc);
// restoring with invocation repository
$restore = $this->invocation->findByPk((string) $doc->getId());
$this->assertInstanceOf('Some\Sample\Product', $restore);
// retrieving the content in the MongoDB
$dump = $this->collection->findOne(array('_id' => $doc->getId()));
$this->assertEquals('Some\Sample\Product', $dump['-fqcn']);  // we store the FQCN
$this->assertEquals('EF-85 L', $dump['title']);
$this->assertEquals(2000, $dump['price']);
```

### White Magic is for Lawful Good
If you have a good model and the time to carefully alias classes in the database,
use the "WhiteMagic" stage. There is automapping but without surprise, your model cannot
turn into chaos.

See full example in [unit test][*15]

```php
// simple object
$doc = new \Some\Sample\Product('EF-85 L', 2000);
// persisting
$this->whitemagic->persist($doc);
// restoring with whitemagic repository
$restore = $this->whitemagic->findByPk((string) $doc->getId());
$this->assertInstanceOf('Some\Sample\Product', $restore);
// retrieving the content in the MongoDB
$dump = $this->collection->findOne(array('_id' => $doc->getId()));
$this->assertEquals('product', $dump['-class']);  // here is the aliasing
$this->assertEquals('EF-85 L', $dump['title']);
$this->assertEquals(2000, $dump['price']);
```

### Hoodoo child
If you need to evolve the model above, you can use "Hoodoo" stage, a
"WhiteMagic" stage mixed with some magic from "BlackMagic" stage. There is a
safety net to prevent some "real" classes to become "fake" classes. This lowers
the rate of WTF per minutes and you choose the level of magic.

## About MDE
With recent concepts of NoSQL, SOA and HMVC, I believe MDE is somewhat
past-history, not always but very often.
It is more suited for V-cycle and fits with difficulties in agile process (except
if your CTO is good enough)

With MDE, the first task is to design and
code the model. It is difficult to parallelize this part, it's blocking everything
else to come. Because of constraints of RDBMS, it drives to over-engineering
(for my experience) and meanwhile, the customer waits and sees nothing.

## About performance

I have not fully tested this dbal but the hydration is the slowest part of this
layer. For example, one document with 100 entities (think about a customer,
with his addresses, past orders with products etc...)
takes about 100 ms to be stored on a standard dual-core desktop without APC.

It is not very efficient but when you seek high performance, you can
forget about ORM, ODM and so on.

## FAQ

### How to map properties ?
All *object's* properties are stored. You have only one thing to do :
The root classes must implement the Persistable interface.

### What is a "root class" ?
It is a class stored in the collection, which contains the MongoId in the key '_id'.
All other agregated objects in this class don't need to implement Persistable, they are
recursively stored.

### How can I remove some transient properties ?
You can't. But you can have a transient class with the interface Skippable.
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

### I have seen the ReflectionClassBC, it is ugly
That's right. [PHPUnit does the same for Mockup][*4], by the way.
But the gain is you don't require PHP 5.4 to use
this lib. And this hack is encapsuled and can be easily removed.

### I see you're using mongo types in your classes model, what about abstraction ?
Abstraction is good but seriously, we're talking about performance here...
Have you ever switch an app to another database ?

### Is there any other constraints you have used ?
* Minimum number of switch because it is hidden inheritance
* No more than 5 methods per class
* No method longer than 20 NCLOC
* No static because it is global
* SRP, OCP, LSP, ISP, DIP at maximum level
* coupling at minimum level

### Is there any lazy loading or proxy classes for DBRef ?
Fly, you fools

## TODO

 * Storing DBRef properly but no automagic or whatsoever
 * Make a Trait for Persistable

## Why this silly name ?
Well, I'm not good at finding names, that's why I tend to keep the most ridiculous
than the most serious. It stands for Docu(ment) + doki to recall
"dokidoki" (loosely means "excitment", sounds like heartbeats) in japanese.
This one, I'm pretty sure it is unique ^_^

[*1]: https://github.com/doctrine/doctrine2/blob/master/lib/Doctrine/ORM/UnitOfWork.php#L2446
[*2]: http://www.mongodb.org/
[*3]: http://en.wikipedia.org/wiki/Object_database
[*4]: https://github.com/sebastianbergmann/phpunit-mock-objects/blob/1.1.1/PHPUnit/Framework/MockObject/Generator.php#L232
[*5]: http://en.wikipedia.org/wiki/Object-relational_mapping
[*7]: http://www.elasticsearch.org/
[*10]: http://en.wikipedia.org/wiki/Keep_it_simple_stupid
[*12]: https://github.com/Trismegiste/DokudokiBundle/blob/master/Tests/ReadmeExampleTest.php
[*13]: https://github.com/Trismegiste/DokudokiBundle/blob/master/Tests/ReadmeExampleTest.php#L47
[*14]: https://github.com/Trismegiste/DokudokiBundle/blob/master/Tests/ReadmeExampleTest.php#L75
[*15]: https://github.com/Trismegiste/DokudokiBundle/blob/master/Tests/ReadmeExampleTest.php#L91
