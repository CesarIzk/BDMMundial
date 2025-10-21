<?php 
// Asumimos que tu 'view()' pasa $userData, $success, y $error
require 'partials/header.php'; 
?>

<div class="contenedor">
    <h2 class="titulo-seccion">Configuración de Perfil</h2>

    <?php if (isset($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

  <form action="/perfil/actualizar" method="POST" class="form-perfil">
    <h3>Información Personal</h3>

    <div class="form-group">
        <label for="Nombre">Nombre Completo</label>
        <input type="text" class="form-control" id="Nombre" name="Nombre"
               value="<?= htmlspecialchars($userData['Nombre'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label for="biografia">Biografía</label>
        <textarea class="form-control" id="biografia" name="biografia" rows="3">
            <?= htmlspecialchars($userData['biografia'] ?? '') ?>
        </textarea>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="fechaNacimiento">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento"
                       value="<?= htmlspecialchars($userData['fechaNacimiento'] ?? '') ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="genero">Género</label>
                <select class="form-select" id="genero" name="genero">
                    <option value="">Prefiero no decirlo</option>
                    <option value="Masculino" <?= ($userData['genero'] ?? '') === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                    <option value="Femenino" <?= ($userData['genero'] ?? '') === 'Femenino' ? 'selected' : '' ?>>Femenino</option>
                    <option value="Otro" <?= ($userData['genero'] ?? '') === 'Otro' ? 'selected' : '' ?>>Otro</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="ciudad">Ciudad</label>
                <input type="text" class="form-control" id="ciudad" name="ciudad"
                       value="<?= htmlspecialchars($userData['ciudad'] ?? '') ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="pais">País</label>
                <select class="form-select" name="pais" id="pais">
                    <option value="">Selecciona tu país</option>
                    <option value="Mexico" <?= ($userData['pais'] ?? '') === 'Mexico' ? 'selected' : '' ?>>México</option>
                    <option value="Colombia" <?= ($userData['pais'] ?? '') === 'Colombia' ? 'selected' : '' ?>>Colombia</option>
                    <option value="Argentina" <?= ($userData['pais'] ?? '') === 'Argentina' ? 'selected' : '' ?>>Argentina</option>
                    <option value="España" <?= ($userData['pais'] ?? '') === 'España' ? 'selected' : '' ?>>España</option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="email">Correo Electrónico</label>
        <input type="email" class="form-control" id="email" name="email"
               value="<?= htmlspecialchars($userData['email'] ?? '') ?>" required>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Actualizar Información</button>
</form>

    <hr class="divisor-perfil">

    <form action="/perfil/password" method="POST" class="form-perfil">
        <h3>Cambiar Contraseña</h3>
        
        <div class="form-group">
            <label for="contrasena_actual">Contraseña Actual</label>
            <input type="password" class="form-control" id="contrasena_actual" name="contrasena_actual" placeholder="••••••••" required>
        </div>
        
        <div class="form-group">
            <label for="contrasena_nueva">Nueva Contraseña</label>
            <input type="password" class="form-control" id="contrasena_nueva" name="contrasena_nueva" placeholder="••••••••" required>
        </div>
        
        <div class="form-group">
            <label for="contrasena_confirmar">Confirmar Nueva Contraseña</label>
            <input type="password" class="form-control" id="contrasena_confirmar" name="contrasena_confirmar" placeholder="••••••••" required>
        </div>
        
        <button type="submit" class="btn btn-success">Cambiar Contraseña</button>
    </form>

    <hr class="divisor-perfil">

    <div class="form-perfil zona-peligro">
        <h3>Zona de Peligro</h3>
        <p>Esta acción no se puede deshacer. Tu cuenta se marcará como inactiva y se cerrará tu sesión.</p>
        <form action="/perfil/deactivate" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres dar de baja tu cuenta?');">
            <button type="submit" class="btn btn-danger">Dar de baja mi cuenta</button>
        </form>
    </div>
</div>


<?php require 'partials/footer.php'; ?>