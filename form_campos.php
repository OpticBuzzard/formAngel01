<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Alias *</label>
        <input type="text" class="form-control" name="alias" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Tipo de Dispositivo *</label>
        <select class="form-select" name="id_tipo_dispositivo" required>
            <option value="">Seleccione...</option>
            <?php foreach($tiposDispositivo as $tipo): ?>
                <option value="<?= $tipo['id'] ?>"><?= $tipo['descripcion'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">MAC Address</label>
        <input type="text" class="form-control mac-mask" name="mac_address" placeholder="XX:XX:XX:XX:XX:XX">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Número de Serie</label>
        <input type="text" class="form-control" name="numero_serie">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Modelo</label>
        <input type="text" class="form-control" name="modelo">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Fabricante</label>
        <input type="text" class="form-control" name="fabricante">
    </div>
</div>