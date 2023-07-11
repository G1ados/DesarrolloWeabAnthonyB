<?php

namespace App\Controllers;
// use App\Models


class Home extends BaseController
{


    public function index()
    {
        return view('welcome_message');
    } 
    

    public function prueba ()
    {
        echo 'hola esto es una prueba';
    }



   

    public function login(){
        return view('login');
    
    }

//-------------------------Buscar por id---------------------------

	
	  public function buscar($id)
    {

        $this->db=\Config\Database::connect();
        $query=$this->db->query("SELECT id, artista, tecnicadibujo, 
        tecnicapintura, estilo FROM datosArtista where id='$id'  ");
        $result=$query->getResult();
        return $this->response->setJSON($result);

    }
//-------------------------Ver todos los registros---------------------------
    public function verTodo()
    {

        $this->db=\Config\Database::connect();
        $query=$this->db->query("SELECT id, artista, tecnicadibujo, 
        tecnicapintura, estilo FROM datosArtista");
        $result=$query->getResult();
        return $this->response->setJSON($result);

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
  
}
