# 🍎🥕 Fruits and Vegetables
## 🎯 Goal
We want to build a service which will take a `request.json` and:
* Process the file and create two separate collections for `Fruits` and `Vegetables`
* Each collection has methods like `add()`, `remove()`, `list()`;
* Units have to be stored as grams;
* Store the collections in a storage engine of your choice. (e.g. Database, In-memory)
* Provide an API endpoint to query the collections. As a bonus, this endpoint can accept filters to be applied to the returning collection.
* Provide another API endpoint to add new items to the collections (i.e., your storage engine).
* As a bonus you might:
  * consider giving option to decide which units are returned (kilograms/grams);
  * how to implement `search()` method collections;
  * use latest version of Symfony's to embbed your logic 

### ✔️ How can I check if my code is working?
You have two ways of moving on:
* You call the Service from PHPUnit test like it's done in dummy test (just run `bin/phpunit` from the console)

or

* You create a Controller which will be calling the service with a json payload

## 💡 Hints before you start working on it
* Keep KISS, DRY, YAGNI, SOLID principles in mind
* Timebox your work - we expect that you would spend between 3 and 4 hours.
* Your code should be tested


## Getting Started

Make sure you have Docker and Docker Compose installed on your machine.

1. **Run the Following Commands**

   Use the provided Makefile to manage Docker Compose commands. Run the following command to start the project:
   The commands are with docker compose so if needed you must change

   ```bash
   make build
   make up
   make install-composer

2. **RUN tests
   ```bash
   make clear-redis
   make test

## Usage

Once the setup is complete, you can access the following features:

To create a collection and create from  file `request.json`, you can use the following endpoint:

POST : http://127.0.0.1:8000/import -> This will create a collection and add items from the file

GET : http://localhost:8000/products -> This will list all products [possibility to add category filter (category=fruit)]

GET : http://localhost:8000//products/search?query= -> This will get all products that contain the query in their name

GET : http://localhost:8000/products/{id} -> This will list the product by id

DELETE : http://localhost:8000/products/{id} -> This will delete the product by id

POST : http://localhost:8000/products -> This will add a product to the collection
   ```json
    "name": "Almond",
    "quantity": 10,
    "category": "fruit",
    "unit": "g"
    }
