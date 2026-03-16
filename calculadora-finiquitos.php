<?php
// calculadoras/calculadora-finiquitos.php
$resultado_finiquito = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calc_type']) && $_POST['calc_type'] === 'finiquito') {
    // Obtener datos del formulario
    $fecha_ingreso = $_POST['fecha_ingreso'] ?? '';
    $fecha_baja = $_POST['fecha_baja'] ?? '';
    $salario_diario = floatval($_POST['salario_diario'] ?? 0);
    $salario_mensual = $salario_diario * 30;
    $otras_percepciones = floatval($_POST['otras_percepciones'] ?? 0);
    $dias_vacaciones_pendientes = floatval($_POST['dias_vacaciones_pendientes'] ?? 0);
    $tipo_despido = $_POST['tipo_despido'] ?? 'voluntario';
    
    // Calcular días trabajados en el año actual
    $fecha_ingreso_obj = new DateTime($fecha_ingreso);
    $fecha_baja_obj = new DateTime($fecha_baja);
    
    // Calcular antigüedad total
    $antiguedad = $fecha_ingreso_obj->diff($fecha_baja_obj);
    $anos_completos = $antiguedad->y;
    $meses_completos = $antiguedad->m;
    $dias_completos = $antiguedad->d;
    
    // Calcular días trabajados en el último año
    $un_ano_atras = clone $fecha_baja_obj;
    $un_ano_atras->modify('-1 year');
    
    if ($fecha_ingreso_obj < $un_ano_atras) {
        $inicio_periodo = $un_ano_atras;
    } else {
        $inicio_periodo = $fecha_ingreso_obj;
    }
    
    $dias_ultimo_ano = $inicio_periodo->diff($fecha_baja_obj)->days;
    
    // ===== SUELDO (días trabajados en el mes actual) =====
    $primer_dia_mes = new DateTime($fecha_baja_obj->format('Y-m-01'));
    $dias_trabajados_mes = $primer_dia_mes->diff($fecha_baja_obj)->days + 1;
    $sueldo_gravado = $salario_diario * $dias_trabajados_mes;
    $sueldo_exento = 0;
    $sueldo_total = $sueldo_gravado;
    
    // ===== AGUINALDO PROPORCIONAL =====
    // Ley Federal del Trabajo: mínimo 15 días de aguinaldo
    $dias_aguinaldo_ley = 15;
    $aguinaldo_proporcional_total = ($salario_diario * $dias_aguinaldo_ley * $dias_ultimo_ano) / 365;
    
    // Parte exenta del aguinaldo (30 UMAS - aproximadamente $3,200)
    $aguinaldo_exento = min($aguinaldo_proporcional_total, 3200);
    $aguinaldo_gravado = max(0, $aguinaldo_proporcional_total - $aguinaldo_exento);
    
    // ===== VACACIONES PROPORCIONALES =====
    // Tabla de días de vacaciones según antigüedad (LFT)
    $dias_vacaciones_por_ano = [
        1 => 12,  // Año 1: 12 días
        2 => 14,  // Año 2: 14 días
        3 => 16,  // Año 3: 16 días
        4 => 18,  // Año 4: 18 días
        5 => 20,  // Año 5: 20 días
        6 => 22,  // Año 6: 22 días
        7 => 24,  // Año 7: 24 días
        8 => 26,  // Año 8: 26 días
        9 => 28,  // Año 9: 28 días
        10 => 30, // Año 10: 30 días
    ];
    
    // Determinar días de vacaciones según antigüedad
    if ($anos_completos >= 10) {
        $dias_vacaciones_base = 30 + (($anos_completos - 10) * 2); // Después de 10 años, aumentan 2 días por año
    } else {
        $dias_vacaciones_base = $dias_vacaciones_por_ano[$anos_completos] ?? 12;
    }
    
    // Vacaciones proporcionales
    $dias_vacaciones_proporcionales = ($dias_vacaciones_base * $dias_ultimo_ano) / 365;
    $vacaciones_proporcionales_total = $salario_diario * $dias_vacaciones_proporcionales;
    $vacaciones_proporcionales_gravado = $vacaciones_proporcionales_total;
    $vacaciones_proporcionales_exento = 0;
    
    // ===== PRIMA VACACIONAL PROPORCIONAL =====
    // Mínimo 25% sobre vacaciones
    $prima_vacacional_proporcional_total = $vacaciones_proporcionales_total * 0.25;
    // Parte exenta de prima vacacional (15 UMAS - aproximadamente $1,600)
    $prima_vacacional_proporcional_exento = min($prima_vacacional_proporcional_total, 1600);
    $prima_vacacional_proporcional_gravado = max(0, $prima_vacacional_proporcional_total - $prima_vacacional_proporcional_exento);
    
    // ===== VACACIONES ADEUDADAS (si se especificaron) =====
    if ($dias_vacaciones_pendientes > 0) {
        $vacaciones_adeudadas_total = $salario_diario * $dias_vacaciones_pendientes;
        $vacaciones_adeudadas_gravado = $vacaciones_adeudadas_total;
        $vacaciones_adeudadas_exento = 0;
    } else {
        $vacaciones_adeudadas_total = 0;
        $vacaciones_adeudadas_gravado = 0;
        $vacaciones_adeudadas_exento = 0;
    }
    
    // ===== PRIMA VACACIONAL ADEUDADA =====
    if ($dias_vacaciones_pendientes > 0) {
        $prima_vacacional_adeudada_total = $vacaciones_adeudadas_total * 0.25;
        // 40% exento aproximadamente
        $prima_vacacional_adeudada_exento = $prima_vacacional_adeudada_total * 0.40;
        $prima_vacacional_adeudada_gravado = $prima_vacacional_adeudada_total * 0.60;
    } else {
        $prima_vacacional_adeudada_total = 0;
        $prima_vacacional_adeudada_gravado = 0;
        $prima_vacacional_adeudada_exento = 0;
    }
    
    // ===== INDEMNIZACIONES (solo para despido injustificado) =====
    $indemnizacion_total = 0;
    $indemnizacion_gravado = 0;
    $indemnizacion_exento = 0;
    $indemnizacion_3meses_total = 0;
    $indemnizacion_3meses_gravado = 0;
    $indemnizacion_3meses_exento = 0;
    $prima_antiguedad_total = 0;
    $prima_antiguedad_gravado = 0;
    $prima_antiguedad_exento = 0;
    
    if ($tipo_despido === 'injustificado') {
        // Indemnización 3 meses
        $indemnizacion_3meses_total = $salario_mensual * 3;
        $indemnizacion_3meses_gravado = $indemnizacion_3meses_total;
        $indemnizacion_3meses_exento = 0;
        
        // Prima de antigüedad (12 días por año)
        $dias_prima_antiguedad = min(12 * $anos_completos, 240); // Máximo 240 días
        $prima_antiguedad_total = $salario_diario * $dias_prima_antiguedad;
        $prima_antiguedad_gravado = $prima_antiguedad_total;
        $prima_antiguedad_exento = 0;
        
        // Indemnización adicional (20 días por año)
        $indemnizacion_total = $salario_diario * 20 * $anos_completos;
        // Parte exenta de indemnización (90 UMAS diarias - aproximadamente $9,600 diarios)
        $limite_diario_exento = 9600;
        $indemnizacion_exento = min($indemnizacion_total, $limite_diario_exento * $dias_prima_antiguedad);
        $indemnizacion_gravado = max(0, $indemnizacion_total - $indemnizacion_exento);
    }
    
    // ===== TOTALES =====
    $total_gravado = $sueldo_gravado + $aguinaldo_gravado + $vacaciones_proporcionales_gravado + 
                     $prima_vacacional_proporcional_gravado + $vacaciones_adeudadas_gravado + 
                     $prima_vacacional_adeudada_gravado + $indemnizacion_gravado + 
                     $indemnizacion_3meses_gravado + $prima_antiguedad_gravado;
    
    $total_exento = $aguinaldo_exento + $vacaciones_proporcionales_exento + 
                    $prima_vacacional_proporcional_exento + $vacaciones_adeudadas_exento + 
                    $prima_vacacional_adeudada_exento + $indemnizacion_exento + 
                    $indemnizacion_3meses_exento + $prima_antiguedad_exento;
    
    $total_percepciones = $total_gravado + $total_exento;
    
    // ===== CÁLCULO DE ISR (simplificado) =====
    // ISR por finiquitos (aproximadamente 10% sobre gravado)
    $isr_finiquitos = $total_gravado * 0.10;
    $isr_indemnizacion = ($indemnizacion_gravado + $indemnizacion_3meses_gravado + $prima_antiguedad_gravado) * 0.15;
    $total_isr = $isr_finiquitos + $isr_indemnizacion;
    
    $neto_recibir = $total_percepciones - $total_isr;
    
    $resultado_finiquito = [
        'fecha_ingreso' => $fecha_ingreso,
        'fecha_baja' => $fecha_baja,
        'salario_diario' => $salario_diario,
        'salario_mensual' => $salario_mensual,
        'dias_vacaciones_pendientes' => $dias_vacaciones_pendientes,
        'tipo_despido' => $tipo_despido,
        'antiguedad' => [
            'años' => $anos_completos,
            'meses' => $meses_completos,
            'dias' => $dias_completos
        ],
        
        // Conceptos
        'sueldo' => [
            'gravado' => $sueldo_gravado,
            'exento' => $sueldo_exento,
            'total' => $sueldo_total
        ],
        'aguinaldo' => [
            'gravado' => $aguinaldo_gravado,
            'exento' => $aguinaldo_exento,
            'total' => $aguinaldo_proporcional_total
        ],
        'vacaciones_proporcionales' => [
            'gravado' => $vacaciones_proporcionales_gravado,
            'exento' => $vacaciones_proporcionales_exento,
            'total' => $vacaciones_proporcionales_total
        ],
        'prima_vacacional_proporcional' => [
            'gravado' => $prima_vacacional_proporcional_gravado,
            'exento' => $prima_vacacional_proporcional_exento,
            'total' => $prima_vacacional_proporcional_total
        ],
        'vacaciones_adeudadas' => [
            'gravado' => $vacaciones_adeudadas_gravado,
            'exento' => $vacaciones_adeudadas_exento,
            'total' => $vacaciones_adeudadas_total
        ],
        'prima_vacacional_adeudada' => [
            'gravado' => $prima_vacacional_adeudada_gravado,
            'exento' => $prima_vacacional_adeudada_exento,
            'total' => $prima_vacacional_adeudada_total
        ],
        'indemnizacion' => [
            'gravado' => $indemnizacion_gravado,
            'exento' => $indemnizacion_exento,
            'total' => $indemnizacion_total
        ],
        'indemnizacion_3meses' => [
            'gravado' => $indemnizacion_3meses_gravado,
            'exento' => $indemnizacion_3meses_exento,
            'total' => $indemnizacion_3meses_total
        ],
        'prima_antiguedad' => [
            'gravado' => $prima_antiguedad_gravado,
            'exento' => $prima_antiguedad_exento,
            'total' => $prima_antiguedad_total
        ],
        
        // Totales
        'total_gravado' => $total_gravado,
        'total_exento' => $total_exento,
        'total_percepciones' => $total_percepciones,
        'isr_finiquitos' => $isr_finiquitos,
        'isr_indemnizacion' => $isr_indemnizacion,
        'total_isr' => $total_isr,
        'neto_recibir' => $neto_recibir
    ];
}
?>

<div class="calculadora-item" id="calculadora-finiquitos" data-calc="finiquito">
    <h3 class="calculadora-nombre">Calculadora de Finiquitos</h3>
    <p class="calculadora-subtitulo">Calcula el finiquito según la Ley Federal del Trabajo</p>
    
    <form method="POST" action="" class="calculadora-form">
        <input type="hidden" name="calc_type" value="finiquito">
        
        <!-- Tipo de despido -->
        <div class="form-group">
            <label>Tipo de despido:</label>
            <div class="radio-group">
                <div class="radio-item">
                    <input type="radio" 
                           name="tipo_despido" 
                           id="despido_voluntario" 
                           value="voluntario" 
                           <?php echo (($_POST['tipo_despido'] ?? 'voluntario') === 'voluntario') ? 'checked' : ''; ?>>
                    <label for="despido_voluntario">Voluntario / Renuncia</label>
                </div>
                <div class="radio-item">
                    <input type="radio" 
                           name="tipo_despido" 
                           id="despido_injustificado" 
                           value="injustificado"
                           <?php echo (($_POST['tipo_despido'] ?? '') === 'injustificado') ? 'checked' : ''; ?>>
                    <label for="despido_injustificado">Despido injustificado</label>
                </div>
            </div>
        </div>
        
        <!-- Fecha de ingreso -->
        <div class="form-group">
            <label for="fecha_ingreso">Fecha de ingreso:</label>
            <input type="date" 
                   name="fecha_ingreso" 
                   id="fecha_ingreso" 
                   class="form-input" 
                   value="<?php echo $_POST['fecha_ingreso'] ?? date('Y-m-d', strtotime('-5 years')); ?>"
                   required>
        </div>

        <!-- Fecha de baja -->
        <div class="form-group">
            <label for="fecha_baja">Fecha de baja:</label>
            <input type="date" 
                   name="fecha_baja" 
                   id="fecha_baja" 
                   class="form-input" 
                   value="<?php echo $_POST['fecha_baja'] ?? date('Y-m-d'); ?>"
                   required>
        </div>

        <!-- Salario diario -->
        <div class="form-group">
            <label for="salario_diario">Salario diario:</label>
            <div class="input-wrapper">
                <span class="currency-symbol">$</span>
                <input type="number" 
                       name="salario_diario" 
                       id="salario_diario" 
                       class="form-input" 
                       step="0.01" 
                       min="0"
                       placeholder="0.00"
                       value="<?php echo $_POST['salario_diario'] ?? '500'; ?>"
                       required>
            </div>
        </div>

        <!-- Días de vacaciones pendientes (opcional) -->
        <div class="form-group">
            <label for="dias_vacaciones_pendientes">¿Cuántos días de vacaciones te deben? (opcional):</label>
            <input type="number" 
                   name="dias_vacaciones_pendientes" 
                   id="dias_vacaciones_pendientes" 
                   class="form-input" 
                   step="1" 
                   min="0"
                   placeholder="0"
                   value="<?php echo $_POST['dias_vacaciones_pendientes'] ?? '15'; ?>">
        </div>

        <!-- Botón Calcular -->
        <div class="form-group">
            <button type="submit" class="btn-calcular-principal">
                Calcular Finiquito
            </button>
        </div>
    </form>

    <?php if($resultado_finiquito): ?>
    <div class="resultados-calculadora">
        <h4 class="resultados-titulo">Resultado del Finiquito</h4>
        
        <!-- Resumen de antigüedad -->
        <div class="antiguedad-resumen" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <strong>Antigüedad:</strong> 
            <?php echo $resultado_finiquito['antiguedad']['años']; ?> años, 
            <?php echo $resultado_finiquito['antiguedad']['meses']; ?> meses, 
            <?php echo $resultado_finiquito['antiguedad']['dias']; ?> días
            <?php if($resultado_finiquito['tipo_despido'] === 'injustificado'): ?>
                <span style="margin-left: 20px; background: rgba(255,255,255,0.2); padding: 5px 10px; border-radius: 20px;">Despido injustificado</span>
            <?php else: ?>
                <span style="margin-left: 20px; background: rgba(255,255,255,0.2); padding: 5px 10px; border-radius: 20px;">Renuncia voluntaria</span>
            <?php endif; ?>
        </div>
        
        <!-- Tabla de resultados en el formato solicitado -->
       <!-- Tabla de resultados en el formato solicitado -->
<div class="tabla-container">
    <table class="tabla-resultados tabla-finiquito-detallada">
        <thead>
            <tr>
                <th>Conceptos</th>
                <th>Gravado</th>
                <th>Exento</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Sueldo</td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['sueldo']['gravado'], 2); ?></td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['sueldo']['exento'], 2); ?></td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['sueldo']['total'], 2); ?></td>
            </tr>
            <tr>
                <td>Aguinaldo proporcional</td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['aguinaldo']['gravado'], 2); ?></td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['aguinaldo']['exento'], 2); ?></td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['aguinaldo']['total'], 2); ?></td>
            </tr>
            <tr>
                <td>Vacaciones proporcionales</td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['vacaciones_proporcionales']['gravado'], 2); ?></td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['vacaciones_proporcionales']['exento'], 2); ?></td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['vacaciones_proporcionales']['total'], 2); ?></td>
            </tr>
            <tr>
                <td>Prima vacacional proporcional</td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['prima_vacacional_proporcional']['gravado'], 2); ?></td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['prima_vacacional_proporcional']['exento'], 2); ?></td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['prima_vacacional_proporcional']['total'], 2); ?></td>
            </tr>
            
            <?php if($resultado_finiquito['vacaciones_adeudadas']['total'] > 0): ?>
            <tr>
                <td>Vacaciones adeudadas</td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['vacaciones_adeudadas']['gravado'], 2); ?></td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['vacaciones_adeudadas']['exento'], 2); ?></td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['vacaciones_adeudadas']['total'], 2); ?></td>
            </tr>
            <tr>
                <td>Prima vacacional adeudada</td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['prima_vacacional_adeudada']['gravado'], 2); ?></td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['prima_vacacional_adeudada']['exento'], 2); ?></td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['prima_vacacional_adeudada']['total'], 2); ?></td>
            </tr>
            <?php endif; ?>
            
            <?php if($resultado_finiquito['tipo_despido'] === 'injustificado'): ?>
            <tr>
                <td>Indemnización</td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['indemnizacion']['gravado'], 2); ?></td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['indemnizacion']['exento'], 2); ?></td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['indemnizacion']['total'], 2); ?></td>
            </tr>
            <tr>
                <td>Indemnización 3 meses</td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['indemnizacion_3meses']['gravado'], 2); ?></td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['indemnizacion_3meses']['exento'], 2); ?></td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['indemnizacion_3meses']['total'], 2); ?></td>
            </tr>
            <tr>
                <td>Prima de antigüedad</td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['prima_antiguedad']['gravado'], 2); ?></td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['prima_antiguedad']['exento'], 2); ?></td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['prima_antiguedad']['total'], 2); ?></td>
            </tr>
            <?php endif; ?>
            
            <tr>
                <td>Subsidio al empleo</td>
                <td class="valor-moneda">$0.00</td>
                <td class="valor-moneda">$0.00</td>
                <td class="valor-moneda">$0.00</td>
            </tr>
            
            <tr class="total-row">
                <td><strong>Total percepciones</strong></td>
                <td class="valor-moneda"><strong>$<?php echo number_format($resultado_finiquito['total_gravado'], 2); ?></strong></td>
                <td class="valor-moneda"><strong>$<?php echo number_format($resultado_finiquito['total_exento'], 2); ?></strong></td>
                <td class="valor-moneda"><strong>$<?php echo number_format($resultado_finiquito['total_percepciones'], 2); ?></strong></td>
            </tr>
            
            <tr>
                <td>ISR por finiquitos</td>
                <td class="valor-moneda">-</td>
                <td class="valor-moneda">-</td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['isr_finiquitos'], 2); ?></td>
            </tr>
            
            <?php if($resultado_finiquito['tipo_despido'] === 'injustificado'): ?>
            <tr>
                <td>ISR por indemnización</td>
                <td class="valor-moneda">-</td>
                <td class="valor-moneda">-</td>
                <td class="valor-moneda">$<?php echo number_format($resultado_finiquito['isr_indemnizacion'], 2); ?></td>
            </tr>
            <?php endif; ?>
            
            <tr class="neto-row">
                <td><strong>Neto a recibir</strong></td>
                <td class="valor-moneda">-</td>
                <td class="valor-moneda">-</td>
                <td class="valor-moneda"><strong>$<?php echo number_format($resultado_finiquito['neto_recibir'], 2); ?></strong></td>
            </tr>
        </tbody>
    </table>
</div>

        <!-- Nota informativa -->
        <div class="nota-informativa" style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
            <p style="margin: 0; color: #666; font-size: 14px;">
                <strong>Nota:</strong> Este cálculo es una estimación basada en la Ley Federal del Trabajo. 
                Los montos exentos son aproximados y pueden variar según la UMA vigente. 
                El ISR es una estimación y puede variar según tu situación fiscal específica.
            </p>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
/* Estilos específicos para la calculadora de Finiquitos */
.tabla-finiquito-detallada {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.tabla-finiquito-detallada thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.tabla-finiquito-detallada th {
    padding: 15px;
    text-align: left;
    font-weight: 600;
}

.tabla-finiquito-detallada th:not(:first-child),
.tabla-finiquito-detallada td:not(:first-child) {
    text-align: right;
}

.tabla-finiquito-detallada td {
    padding: 12px 15px;
    border-bottom: 1px solid #eef2f6;
}

.tabla-finiquito-detallada tbody tr:last-child td {
    border-bottom: none;
}

.tabla-finiquito-detallada .total-row {
    background: #f0f4ff;
    font-weight: bold;
    border-top: 2px solid #667eea;
}

.tabla-finiquito-detallada .neto-row {
    background: linear-gradient(135deg, #e6f0ff 0%, #d9e6ff 100%);
    font-weight: bold;
    font-size: 16px;
}

.valor-moneda {
    font-family: 'Courier New', monospace;
    font-weight: 500;
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
    accent-color: #667eea;
    cursor: pointer;
}

.radio-item label {
    cursor: pointer;
    font-weight: 500;
    color: #333;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.currency-symbol {
    position: absolute;
    left: 12px;
    font-size: 18px;
    font-weight: bold;
    color: #666;
}

.form-input {
    width: 100%;
    padding: 12px 12px 12px 35px;
    font-size: 16px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    transition: border-color 0.3s;
}

.form-input:focus {
    outline: none;
    border-color: #667eea;
}

input[type="date"].form-input {
    padding: 12px;
}

.btn-calcular-principal {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

.btn-calcular-principal:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
}

.btn-calcular-principal:active {
    transform: translateY(0);
}

.resultados-titulo {
    margin: 20px 0 15px 0;
    color: #333;
    font-size: 18px;
    font-weight: 600;
}

.tabla-container {
    overflow-x: auto;
    border-radius: 12px;
}

.antiguedad-resumen {
    font-size: 16px;
}

@media (max-width: 768px) {
    .radio-group {
        flex-direction: column;
        gap: 15px;
    }
    
    .tabla-finiquito-detallada {
        font-size: 14px;
    }
    
    .tabla-finiquito-detallada td,
    .tabla-finiquito-detallada th {
        padding: 8px;
    }
}
.tabla-finiquito-detallada {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.tabla-finiquito-detallada thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.tabla-finiquito-detallada th {
    padding: 15px;
    text-align: left;
    font-weight: 600;
}

.tabla-finiquito-detallada th:not(:first-child),
.tabla-finiquito-detallada td:not(:first-child) {
    text-align: right;
}

.tabla-finiquito-detallada td {
    padding: 12px 15px;
    border-bottom: 1px solid #eef2f6;
    color: #000000; /* Texto negro para todas las celdas */
}

.tabla-finiquito-detallada tbody tr:last-child td {
    border-bottom: none;
}

.tabla-finiquito-detallada .total-row {
    background: #f0f4ff;
    font-weight: bold;
    border-top: 2px solid #667eea;
}

.tabla-finiquito-detallada .total-row td {
    color: #000000; /* Texto negro para la fila de total */
}

.tabla-finiquito-detallada .neto-row {
    background: linear-gradient(135deg, #e6f0ff 0%, #d9e6ff 100%);
    font-weight: bold;
    font-size: 16px;
}

.tabla-finiquito-detallada .neto-row td {
    color: #000000; /* Texto negro para la fila de neto */
}

.valor-moneda {
    font-family: 'Courier New', monospace;
    font-weight: 500;
    color: #000000 !important; /* Forzar color negro para los valores monetarios */
}

/* Estilo para los textos que no son números */
.tabla-finiquito-detallada td:first-child {
    color: #000000;
    font-weight: normal;
}

.tabla-finiquito-detallada .total-row td:first-child,
.tabla-finiquito-detallada .neto-row td:first-child {
    color: #000000;
    font-weight: bold;
}

/* Mantener el encabezado en blanco */
.tabla-finiquito-detallada thead th {
    color: white;
}

/* Asegurar que los guiones también sean negros */
.tabla-finiquito-detallada td.valor-moneda:contains("-") {
    color: #000000;
}
</style>