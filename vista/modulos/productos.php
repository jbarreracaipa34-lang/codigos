<script src="vista/js/cl_productos.js"></script>

<div class="container-fluid px-4 my-4">
    <div class="p-4 mb-4 bg-light rounded-3 border shadow-sm">
        <div class="container-fluid">
            <h1 class="display-6 fw-bold text-primary">Gestión de Productos</h1>
            <p class="col-md-8 fs-6 text-muted mb-0">
                Administra el inventario, registro, edición de productos y generación de códigos de barras.
            </p>
        </div>
    </div>

    <div id="panelPrincipalProductos">
        <div class="row g-4">
            <div class="col-xl-3 col-lg-4 col-md-5">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white fw-bold py-3">
                        <i class="bi bi-box-seam-fill me-2"></i>Registrar Producto
                    </div>
                    <div class="card-body p-4">
                        <form id="formProducto" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="txt-stock" class="form-label fw-semibold">Stock</label>
                                <input type="number" class="form-control" id="txt-stock" placeholder="Cantidad disponible" required>
                                <div class="invalid-feedback">
                                    Ingrese la cantidad de stock.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="txt-precio" class="form-label fw-semibold">Precio</label>
                                <input type="number" class="form-control" id="txt-precio" step="0.01" placeholder="Ej: 99.99" required>
                                <div class="invalid-feedback">
                                    Ingrese un precio válido.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="txt-descripcion" class="form-label fw-semibold">Descripción</label>
                                <input type="text" class="form-control" id="txt-descripcion" placeholder="Nombre o descripción corta" required>
                                <div class="invalid-feedback">
                                    Ingrese la descripción del producto.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="txt-url-foto" class="form-label fw-semibold">Imagen del Producto</label>
                                <input type="file" class="form-control" id="txt-url-foto" accept=".jpg,.png,.webp,.svg,.jpeg" required>
                                <div class="invalid-feedback">
                                    Seleccione una imagen para el producto.
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                                <i class="bi bi-save me-1"></i> Agregar Producto
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xl-9 col-lg-8 col-md-7">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3 border-bottom">
                        <i class="bi bi-boxes me-2 text-primary"></i>Lista de Productos
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table id="tablaProductos" class="table table-hover align-middle w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 100px;">Foto</th>
                                        <th scope="col">Stock</th>
                                        <th scope="col">Precio</th>
                                        <th scope="col">Descripción</th>
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

    <div id="panelFormularioEditarProductos" style="display: none;">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom">
                        <span class="fw-bold fs-5 text-primary">
                            <i class="bi bi-pencil-square me-2"></i>Editar Producto
                        </span>
                        <button id="btn-RegresarEditar" type="button" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Regresar a la lista
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <form id="formEditarProductos" class="needs-validation" novalidate>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="txt_edit_stock" class="form-label fw-semibold">Stock</label>
                                    <input type="number" class="form-control" id="txt_edit_stock" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese el Stock.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="txt_edit_precio" class="form-label fw-semibold">Precio</label>
                                    <input type="number" class="form-control" id="txt_edit_precio" step="0.01" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese el Precio.
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="txt_edit_descripcion" class="form-label fw-semibold">Descripción</label>
                                    <input type="text" class="form-control" id="txt_edit_descripcion" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese la Descripción.
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <label class="form-label fw-semibold d-block">Imagen Actual</label>
                                    <div class="d-flex align-items-center gap-3 p-2 bg-light rounded border">
                                        <img id="img_edit_url_Foto_pro" src="" alt="Imagen del producto" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid #ccc;">
                                        <div class="flex-grow-1">
                                            <label for="txt_edit_url_Foto_pro" class="form-label mb-1 text-muted small">Seleccionar nueva imagen:</label>
                                            <input type="file" class="form-control form-control-sm" id="txt_edit_url_Foto_pro" accept=".jpg,.png,.webp,.svg,.jpeg">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-4 d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-secondary" id="btn_CancelarEditarProducto">Cancelar</button>
                                    <button id="btnEditarProductos" class="btn btn-primary px-4" type="submit">
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

<script src="vista/js/productos.js"></script>
