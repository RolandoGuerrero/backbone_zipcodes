# Reto Backbone Zipcodes
### Definición del problema
Importar base de datos de los códigos postales de México otorgada por el SERVICIO POSTAL MEXICANO y habilitar un endpoint para obtener la información específica de un código postal.

## Resolución
Se revisó la estructura de los datos y se hizo un diagrama de base datos para la generación de modelos, en el cual resultaron los siguientes:

- `FederalEntity`
- `Municipality` (Relacionada a `FederalEntity`)
- `ZipCode` (Relacionada a `FederalEntity`, Relacionada a `Municipality`)
- `SettlementType` 
- `Settlements`  (Relacionada a `ZipCode`, Relacionada a `SettlementType`)

Se descargó el archivo con los datos de los códigos postales y posteriormente fue guardado por múltiples archivos csv, estos archivos csv fueron agregados al proyecto para ser leídos por un seeder (`ZipcodeCsvSeeder`), el seeder hace una iteración por todos los archivos csv hace uso de una clase `CsvImporter` para hacer la importación de datos de casa archivo.

### CsvImporter Class
Se creó una clase `CsvImporter` padre siguiendo las convenciones de Strategy Pattern que laravel ya aplica un muchos de sus características y se creó el adaptador `PostalMXCsvImporter` este adaptador hace el proceso específico de un csv con los headers presentados por el SERVICIO POSTAL MEXICANO, después del procesamiento se guarda en la base de datos la información. 

Si en un futuro se quisiera hacer uso de otro tipo de csv con header diferentes solo se debería crear un nuevo adapter que funcione con este csv, permitiendo así que la clase `CsvImporter` crezca sin que suponga un cambio en el funcionamiento original.

### Endpoint
Se dio de alta el endpoint `/api/zip-codes/{zipcode}` el cual es trabajo por el controlador `GetZipcodeController`, este controlador hace el llamado a un clase de acción llamada `GetZipCode` para obtener la información del código postal junto con sus relaciones.

### GetZipCode Action Class
Esta clase sigue la convenciones de Action Pattern presentado por laravel en los últimos años, la idea es abstraer la lógica de negocios a una clase única la cual procesa el request y responde según sea el caso. En el caso de esta clase hace la búsqueda del código postal dado como parámetro primero revisa si ese código postal ha sido guardado en cache de ser encontrado  lo retorna si no procede a buscar en la base de datos y crea un registro en caché con la información y regresa retorna, en caso de que el código postal ingresado no exista lanzará una excepción `ModelNotFoundException`. El uso de este patrón de diseño nos permite que al estar la lógica de negocios abstraída a una sola clase pueda ser llamada por varios puntos de entrada en nuestro caso fue un controlador, pero se puede hacer los mismo para un job, un comando, etc.

Sobre el uso del caché opte por la implementación ya que son registros que no son editados muy seguido y da la oportunidad de hacer uso de esta herramienta para bajar la latencia en la respuesta de la acción.

### Pruebas.
Se crearon pruebas unitarias para cada clase aplicada en el proyecto en nuestro caso para el controlador, la acción y el importador. Cada test validó el funcionamiento de forma correcta tanto en los escenarios donde el resultado es válido como los escenarios donde la respuesta es un error o una excepción.

#### Prueba local.
Para hacer una prueba local basta con:

Hacer clon del proyecto:
```sh
git clone git@github.com:RolandoGuerrero/backbone_zipcodes.git
```
Posteriormente correr:
```sh
docker-compose up
```
Finalmente correr:
```sh
docker compose exec app php artisan test
```
