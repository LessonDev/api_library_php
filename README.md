# Test materielelectrique.com

L'objectif de ce test est de réaliser 3 apis liées à une gestion des 
livres dans une bibliothèque

Le temps nécessaire pour la réalisation du test est de 2 à 3 heures.

Pour information, il n'est pas autorisé d'utiliser un framework développé par
des tiers. Vous pouvez par contre réutiliser des scripts écrits par vos soins
si vous le souhaitez.

## livrable
Merci de fournir un fichier zip de l'ensemble du test réalisé et notes au besoin. 


## description des apis
 
###1. GET /books/{id}

cette api doit renvoyer un livre demandé

#### Input

_{id}_ est l'identifiant du livre

#### Output 

Un livre au format JSON

exemple de sortie

```
{
"data":{
    "id": 6,
    "type": "book",
    "title": "1984",
    "author": {
        "id": 3,
        "name": "George Orwell"
    }
  }
}
```
 
###2. POST /books

cette api doit permettre de créer un livre

#### Input

liste des parametres (tous sont obligatoires)
* __title__, titre du livre
* __author__, id de l'auteur

#### Output 

Le livre crée (cf la sortie de l'api GET /books/{id} )

###3. GET /books

cette API doit renvoyer la liste des livres dans le catalogue

 
#### Input
 
 l'api accepte le parametre _order_ qui peut prendre les valeurs _author_ ou _title_ et triera les livres par auteur ou par titre

#### Output 
 
 Une liste au format JSON
 
exemple:

```
{
"data": [
  {
    "id": 1,
    "type": "book",
    "title": "Good omens",
    "author": {
        "id": 1,
        "name": "Terry Pratchett"
    }
  },
  {
    "id": 3,
    "type": "book",
    "title": "Mort",
    "author": {
        "id": 1,
        "name": "George Orwell"
    }
  },
  {
    "id": 6,
    "type": "book",
    "title": "1984",
    "author": {
        "id": 1,
        "name": "George Orwell"
    }
  }
]
}
```
 
 ###4. GET /author/{name}/books
 
 cette api doit renvoyer la liste des livre disponible pour un auteur donné
 
 #### Input
 
 _{name}_ est le nom de l'auteur en minuscule et des "_" remplacent les espaces
 
 l'api accepte le parametre _order_ qui peut prendre les valeurs _id_ ou _title_ et triera les livres selon leurs ID ou leurs Titre

 #### Output 
 
 Une liste au format JSON
 
 exemple de sortie
 
 ```
{
    "data": [
      {
        "id": 1,
        "type": "book",
        "title": "Good omens",
      },
      {
        "id": 3,
        "type": "book",
        "title": "Mort"
      }
    ]
}
```

###5. PATCH /books/{id}

cette API doit modifier avec method PATCH la livre dans le catalogue

#### Input

_{id}_ est l'identifiant du livre

#### Output

Un livre au format JSON

###6. PUT /books/{id}

cette API doit modifier avec method PUT la livre dans le catalogue

#### Input

_{id}_ est l'identifiant du livre

#### Output

Un livre au format JSON

###7. DELETE /books/{id}

cette API doit supprimer la livre dans le catalogue

#### Input

_{id}_ est l'identifiant du livre

#### Output

Un livre au format JSON

###8. DELETE /author/{id}

cette API doit supprimer la livre dans le catalogue

#### Input

_{id}_ est l'identifiant du livre

#### Output

Un livre au format JSON