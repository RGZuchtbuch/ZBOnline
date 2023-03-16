# RGZuchtbuch Technical Design

## Guide
In this document we'll try to picture decisions and solutions that shall help keeping the project understandable and thus consistent.
For diagrams we use the PlantUml in Markdown option witch should be enabled in your reader.  
The project is developed using PHPSTorm that supports js, php sql and plantuml (plugin).

## Introduction
* Vision
* Frameworks
* 

## System
For the system it is chosen te enable simple webhosting providing Apache, PHP, MySQL/MariaDB.  
Its a Single Page Application, using [Svelte](https://svelte.dev/) with [TailwindCss](https://tailwindcss.com/) and the [Tinro](https://github.com/AlexxNB/tinro) frontend router.
The SPA relies on the backend for a serving the SPA and static files as well as the API and a few special PHP scripts like for excel support. Communication between SPA and API is by JSON syntax.

## SPA
// TODO
```puml

    component Article
    component GeoMap
    component TimeLine
    component Districts
    component District
    component Members
    component Member
    component ResultsList
    component ResultsEdit
    component Sections
    component Section
    component Breeds
    component Breed
    component Colors
    component Color
    
    component App {
        component Router
        component Info
        component Standard
        component Results
        component Breeder
        component Moderator
        component Admin
    }
    
    Router --> Info
    Router --> Standard
    Router --> Results
    Router --> Breeder 
    Router --> Moderator
    Router --> Admin
    
    Info -d-> Article
    Standard -d-> Sections
    Sections-d-> Section
    Section -d-> Breeds
    Breeds -d-> Breed
    Breed -d-> Colors
    Colors -d-> Color
    
    Results -d-> GeoMap
    Results -d-> TimeLine
    Results -d-> ResultsList
    Moderator -d-> Districts
    Districts -d-> District
    District -d-> Members
    District -d-> ResultsList
    District -d-> ResultsEdit
    Members -d-> Member
```
## API
The API is PHPP based to support simple, cheap,  webhosting (LAMP)
Our target is Apache, PHP 8.x, MARIADB 10.1

We use server side routing, with the SLIM Framework v4.x. This allows for nice urls.
On top of this a three layered structure is chosen. The router forwards the request to the controller that is responsible for authenticating and authorization. Then the Controller can use Models to get needed data. Models are a layer around the queries. Some query abstraction is applied here through the query class. 

```puml
    class Client
    package Server {
        class Router
        class Controller {
            authorized()
            process()
        }
        class Model {
            get()
            set()
            del()
        }
        class Query {
            prepare()
            execute()
        }
    }
    
    Client -r-> Router
    Router -r-> Controller
    Controller -r-> Model
    Model -r-> Query
```


### Controller

### Model

### Query


## Excel support

## Database
All data is stored in the database.  
The target Database is MARIADB 10.1. Unfortunately this does not support CTE's yet, but an providers update should make this available ( ~ summer 2023 ).

This caused some issues writing the recursive queries. First solution was to use sql variables tot mimic cte's but the use of variables apperared to give unpredictable results, as the setting and updating order of the variable in the query is not garantied in mysql 8 and MariaDB animore. 
For that reason the iterative setup was chosen, in these case to check if a breed is in a section or one of it's descendants, or a result in a district  or one of it's descendants.

    WHERE breed.sectionId IN (
        SELECT distinct child.id FROM section AS parent
		    LEFT JOIN section AS child ON child.id = parent.id OR child.parentId = parent.id
		WHERE parent.id=:sectionId OR parent.parentId=:sectionId
    )

and

    WHERE result.districtId IN (
        SELECT distinct child.id FROM district AS parent
		    LEFT JOIN district AS child ON child.id = parent.id OR child.parentId = parent.id
		WHERE parent.id=:districtId OR parent.parentId=:districtId
    )

### DB Schema

### Recursive queries

The database structure has been design 


### Todo
* Stored procedures ?
