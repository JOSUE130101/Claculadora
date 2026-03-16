<?php
// calculadoras/calculadora-interes.php

// Depuración - Verificar si el archivo se está cargando
error_log("calculadora-interes.php se está ejecutando");
echo "<!-- DEBUG: calculadora-interes.php cargado correctamente -->";

$resultado_interes = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calc_type']) && $_POST['calc_type'] === 'interes') {
    // Obtener valores del formulario
    $deposito_inicial = floatval($_POST['deposito_inicial'] ?? 0);
    $aportacion_anual = floatval($_POST['aportacion_anual'] ?? 0);
    $ajustar_inflacion = $_POST['ajustar_inflacion'] ?? 'no';
    $incremento_anual = floatval($_POST['incremento_anual'] ?? 0);
    $anos_inversion = intval($_POST['anos_inversion'] ?? 0);
    $rendimiento_anual = floatval($_POST['rendimiento_anual'] ?? 0);
    
    // Array para almacenar los datos de cada año
    $tabla_anual = [];
    $saldo_actual = $deposito_inicial;
    $aportacion_actual = $aportacion_anual;
    $total_aportaciones = $deposito_inicial;
    $total_rendimientos = 0;
    
    // Calcular año por año
    for ($ano = 1; $ano <= $anos_inversion; $ano++) {
        $saldo_inicial = $saldo_actual;
        
        // Calcular rendimiento del año
        $rendimiento_ano = $saldo_actual * ($rendimiento_anual / 100);
        
        // Actualizar saldo con rendimiento
        $saldo_actual += $rendimiento_ano;
        
        // Aplicar aportación del año
        $saldo_actual += $aportacion_actual;
        
        // Calcular saldo final del año
        $saldo_final = $saldo_actual;
        
        // Guardar datos del año
        $tabla_anual[] = [
            'ano' => $ano,
            'saldo_inicial' => $saldo_inicial,
            'aportacion' => $aportacion_actual,
            'rendimiento' => $rendimiento_ano,
            'saldo_final' => $saldo_final
        ];
        
        // Actualizar totales
        $total_aportaciones += $aportacion_actual;
        $total_rendimientos += $rendimiento_ano;
        
        // Si se ajusta por inflación, incrementar la aportación para el próximo año
        if ($ajustar_inflacion === 'si' && $incremento_anual > 0) {
            $aportacion_actual *= (1 + ($incremento_anual / 100));
        }
    }
    
    // Calcular ganancias totales
    $ganancias_totales = $saldo_final - $total_aportaciones;
    
    $resultado_interes = [
        'deposito_inicial' => $deposito_inicial,
        'aportacion_anual' => $aportacion_anual,
        'anos_inversion' => $anos_inversion,
        'rendimiento_anual' => $rendimiento_anual,
        'saldo_final' => $saldo_final,
        'total_aportaciones' => $total_aportaciones,
        'ganancias_totales' => $ganancias_totales,
        'tabla_anual' => $tabla_anual
    ];
}
?>

<div class="calculadora-item" id="calculadora-interes" data-calc="interes">
    <h3 class="calculadora-nombre">Calculadora de Interés Compuesto</h3>
    <p class="calculadora-subtitulo">Calcula el crecimiento de tu inversión a lo largo del tiempo</p>
    
    <form method="POST" action="" class="calculadora-form">
        <input type="hidden" name="calc_type" value="interes">
        
        <!-- Depósito Inicial -->
        <div class="form-group">
            <label for="deposito_inicial">CAPTURA tu Depósito Inicial</label>
            <div class="input-wrapper">
                <span class="currency-symbol">$</span>
                <input type="number" 
                       name="deposito_inicial" 
                       id="deposito_inicial" 
                       class="form-input" 
                       step="0.01" 
                       min="0"
                       placeholder="0.00"
                       value="<?php echo isset($_POST['deposito_inicial']) ? htmlspecialchars($_POST['deposito_inicial']) : '10'; ?>"
                       required>
            </div>
            <small class="input-helper">Ingrese la cantidad con la que inicia tu inversión.</small>
        </div>

        <!-- Aportación Anual -->
        <div class="form-group">
            <label for="aportacion_anual">Aportación Anual</label>
            <div class="input-wrapper">
                <span class="currency-symbol">$</span>
                <input type="number" 
                       name="aportacion_anual" 
                       id="aportacion_anual" 
                       class="form-input" 
                       step="0.01" 
                       min="0"
                       placeholder="0.00"
                       value="<?php echo isset($_POST['aportacion_anual']) ? htmlspecialchars($_POST['aportacion_anual']) : '80'; ?>"
                       required>
            </div>
            <small class="input-helper">¿Cuánto dinero puedes sumarle a tu inversión cada año?</small>
        </div>

        <!-- Ajuste por inflación - Radio Buttons -->
        <div class="form-group">
            <label>¿Aumentarás un porcentaje anual para compensar la inflación?</label>
            <div class="radio-group">
                <div class="radio-item">
                    <input type="radio" 
                           name="ajustar_inflacion" 
                           id="inflacion_si" 
                           value="si"
                           <?php echo (isset($_POST['ajustar_inflacion']) && $_POST['ajustar_inflacion'] === 'si') ? 'checked' : ''; ?>>
                    <label for="inflacion_si">Sí</label>
                </div>
                <div class="radio-item">
                    <input type="radio" 
                           name="ajustar_inflacion" 
                           id="inflacion_no" 
                           value="no"
                           <?php echo (!isset($_POST['ajustar_inflacion']) || $_POST['ajustar_inflacion'] === 'no') ? 'checked' : ''; ?>>
                    <label for="inflacion_no">No</label>
                </div>
            </div>
        </div>

        <!-- Incremento anual (aparece si selecciona Sí) -->
        <div class="form-group incremento-group" id="incremento-group" style="<?php echo (isset($_POST['ajustar_inflacion']) && $_POST['ajustar_inflacion'] === 'si') ? 'display: block;' : 'display: none;'; ?>">
            <label for="incremento_anual">Incremento % anual a tu inversión</label>
            <div class="input-wrapper">
                <input type="number" 
                       name="incremento_anual" 
                       id="incremento_anual" 
                       class="form-input" 
                       step="0.01" 
                       min="0"
                       placeholder="0.00"
                       value="<?php echo isset($_POST['incremento_anual']) ? htmlspecialchars($_POST['incremento_anual']) : '10'; ?>">
                <span class="percentage-symbol">%</span>
            </div>
            <small class="input-helper">Por la inflación, ¿Cuánto incrementarías cada año tu aportación?</small>
        </div>

        <!-- Años de inversión -->
        <div class="form-group">
            <label for="anos_inversion">¿Cuántos años quieres mantener tu inversión?</label>
            <input type="number" 
                   name="anos_inversion" 
                   id="anos_inversion" 
                   class="form-input" 
                   min="1"
                   placeholder="0"
                   value="<?php echo isset($_POST['anos_inversion']) ? htmlspecialchars($_POST['anos_inversion']) : '5'; ?>"
                   required>
            <small class="input-helper">¿Por cuántos años vas a realizar la inversión?</small>
        </div>

        <!-- Rendimiento anual esperado -->
        <div class="form-group">
            <label for="rendimiento_anual">Rendimiento % Anual Promedio Esperado</label>
            <div class="input-wrapper">
                <input type="number" 
                       name="rendimiento_anual" 
                       id="rendimiento_anual" 
                       class="form-input" 
                       step="0.01" 
                       min="0"
                       placeholder="0"
                       value="<?php echo isset($_POST['rendimiento_anual']) ? htmlspecialchars($_POST['rendimiento_anual']) : '10'; ?>"
                       required>
                <span class="percentage-symbol">%</span>
            </div>
            <small class="input-helper">Una renta fija ronda el 5% - 18% aproximadamente.</small>
        </div>

        <!-- Botón Calcular -->
        <div class="form-group">
            <button type="submit" class="btn-calcular-principal">
                Calcular Interés Compuesto
            </button>
        </div>
    </form>

    <?php if($resultado_interes): ?>
    <div class="resultados-calculadora">
        <h4 class="resultados-titulo">Cálculo de Interés Compuesto</h4>
        
        <!-- Resultados principales -->
        <div class="calculo-principal-interes">
            <div class="resultado-grid">
                <div class="resultado-card total">
                    <span class="resultado-label">Tus ganancias serán:</span>
                    <span class="resultado-valor">$<?php echo number_format($resultado_interes['ganancias_totales'], 2); ?></span>
                </div>
                <div class="resultado-card final">
                    <span class="resultado-label">Al final del periodo tendrás:</span>
                    <span class="resultado-valor">$<?php echo number_format($resultado_interes['saldo_final'], 2); ?></span>
                </div>
            </div>
        </div>

        <!-- Tabla anual -->
        <h4 class="resultados-titulo" style="margin-top: 30px; color: #2c3e50;">Detalle año por año</h4>
        
      <div class="tabla-container">
    <table class="tabla-resultados tabla-interes">
        <thead>
            <tr>
                <th>Año</th>
                <th>Saldo Inicial</th>
                <th>Aportación</th>
                <th>Rendimiento</th>
                <th>Saldo Final</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($resultado_interes['tabla_anual'] as $ano): ?>
            <tr>
                <td style="color: #000000; font-weight: bold;"><?php echo $ano['ano']; ?></td>
                <td style="color: #000000;">$<?php echo number_format($ano['saldo_inicial'], 2); ?></td>
                <td style="color: #000000;">$<?php echo number_format($ano['aportacion'], 2); ?></td>
                <td style="color: #000000;">$<?php echo number_format($ano['rendimiento'], 2); ?></td>
                <td><strong style="color: #000000;">$<?php echo number_format($ano['saldo_final'], 2); ?></strong></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

        <!-- Resumen de inversión -->
        <div class="resumen-inversion" style="margin-top: 20px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
            <h5 style="margin: 0 0 15px 0; color: #2c3e50;">Resumen de tu inversión:</h5>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <div>
                    <span style="color: #666;">Total invertido:</span><br>
                    <strong style="font-size: 1.2em; color: #27ae60;">$<?php echo number_format($resultado_interes['total_aportaciones'], 2); ?></strong>
                </div>
                <div>
                    <span style="color: #666;">Total rendimientos:</span><br>
                    <strong style="font-size: 1.2em; color: #27ae60;">$<?php echo number_format($resultado_interes['ganancias_totales'], 2); ?></strong>
                </div>
                <div>
                    <span style="color: #666;">Rendimiento total:</span><br>
                    <strong style="font-size: 1.2em; color: #27ae60;">
                        <?php 
                        if($resultado_interes['total_aportaciones'] > 0) {
                            $porcentaje_rendimiento = ($resultado_interes['ganancias_totales'] / $resultado_interes['total_aportaciones']) * 100;
                            echo number_format($porcentaje_rendimiento, 2) . '%';
                        } else {
                            echo '0.00%';
                        }
                        ?>
                    </strong>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
/* Estilos específicos para la calculadora de interés compuesto */
.calculo-principal-interes {
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
    border-radius: 12px;
    padding: 25px;
    color: white;
    margin-bottom: 25px;
}

.resultado-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.resultado-card {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    backdrop-filter: blur(10px);
}

.resultado-card.total {
    border-right: 2px solid rgba(255, 255, 255, 0.2);
}

.resultado-label {
    display: block;
    font-size: 14px;
    margin-bottom: 10px;
    opacity: 0.9;
}

.resultado-valor {
    display: block;
    font-size: 32px;
    font-weight: bold;
    line-height: 1.2;
}

.tabla-interes {
    font-size: 14px;
}

.tabla-interes th {
    background: #2c3e50;
    color: white;
    padding: 12px;
    text-align: center;
}

.tabla-interes td {
    padding: 10px;
    text-align: right;
    border-bottom: 1px solid #e0e0e0;
}

.tabla-interes td:first-child {
    text-align: center;
    font-weight: bold;
    background: #f8f9fa;
}

.tabla-interes tr:hover {
    background: #f5f5f5;
}

.radio-group {
    display: flex;
    gap: 30px;
    margin-top: 10px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
}

.radio-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.radio-item input[type="radio"] {
    width: 18px;
    height: 18px;
    accent-color: #27ae60;
    cursor: pointer;
}

.radio-item label {
    cursor: pointer;
    font-weight: 500;
    color: #333;
}

.input-helper {
    display: block;
    font-size: 12px;
    color: #666;
    margin-top: 5px;
    font-style: italic;
}

.percentage-symbol {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
    font-weight: bold;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.currency-symbol {
    position: absolute;
    left: 15px;
    color: #666;
    font-weight: bold;
}

.input-wrapper .form-input {
    padding-left: 30px;
    padding-right: 30px;
}

.incremento-group {
    transition: all 0.3s ease;
}

@media (max-width: 768px) {
    .resultado-grid {
        grid-template-columns: 1fr;
    }
    
    .resultado-card.total {
        border-right: none;
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    }
    
    .resultado-valor {
        font-size: 24px;
    }
    
    .radio-group {
        flex-direction: column;
        gap: 15px;
    }
}
</style>

<script>
// Script para mostrar/ocultar el campo de incremento según la selección de inflación
document.addEventListener('DOMContentLoaded', function() {
    const radioSi = document.getElementById('inflacion_si');
    const radioNo = document.getElementById('inflacion_no');
    const incrementoGroup = document.getElementById('incremento-group');
    
    if(radioSi && radioNo && incrementoGroup) {
        function toggleIncremento() {
            if (radioSi.checked) {
                incrementoGroup.style.display = 'block';
            } else {
                incrementoGroup.style.display = 'none';
            }
        }
        
        radioSi.addEventListener('change', toggleIncremento);
        radioNo.addEventListener('change', toggleIncremento);
        
        // Ejecutar al cargar la página
        toggleIncremento();
    }
});
</script>