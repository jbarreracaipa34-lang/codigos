<script src="vista/js/cl_usuario.js"></script>

<div class="container-fluid px-4 my-4">
    <div class="p-4 mb-4 bg-light rounded-3 border shadow-sm">
        <div class="container-fluid">
            <h1 class="display-6 fw-bold text-primary">Gestión de Usuarios</h1>
            <p class="col-md-8 fs-6 text-muted mb-0">
                Administra el registro, edición, generación de carnets y códigos QR para cada usuario.
            </p>
        </div>
    </div>

    <div id="panelPrincipalUsuarios">
        <div class="row g-4">
            <div class="col-xl-3 col-lg-4 col-md-5">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white fw-bold py-3">
                        <i class="bi bi-person-plus-fill me-2"></i>Registrar Usuario
                    </div>
                    <div class="card-body p-4">
                        <form id="formUsuarios" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="txt-documento" class="form-label fw-semibold">Documento</label>
                                <input type="text" class="form-control" id="txt-documento" name="documento" inputmode="numeric" pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Solo números" required>
                                <div class="invalid-feedback">
                                    Ingrese un documento válido (solo números).
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="txt-nombre" class="form-label fw-semibold">Nombre Completo</label>
                                <input type="text" class="form-control" id="txt-nombre" name="nombres" placeholder="Nombres y apellidos" required>
                                <div class="invalid-feedback">
                                    Ingrese el nombre completo.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="txt-email" class="form-label fw-semibold">Correo Electrónico</label>
                                <input type="email" class="form-control" id="txt-email" name="email" placeholder="correo@ejemplo.com" required>
                                <div class="invalid-feedback">
                                    Ingrese un correo electrónico válido.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="txt-telefono" class="form-label fw-semibold">Teléfono</label>
                                <input type="tel" class="form-control" id="txt-telefono" name="telefono" inputmode="numeric" pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Solo números" required>
                                <div class="invalid-feedback">
                                    Ingrese un número de teléfono válido.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="txt-url_foto" class="form-label fw-semibold">Foto de Perfil</label>
                                <input type="file" class="form-control" id="txt-url_foto" name="foto" accept=".jpg,.png,.webp,.svg,.jpeg" required>
                                <div class="invalid-feedback">
                                    Seleccione una imagen para el usuario.
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                                <i class="bi bi-save me-1"></i> Agregar Usuario
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xl-9 col-lg-8 col-md-7">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3 border-bottom">
                        <i class="bi bi-people-fill me-2 text-primary"></i>Lista de Usuarios
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table id="tablaUsuarios" class="table table-hover align-middle w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 90px;">Foto</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Documento</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Teléfono</th>
                                        <th scope="col" class="text-center text-nowrap">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="panelFormularioEditarUsuario" style="display: none;">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom">
                        <span class="fw-bold fs-5 text-primary">
                            <i class="bi bi-pencil-square me-2"></i>Editar Usuario
                        </span>
                        <button id="btn_RegresarEditar" type="button" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Regresar a la lista
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <form id="formEditarUsuarios" class="needs-validation" novalidate>
                            <input type="hidden" id="txt_edit_idusuario">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="txt_edit_documento" class="form-label fw-semibold">Documento</label>
                                    <input type="text" class="form-control" id="txt_edit_documento" inputmode="numeric" pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Solo números" required>
                                    <div class="invalid-feedback">
                                        Ingrese un documento válido.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="txt_edit_nombre" class="form-label fw-semibold">Nombre Completo</label>
                                    <input type="text" class="form-control" id="txt_edit_nombre" required>
                                    <div class="invalid-feedback">
                                        Ingrese el nombre.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="txt_edit_email" class="form-label fw-semibold">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="txt_edit_email" required>
                                    <div class="invalid-feedback">
                                        Ingrese un correo válido.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="txt_edit_telefono" class="form-label fw-semibold">Teléfono</label>
                                    <input type="tel" class="form-control" id="txt_edit_telefono" inputmode="numeric" pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                    <div class="invalid-feedback">
                                        Ingrese un teléfono válido.
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <label class="form-label fw-semibold d-block">Foto Actual</label>
                                    <div class="d-flex align-items-center gap-3 p-2 bg-light rounded border">
                                        <img id="img_edit_preview" src="" alt="Foto actual" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px; border: 1px solid #ccc;">
                                        <div class="flex-grow-1">
                                            <label for="txt_edit_url_foto" class="form-label mb-1 text-muted small">Seleccionar nueva foto:</label>
                                            <input type="file" class="form-control form-control-sm" id="txt_edit_url_foto" accept=".jpg,.png,.webp,.svg,.jpeg" required>
                                            <div class="invalid-feedback">
                                                Seleccione la imagen para actualizar.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-4 d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-secondary" id="btn_CancelarEditar">Cancelar</button>
                                    <button id="btn_GuardarEditarUsuario" class="btn btn-primary px-4" type="submit">
                                        <i class="bi bi-check-lg me-1"></i> Guardar Cambios
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="vista/js/usuario.js"></script>