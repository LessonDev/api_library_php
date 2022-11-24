# api_library_php

L'objectif de ce test est de réaliser 8 apis liées à une gestion des 
livres dans une bibliothèque


## description des apis
 
###1. GET /books/{id}

cette api doit renvoyer un livre demandé

#### Input

_{id}_ est l'identifiant du livre

#### Output 

Un livre au format JSON

    exemple : 
    {
        "data": [
            {
                "id": 1,
                "title": "The Colour of Magic",
                "type": "book",
                "author": {
                    "id": 2,
                    "name": "Terry Pratchett"
                }
            }
        ]
    }
 
###2. POST /books

cette api doit permettre de créer un livre

#### Input

liste des parametres (tous sont obligatoires)
* __title__, titre du livre
* __author__, id de l'auteur

 
    exemple:
    {
        "title": "Harry Potter à l'école des sorciers",
        "author": 2
    }


#### Output 

Le livre crée (cf la sortie de l'api GET /books/{id} )
      
    exemple: 
    {
        "message": "books created",
        "GET": "/api_library_php/books/8"
    }

###3. GET /books

cette API doit renvoyer la liste des livres dans le catalogue

#### Input
 
 l'api accepte le parametre _order_ qui peut prendre les valeurs _author_ ou _title_ et triera les livres par auteur ou par titre

#### Output 
 
 Une liste au format JSON
 
exemple:

    exemple :
    {
     "data": [
            {
                "id": 1,
                "title": "The Colour of Magic",
                "type": "book",
                "author": {
                    "id": 2,
                    "name": "Terry Pratchett"
                }
            },
            {
                "id": 3,
                "title": "ff",
                "type": "book",
                "author": {
                    "id": 3,
                    "name": "George Orwell"
                }
            },
            { ....
 
 ###4. GET /author/{name}/books
 
 cette api doit renvoyer la liste des livre disponible pour un auteur donné
 
 #### Input
 
 _{name}_ est le nom de l'auteur en minuscule et des "_" remplacent les espaces
 
 l'api accepte le parametre _order_ qui peut prendre les valeurs _id_ ou _title_ et triera les livres selon leurs ID ou leurs Titre
 
     exemple : 
     /author/Terry_Pratchett/books
     
     {
         "order": "id"
     }

 #### Output 
 
 Une liste au format JSON
 
     exemple : 
      {
          "data": [
              {
                  "id": 1,
                  "type": "book",
                  "title": "The Colour of Magic"
              },
              {
                  "id": 2,
                  "type": "book",
                  "title": "Going Postal"
              },
              {
                  "id": 3,
                  "type": "book",
                  "title": "Thief of Time"
              }
          ]
      }
              

###5. PATCH /books/{id}

cette API doit modifier avec method PATCH la livre dans le catalogue

#### Input

_{id}_ est l'identifiant du livre

        exemple :
        {
            "title": "Harry Potter et la Chambre des secrets",
            "author": 2
        }

#### Output

Un livre au format JSON

       exemple : 
       {
            "message": "books 8 updated",
            "GET": "/api_library_php/books/8"
       }

###6. PUT /books/{id}

cette API doit modifier avec method PUT la livre dans le catalogue

#### Input

_{id}_ est l'identifiant du livre

    exemple :
    {
    "title": "Foundation",
    "author": 1
    }

#### Output

Un livre au format JSON

    exemple :
    {
    "message": "books 4 updated",
    "GET": "/api_library_php/books/4"
    }

###7. DELETE /books/{id}

cette API doit supprimer la livre dans le catalogue

#### Input

_{id}_ est l'identifiant du livre

#### Output

Un livre au format JSON

     exemple : 
         {
             "message": "books id = 2 is deleted"
         }

###8. DELETE /author/{id}

cette API doit supprimer la livre dans le catalogue

#### Input

_{id}_ est l'identifiant du livre

#### Output

Un livre au format JSON

    exemple : 
    {
        "message": "author id = 1 is deleted"
    }