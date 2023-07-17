<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRUD</title>
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.0/css/bootstrap.min.css">
</head>

<body>
  <nav class="navbar navbar-dark">
    <div class="container">
      <span class="navbar-brand mb-0 h1">CRUD Example</span>
      <form id="buscar-artista-form" class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Buscar" id="buscar-artista-input">
        <button class="btn btn-outline-light" type="submit">Buscar</button>
      </form>
    </div>
  </nav>

  <div class="container">
    <h2 class="mt-3">Ver todos los registros</h2>

    <?php if (!empty($registros)) { ?>
      <table class="table table-dark mt-3">
        <thead>
          <tr>
            <th>ID</th>
            <th>Artista</th>
            <th>Técnica de dibujo</th>
            <th>Técnica de pintura</th>
            <th>Estilo</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($registros as $registro) { ?>
            <tr>
              <td><?php echo $registro->id; ?></td>
              <td><?php echo $registro->artista; ?></td>
              <td><?php echo $registro->tecnicadibujo; ?></td>
              <td><?php echo $registro->tecnicapintura; ?></td>
              <td><?php echo $registro->estilo; ?></td>
              <td>
                <button type="button" class="btn btn-primary btn-actualizar" data-id="<?php echo $registro->id; ?>">Actualizar</button>
                <button type="button" class="btn btn-danger btn-eliminar" data-id="<?php echo $registro->id; ?>">Eliminar</button>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <p>No hay registros.</p>
    <?php } ?>

    <h2 class="mt-3">Nuevo Artista</h2>

    <form id="nuevo-artista-form">
      <div class="form-group">
        <label for="artista">Artista:</label>
        <input type="text" class="form-control" id="artista" name="artista" required>
      </div>
      <div class="form-group">
        <label for="tecnicadibujo">Técnica de dibujo:</label>
        <input type="text" class="form-control" id="tecnicadibujo" name="tecnicadibujo" required>
      </div>
      <div class="form-group">
        <label for="tecnicapintura">Técnica de pintura:</label>
        <input type="text" class="form-control" id="tecnicapintura" name="tecnicapintura" required>
      </div>
      <div class="form-group">
        <label for="estilo">Estilo:</label>
        <input type="text" class="form-control" id="estilo" name="estilo" required>
      </div>
      <button type="submit" class="btn btn-primary">Guardar</button>
    </form>

    <div id="message" class="mt-3"></div>
    <div id="error" class="mt-3"></div>
  </div>

  <!-- Modal para Actualizar Artista -->
  <div class="modal fade" id="actualizarArtistaModal" tabindex="-1" aria-labelledby="actualizarArtistaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="actualizarArtistaModalLabel">Actualizar Artista</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="actualizar-artista-form">
            <div class="form-group">
              <label for="artista-actualizar">Artista:</label>
              <input type="text" class="form-control" id="artista-actualizar" name="artista" required>
            </div>
            <div class="form-group">
              <label for="tecnicadibujo-actualizar">Técnica de dibujo:</label>
              <input type="text" class="form-control" id="tecnicadibujo-actualizar" name="tecnicadibujo" required>
            </div>
            <div class="form-group">
              <label for="tecnicapintura-actualizar">Técnica de pintura:</label>
              <input type="text" class="form-control" id="tecnicapintura-actualizar" name="tecnicapintura" required>
            </div>
            <div class="form-group">
              <label for="estilo-actualizar">Estilo:</label>
              <input type="text" class="form-control" id="estilo-actualizar" name="estilo" required>
            </div>
            <input type="hidden" id="id-actualizar" name="id">
            <button type="submit" class="btn btn-primary">Actualizar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script>
    // Función para mostrar mensajes
    function showMessage(message, type) {
      var alertClass = type === 'error' ? 'alert-danger' : 'alert-success';
      var messageHtml = '<div class="alert ' + alertClass + '">' + message + '</div>';
      $('#message').html(messageHtml);
      setTimeout(function() {
        $('#message').html('');
      }, 5000);
    }

    // Función para mostrar errores
    function showError(error) {
      var errorHtml = '<div class="error">' + error + '</div>';
      $('#error').html(errorHtml);
    }

    // Nuevo Artista
    $('#nuevo-artista-form').submit(function(event) {
      event.preventDefault();
      var formData = $(this).serialize();
      $.ajax({
        url: '/home/nuevoArtista',
        type: 'POST',
        data: formData,
        success: function(response) {
          $('#nuevo-artista-form')[0].reset();
          showMessage(response.message, 'success');
        },
        error: function(xhr, status, error) {
          var response = JSON.parse(xhr.responseText);
          showError(response.error);
        }
      });
    });

    // Eliminar Artista
    $('.btn-eliminar').click(function() {
      var id = $(this).data('id');
      if (confirm('¿Estás seguro de que quieres eliminar este artista?')) {
        $.ajax({
          url: '/home/eliminarArtista',
          type: 'DELETE',
          data: JSON.stringify({ id: id }),
          contentType: 'application/json',
          success: function(response) {
            showMessage(response.message, 'success');
          },
          error: function(xhr, status, error) {
            var response = JSON.parse(xhr.responseText);
            showError(response.error);
          }
        });
      }
    });

    // Actualizar Artista
	$('.btn-actualizar').click(function() {
  var id = $(this).data('id');
  // Obtener los datos del artista por su ID
  $.ajax({
    url: '/home/buscar',
    type: 'POST',
    data: JSON.stringify({ id: id }),
    contentType: 'application/json',
    success: function(response) {
      // Rellenar los campos del formulario con los datos del artista
      $('#id-actualizar').val(response.id);
      $('#artista-actualizar').val(response.artista);
      $('#tecnicadibujo-actualizar').val(response.tecnicadibujo);
      $('#tecnicapintura-actualizar').val(response.tecnicapintura);
      $('#estilo-actualizar').val(response.estilo);
      // Mostrar el modal de actualización de artista
      $('#actualizarArtistaModal').modal('show');
    },
    error: function(xhr, status, error) {
      var response = JSON.parse(xhr.responseText);
      showError(response.error);
    }
  });
});

    // Actualizar Artista - Enviar formulario
    $('#actualizar-artista-form').submit(function(event) {
      event.preventDefault();
      var formData = $(this).serialize();
      $.ajax({
        url: '/home/actualizarArtista',
        type: 'PUT',
        data: formData,
        success: function(response) {
          $('#actualizar-artista-form')[0].reset();
          $('#actualizarArtistaModal').modal('hide');
          showMessage(response.message, 'success');
        },
        error: function(xhr, status, error) {
          var response = JSON.parse(xhr.responseText);
          showError(response.error);
        }
      });
    });

    // Buscar
    $('#buscar-artista-form').submit(function(event) {
      event.preventDefault();
      var searchTerm = $('#buscar-artista-input').val();
      // Realizar la búsqueda con el término proporcionado
      $.ajax({
        url: '/home/buscar',
        type: 'POST',
        data: JSON.stringify({ id: searchTerm }),
        contentType: 'application/json',
        success: function(response) {
          // Manejar la respuesta de búsqueda
        },
        error: function(xhr, status, error) {
          var response = JSON.parse(xhr.responseText);
          showError(response.error);
        }
      });
    });
  </script>
</body>

</html>



