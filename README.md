# aivo-spotify-integration
Utilizando la api de spotify crear un endpoint al que ingresando el nombre de la banda se obtenga un array de toda la discografia, cada disco debe tener este formato:

```
[
    {
        "name": "Album Name",
        "released": "10-10-2010",
         "tracks": 10,
         "cover": {
             "height": 640,
             "width": 640,
             "url": "https://i.scdn.co/image/6c951f3f334e05ffa"
         }
     }
]
```
El endpoint debe ser el siguiente:
```
http://localhost/api/v1/albums?q=<band-name>
```
## Instalación y ejecución
- Abrir una terminal y ejecutar:
  
  ```
  $ git clone https://github.com/cpsoberats/aivo-spotify-integration.git
  $ cd aivo-spotify-integration
  $ composer install
  $ composer start
  ```
- Para ejecutar las pruebas:
  ```
  $ composer test
  ```
## Tecnologías utilizadas
- Slim3
- Guzzle


