<?php

namespace App\Controllers;
// use App\Models


class Home extends BaseController
{

 //-------------------------Buscar por id---------------------------

 public function buscar()
 {
     // Obtener el contenido del cuerpo (body) de la solicitud
     $json = $this->request->getBody();

     // Decodificar el contenido JSON en un array asociativo
     $data = json_decode($json, true);

     // Verificar si se proporcionó el ID en el cuerpo de la solicitud
     if (isset($data['id'])) {
         // Conectarse a la base de datos
         $db = \Config\Database::connect();

         // Obtener el ID del array de datos
         $id = strval($data['id']);

         // Realizar la consulta para buscar el registro con el ID especificado
         $query = $db->table('datosartista')->where('id', $id)->get();

         // Verificar si se encontró el registro
         if ($query->getNumRows() > 0) {
             // Obtener el resultado de la consulta
             $result = $query->getRow();

             // Devolver el resultado como respuesta JSON
             return $this->response->setJSON($result);
         } else {
             // Si no se encontró el registro, devolver una respuesta de error
             return $this->response->setJSON(['error' => 'Registro no encontrado']);
         }
     } else {
         // Si no se proporcionó el ID en el cuerpo de la solicitud, devolver una respuesta de error
         return $this->response->setJSON(['error' => 'Falta el ID en el cuerpo de la solicitud']);
     }
 }

//-------------------------Ver todos los registros---------------------------

    public function verTodo()
    {
        if ($this->request->getMethod() === 'get') {
            $this->db = \Config\Database::connect();
            $query = $this->db->query("SELECT id, artista, tecnicadibujo, tecnicapintura, estilo FROM datosArtista");
            $result = $query->getResult();

            // Pasar los registros a la vista
            $data['registros'] = $result;

            // Cargar la vista CRUD.php
            return view('CRUD', $data);
        }

        // Si no es una solicitud GET, devolver una respuesta de error
        return $this->response->setJSON(['error' => 'Método no permitido']);
    }

 //-------------------------POST---------------------------

 public function nuevoArtista()
 {
     // Verificar si es una solicitud POST
     if ($this->request->getMethod() === 'post') {
         // Recuperar los datos de la solicitud POST en formato JSON
         $data = $this->request->getJSON(true);

         // Verificar que se hayan proporcionado todos los datos requeridos
         if (!isset($data['id'], $data['artista'], $data['tecnicadibujo'], $data['tecnicapintura'], $data['estilo'])) {
             return $this->response->setJSON(['error' => 'Faltan datos requeridos']);
         }

         // Conectarse a la base de datos
         $db = \Config\Database::connect();

         // Insertar los datos en la tabla de la base de datos
         $db->table('datosartista')->insert($data);

         // Devolver una respuesta de éxito
         return $this->response->setJSON(['message' => 'Datos guardados correctamente']);
     }

     // Si no es una solicitud POST, devolver una respuesta de error
     return $this->response->setJSON(['error' => 'Método no permitido']);
 }

 //-------------------------DELETE---------------------------

 public function eliminarArtista()
 {
     $request = service('request');

     // Verificar si es una solicitud DELETE
     if ($request->getMethod() === 'delete') {
         // Recuperar los datos de la solicitud DELETE
         $data = $request->getJSON(true);

         // Verificar que se haya proporcionado el identificador del artista
         if (!isset($data['id'])) {
             return $this->response->setJSON(['error' => 'Falta el identificador del artista']);
         }

         // Obtener el identificador del artista
         $id = strval($data['id']);

         // Conectarse a la base de datos
         $db = db_connect();

         // Eliminar el artista de la tabla de la base de datos
         $db->table('datosartista')->where('id', $id)->delete();

         // Devolver una respuesta de éxito
         return $this->response->setJSON(['message' => 'Artista eliminado correctamente']);
     }

     // Si no es una solicitud DELETE, devolver una respuesta de error
     return $this->response->setJSON(['error' => 'Método no permitido']);
 }

 //-------------------------PUT---------------------------

 public function actualizarArtista()
 {
     $request = service('request');

     // Verificar si es una solicitud PUT
     if ($request->getMethod() === 'put') {
         // Recuperar los datos de la solicitud PUT
         $data = $request->getJSON(true);

         // Verificar que se hayan proporcionado todos los datos requeridos
         if (!isset($data['id']) || !isset($data['artista']) || !isset($data['tecnicadibujo']) || !isset($data['tecnicapintura']) || !isset($data['estilo'])) {
             return $this->response->setJSON(['error' => 'Faltan datos requeridos']);
         }

         // Obtener los datos del artista
         $id = strval($data['id']);
         $artista = $data['artista'];
         $tecnicadibujo = $data['tecnicadibujo'];
         $tecnicapintura = $data['tecnicapintura'];
         $estilo = $data['estilo'];

         // Conectarse a la base de datos
         $db = db_connect();

         // Verificar si el artista existe en la base de datos
         if (!$db->table('datosartista')->where('id', $id)->countAllResults()) {
             return $this->response->setJSON(['error' => 'El artista no existe']);
         }

         // Actualizar los datos del artista en la tabla de la base de datos
         $db->table('datosartista')->where('id', $id)->update([
             'artista' => $artista,
             'tecnicadibujo' => $tecnicadibujo,
             'tecnicapintura' => $tecnicapintura,
             'estilo' => $estilo
         ]);

         // Devolver una respuesta de éxito
         return $this->response->setJSON(['message' => 'Artista actualizado correctamente']);
     }

     // Si no es una solicitud PUT, devolver una respuesta de error
     return $this->response->setJSON(['error' => 'Método no permitido']);
 }

//-------------------------Función para el navegador---------------------------
    public function CRUD(){

       
        if ($this->request->getMethod() === 'get') {
            $this->db = \Config\Database::connect();
            $query = $this->db->query("SELECT id, artista, tecnicadibujo, tecnicapintura, estilo FROM datosArtista");
            $result = $query->getResult();

            // Pasar los registros a la vista
            $data['registros'] = $result;

            // Cargar la vista CRUD.php
            return view('CRUD', $data);
        } elseif ($this->request->getMethod() === 'post') {
            // Recuperar los datos de la solicitud POST en formato JSON
            $data = $this->request->getJSON(true);

            // Verificar qué acción se está realizando
            if (isset($data['action'])) {
                $action = $data['action'];

                switch ($action) {
                    case 'nuevo':
                        // Verificar que se hayan proporcionado todos los datos requeridos
                        if (!isset($data['id'], $data['artista'], $data['tecnicadibujo'], $data['tecnicapintura'], $data['estilo'])) {
                            return $this->response->setJSON(['error' => 'Faltan datos requeridos']);
                        }

                        // Conectarse a la base de datos
                        $db = \Config\Database::connect();

                        // Insertar los datos en la tabla de la base de datos
                        $db->table('datosartista')->insert($data);

                        // Devolver una respuesta de éxito
                        return $this->response->setJSON(['message' => 'Datos guardados correctamente']);
                        case 'eliminar':
                        // Verificar que se haya proporcionado el identificador del artista
                        if (!isset($data['id'])) {
                            return $this->response->setJSON(['error' => 'Falta el identificador del artista']);
                        }

                        // Obtener el identificador del artista
                        $id = strval($data['id']);

                        // Conectarse a la base de datos
                        $db = db_connect();

                        // Eliminar el artista de la tabla de la base de datos
                        $db->table('datosartista')->where('id', $id)->delete();

                        // Devolver una respuesta de éxito
                        return $this->response->setJSON(['message' => 'Artista eliminado correctamente']);
                        case 'actualizar':
                        // Verificar que se hayan proporcionado todos los datos requeridos
                        if (!isset($data['id']) || !isset($data['artista']) || !isset($data['tecnicadibujo']) || !isset($data['tecnicapintura']) || !isset($data['estilo'])) {
                            return $this->response->setJSON(['error' => 'Faltan datos requeridos']);
                        }

                        // Obtener los datos del artista
                        $id = strval($data['id']);
                        $artista = $data['artista'];
                        $tecnicadibujo = $data['tecnicadibujo'];
                        $tecnicapintura = $data['tecnicapintura'];
                        $estilo = $data['estilo'];

                        // Conectarse a la base de datos
                        $db = db_connect();

                        // Verificar si el artista existe en la base de datos
                        if (!$db->table('datosartista')->where('id', $id)->countAllResults()) {
                            return $this->response->setJSON(['error' => 'El artista no existe']);
                        }

                        // Actualizar los datos del artista en la tabla de la base de datos
                        $db->table('datosartista')->where('id', $id)->update([
                            'artista' => $artista,
                            'tecnicadibujo' => $tecnicadibujo,
                            'tecnicapintura' => $tecnicapintura,
                            'estilo' => $estilo
                        ]);

                        // Devolver una respuesta de éxito
                        return $this->response->setJSON(['message' => 'Artista actualizado correctamente']);
                    default:
                        // Acción no válida
                        return $this->response->setJSON(['error' => 'Acción no válida']);
                }
            } else {
                // No se proporcionó una acción
                return $this->response->setJSON(['error' => 'Falta la acción en el cuerpo de la solicitud']);
            }
        }

        // Si no es una solicitud GET o POST, devolver una respuesta de error
        return $this->response->setJSON(['error' => 'Método no permitido']);
    }
  
}


