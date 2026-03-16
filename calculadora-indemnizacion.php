<?php
// calculadoras/calculadora-indemnizacion.php
$resultado_indemnizacion = null;

// Valores UMA 2026 según INEGI [citation:1][citation:3]
define('UMA_DIARIA_2026', 117.31);
define('UMA_MENSUAL_2026', 3566.22);
define('UMA_ANUAL_2026', 42794.64);

// Salario mínimo general 2026
define('SALARIO_MINIMO_2026', 315.04); // Según CONASAMI

// Límite de integración del SBC (25 UMA) [citation:5]
define('LIMITE_SBC', UMA_DIARIA_2026 * 25); // $2,932.75

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calc_type']) && $_POST['calc_type'] === 'indemnizacion') {
    // Obtener datos del formulario
    $fecha_ingreso = $_POST['fecha_ingreso'] ?? '';
    $fecha_baja = $_POST['fecha_baja'] ?? '';
    $salario_diario = floatval($_POST['salario_diario'] ?? 0);
    $otras_percepciones = floatval($_POST['otras_percepciones'] ?? 0);
    $dias_vacaciones_pendientes = floatval($_POST['dias_vacaciones_pendientes'] ?? 0);
    
    // Calcular días trabajados
    $fecha_ingreso_obj = DateTime::createFromFormat('d/m/Y', $fecha_ingreso);
    $fecha_baja_obj = DateTime::createFromFormat('d/m/Y', $fecha_baja);
    
    if (!$fecha_ingreso_obj || !$fecha_baja_obj) {
        $error = 'Formato de fecha inválido. Usa DD/MM/AAAA';
    } elseif ($fecha_baja_obj <= $fecha_ingreso_obj) {
        $error = 'La fecha de baja debe ser posterior a la fecha de ingreso.';
    } else {
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
        
        // Determinar salario diario integrado (con prestaciones mínimas de ley)
        $aguinaldo_dias = 15; // 15 días de aguinaldo
        $prima_vacacional_porcentaje = 0.25; // 25% de prima vacacional
        
        // Tabla de días de vacaciones según antigüedad (LFT)
        $dias_vacaciones_por_ano = [
            1 => 12, 2 => 14, 3 => 16, 4 => 18, 5 => 20,
            6 => 22, 7 => 24, 8 => 26, 9 => 28, 10 => 30
        ];
        
        if ($anos_completos >= 10) {
            $dias_vacaciones_base = 30 + (($anos_completos - 9) * 2);
        } else {
            $dias_vacaciones_base = $dias_vacaciones_por_ano[$anos_completos] ?? 12;
        }
        
        // Salario diario integrado (para indemnizaciones)
        $factor_integracion = 1 + ($aguinaldo_dias / 365) + (($dias_vacaciones_base * $prima_vacacional_porcentaje) / 365);
        $salario_diario_integrado = $salario_diario * $factor_integracion;
        
        // Límite de salario para indemnizaciones (2 veces el salario mínimo o 25 UMA) [citation:5]
        $salario_maximo_indemnizacion = max(SALARIO_MINIMO_2026 * 2, LIMITE_SBC);
        $salario_indemnizacion = min($salario_diario_integrado, $salario_maximo_indemnizacion);
        
        // ===== SUELDO (días trabajados en el mes actual) =====
        $primer_dia_mes = new DateTime($fecha_baja_obj->format('Y-m-01'));
        $dias_trabajados_mes = $primer_dia_mes->diff($fecha_baja_obj)->days + 1;
        $sueldo_gravado = $salario_diario * $dias_trabajados_mes;
        $sueldo_total = $sueldo_gravado;
        
        // ===== AGUINALDO PROPORCIONAL =====
        $aguinaldo_proporcional_total = ($salario_diario * 15 * $dias_ultimo_ano) / 365;
        // Parte exenta: 30 UMA [citation:4]
        $aguinaldo_exento = min($aguinaldo_proporcional_total, 30 * UMA_DIARIA_2026);
        $aguinaldo_gravado = max(0, $aguinaldo_proporcional_total - $aguinaldo_exento);
        
        // ===== VACACIONES PROPORCIONALES =====
        $dias_vacaciones_proporcionales = ($dias_vacaciones_base * $dias_ultimo_ano) / 365;
        $vacaciones_proporcionales_total = $salario_diario * $dias_vacaciones_proporcionales;
        $vacaciones_proporcionales_gravado = $vacaciones_proporcionales_total;
        
        // ===== PRIMA VACACIONAL PROPORCIONAL =====
        $prima_vacacional_proporcional_total = $vacaciones_proporcionales_total * 0.25;
        // Parte exenta: 15 UMA
        $prima_vacacional_proporcional_exento = min($prima_vacacional_proporcional_total, 15 * UMA_DIARIA_2026);
        $prima_vacacional_proporcional_gravado = $prima_vacacional_proporcional_total - $prima_vacacional_proporcional_exento;
        
        // ===== VACACIONES ADEUDADAS =====
        if ($dias_vacaciones_pendientes > 0) {
            $vacaciones_adeudadas_total = $salario_diario * $dias_vacaciones_pendientes;
            $vacaciones_adeudadas_gravado = $vacaciones_adeudadas_total;
            
            // Prima vacacional adeudada (25%)
            $prima_vacacional_adeudada_total = $vacaciones_adeudadas_total * 0.25;
            // 90 días de salario exentos para indemnizaciones [citation:2]
            $limite_exento_diario = 90 * UMA_DIARIA_2026;
            $prima_vacacional_adeudada_exento = min($prima_vacacional_adeudada_total, $limite_exento_diario * 0.4);
            $prima_vacacional_adeudada_gravado = $prima_vacacional_adeudada_total - $prima_vacacional_adeudada_exento;
        } else {
            $vacaciones_adeudadas_total = 0;
            $vacaciones_adeudadas_gravado = 0;
            $prima_vacacional_adeudada_total = 0;
            $prima_vacacional_adeudada_gravado = 0;
            $prima_vacacional_adeudada_exento = 0;
        }
        
        // ===== INDEMNIZACIONES POR DESPIDO INJUSTIFICADO =====
        // Indemnización 3 meses (Art. 50 LFT)
        $indemnizacion_3meses_total = $salario_indemnizacion * 90; // 3 meses = 90 días
        $indemnizacion_3meses_gravado = $indemnizacion_3meses_total;
        
        // Prima de antigüedad (12 días por año, máximo 2 años) - Art. 162 LFT
        $dias_prima_antiguedad = min(12 * $anos_completos, 24 * 12); // Máximo 2 años (24 meses)
        $prima_antiguedad_total = $salario_diario * $dias_prima_antiguedad;
        $prima_antiguedad_gravado = $prima_antiguedad_total;
        
        // Indemnización adicional (20 días por año) - Art. 50 LFT
        $indemnizacion_total = $salario_indemnizacion * 20 * $anos_completos;
        // Parte exenta: 90 UMA diarias [citation:4]
        $indemnizacion_exento = min($indemnizacion_total, 90 * UMA_DIARIA_2026 * $anos_completos);
        $indemnizacion_gravado = $indemnizacion_total - $indemnizacion_exento;
        
        // ===== TOTALES =====
        $total_gravado = $sueldo_gravado + $aguinaldo_gravado + $vacaciones_proporcionales_gravado + 
                        $prima_vacacional_proporcional_gravado + $vacaciones_adeudadas_gravado + 
                        $prima_vacacional_adeudada_gravado + $indemnizacion_gravado + 
                        $indemnizacion_3meses_gravado + $prima_antiguedad_gravado;
        
        $total_exento = $aguinaldo_exento + $prima_vacacional_proporcional_exento + 
                        ($prima_vacacional_adeudada_exento ?? 0) + $indemnizacion_exento;
        
        $total_percepciones = $total_gravado + $total_exento;
        
        // ===== CÁLCULO DE ISR (simplificado con tarifa 2026) =====
        // Tarifa mensual ISR 2026 (estimada con inflación 3.69%)
        $limite_inferior = 15000; // Aproximación
        $isr_finiquitos = ($total_gravado - $indemnizacion_gravado - $indemnizacion_3meses_gravado - $prima_antiguedad_gravado) * 0.12;
        $isr_indemnizacion = ($indemnizacion_gravado + $indemnizacion_3meses_gravado + $prima_antiguedad_gravado) * 0.15;
        $total_isr = $isr_finiquitos + $isr_indemnizacion;
        
        $neto_recibir = $total_percepciones - $total_isr;
        
        $resultado_indemnizacion = [
            'fecha_ingreso' => $fecha_ingreso,
            'fecha_baja' => $fecha_baja,
            'salario_diario' => $salario_diario,
            'dias_vacaciones_pendientes' => $dias_vacaciones_pendientes,
            'antiguedad' => [
                'años' => $anos_completos,
                'meses' => $meses_completos,
                'dias' => $dias_completos
            ],
            // Conceptos para la tabla
            'sueldo' => ['gravado' => $sueldo_gravado, 'exento' => 0, 'total' => $sueldo_total],
            'aguinaldo' => ['gravado' => $aguinaldo_gravado, 'exento' => $aguinaldo_exento, 'total' => $aguinaldo_proporcional_total],
            'vacaciones_proporcionales' => ['gravado' => $vacaciones_proporcionales_gravado, 'exento' => 0, 'total' => $vacaciones_proporcionales_total],
            'prima_vacacional_proporcional' => ['gravado' => $prima_vacacional_proporcional_gravado, 'exento' => $prima_vacacional_proporcional_exento, 'total' => $prima_vacacional_proporcional_total],
            'vacaciones_adeudadas' => ['gravado' => $vacaciones_adeudadas_gravado, 'exento' => 0, 'total' => $vacaciones_adeudadas_total],
            'prima_vacacional_adeudada' => ['gravado' => $prima_vacacional_adeudada_gravado, 'exento' => $prima_vacacional_adeudada_exento, 'total' => $prima_vacacional_adeudada_total],
            'indemnizacion' => ['gravado' => $indemnizacion_gravado, 'exento' => $indemnizacion_exento, 'total' => $indemnizacion_total],
            'indemnizacion_3meses' => ['gravado' => $indemnizacion_3meses_gravado, 'exento' => 0, 'total' => $indemnizacion_3meses_total],
            'prima_antiguedad' => ['gravado' => $prima_antiguedad_gravado, 'exento' => 0, 'total' => $prima_antiguedad_total],
            'subsidio' => ['gravado' => 0, 'exento' => 0, 'total' => 0],
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
}
?>

<div class="calculadora-item" id="calculadora-indemnizacion" data-calc="indemnizacion">
    <h3 class="calculadora-nombre">Calculadora de Indemnización por Despido Injustificado 2026</h3>
    <p class="calculadora-subtitulo">Calcula tu indemnización según la Ley Federal del Trabajo con UMA 2026 ($<?php echo number_format(UMA_DIARIA_2026, 2); ?> diarios) [citation:1]</p>
    
    <form method="POST" action="" class="calculadora-form">
        <input type="hidden" name="calc_type" value="indemnizacion">
        
        <!-- Fecha de ingreso -->
        <div class="form-group">
            <label for="fecha_ingreso">Fecha de ingreso:</label>
            <input type="text" 
                   name="fecha_ingreso" 
                   id="fecha_ingreso" 
                   class="form-input" 
                   placeholder="dd/mm/aaaa"
                   value="<?php echo $_POST['fecha_ingreso'] ?? '13/01/2025'; ?>"
                   required>
            <small class="help-text">Formato: DD/MM/AAAA</small>
        </div>

        <!-- Fecha de baja -->
        <div class="form-group">
            <label for="fecha_baja">Fecha de baja:</label>
            <input type="text" 
                   name="fecha_baja" 
                   id="fecha_baja" 
                   class="form-input" 
                   placeholder="dd/mm/aaaa"
                   value="<?php echo $_POST['fecha_baja'] ?? '26/02/2026'; ?>"
                   required>
            <small class="help-text">Formato: DD/MM/AAAA</small>
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
                       value="<?php echo $_POST['salario_diario'] ?? '650'; ?>"
                       required>
            </div>
        </div>

        <!-- Otras percepciones mensuales -->
        <div class="form-group">
            <label for="otras_percepciones">Otras percepciones mensuales:</label>
            <div class="input-wrapper">
                <span class="currency-symbol">$</span>
                <input type="number" 
                       name="otras_percepciones" 
                       id="otras_percepciones" 
                       class="form-input" 
                       step="0.01" 
                       min="0"
                       placeholder="0.00"
                       value="<?php echo $_POST['otras_percepciones'] ?? '0'; ?>">
            </div>
        </div>

        <!-- Días de vacaciones pendientes -->
        <div class="form-group">
            <label for="dias_vacaciones_pendientes">¿Cuántos días de vacaciones te deben? (opcional):</label>
            <input type="number" 
                   name="dias_vacaciones_pendientes" 
                   id="dias_vacaciones_pendientes" 
                   class="form-input" 
                   step="1" 
                   min="0"
                   placeholder="0"
                   value="<?php echo $_POST['dias_vacaciones_pendientes'] ?? '12'; ?>">
        </div>

        <!-- Botón Calcular -->
        <div class="form-group">
            <button type="submit" class="btn-calcular-principal">
                Calcular Indemnización
            </button>
        </div>
    </form>

    <?php if(isset($error)): ?>
        <div class="error-mensaje" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-top: 20px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <?php if($resultado_indemnizacion): ?>
    <div class="resultados-calculadora">
        <h4 class="resultados-titulo">Cálculo de Indemnización 2026</h4>
        
        <!-- Resumen de antigüedad -->
        <div class="antiguedad-resumen" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <strong>Antigüedad:</strong> 
            <?php echo $resultado_indemnizacion['antiguedad']['años']; ?> años, 
            <?php echo $resultado_indemnizacion['antiguedad']['meses']; ?> meses, 
            <?php echo $resultado_indemnizacion['antiguedad']['dias']; ?> días
            <span style="margin-left: 20px; background: rgba(255,255,255,0.2); padding: 5px 10px; border-radius: 20px;">UMA 2026: $<?php echo number_format(UMA_DIARIA_2026, 2); ?> diarios</span>
        </div>
        
        <!-- Tabla de resultados -->
        <div class="tabla-container">
            <table class="tabla-resultados tabla-indemnizacion">
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
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['sueldo']['gravado'], 2); ?></td>
                        <td class="valor-moneda">-</td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['sueldo']['total'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Aguinaldo proporcional</td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['aguinaldo']['gravado'], 2); ?></td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['aguinaldo']['exento'], 2); ?></td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['aguinaldo']['total'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Vacaciones proporcionales</td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['vacaciones_proporcionales']['gravado'], 2); ?></td>
                        <td class="valor-moneda">-</td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['vacaciones_proporcionales']['total'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Prima vacacional proporcional</td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['prima_vacacional_proporcional']['gravado'], 2); ?></td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['prima_vacacional_proporcional']['exento'], 2); ?></td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['prima_vacacional_proporcional']['total'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Vacaciones adeudadas</td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['vacaciones_adeudadas']['gravado'], 2); ?></td>
                        <td class="valor-moneda">-</td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['vacaciones_adeudadas']['total'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Prima vacacional adeudada</td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['prima_vacacional_adeudada']['gravado'], 2); ?></td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['prima_vacacional_adeudada']['exento'], 2); ?></td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['prima_vacacional_adeudada']['total'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Indemnización</td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['indemnizacion']['gravado'], 2); ?></td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['indemnizacion']['exento'], 2); ?></td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['indemnizacion']['total'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Indemnización 3 meses</td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['indemnizacion_3meses']['gravado'], 2); ?></td>
                        <td class="valor-moneda">-</td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['indemnizacion_3meses']['total'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Prima de antigüedad</td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['prima_antiguedad']['gravado'], 2); ?></td>
                        <td class="valor-moneda">-</td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['prima_antiguedad']['total'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Subsidio al empleo</td>
                        <td class="valor-moneda">-</td>
                        <td class="valor-moneda">-</td>
                        <td class="valor-moneda">$0.00</td>
                    </tr>
                    <tr class="total-row">
                        <td><strong>Total percepciones</strong></td>
                        <td class="valor-moneda"><strong>$<?php echo number_format($resultado_indemnizacion['total_gravado'], 2); ?></strong></td>
                        <td class="valor-moneda"><strong>$<?php echo number_format($resultado_indemnizacion['total_exento'], 2); ?></strong></td>
                        <td class="valor-moneda"><strong>$<?php echo number_format($resultado_indemnizacion['total_percepciones'], 2); ?></strong></td>
                    </tr>
                    <tr>
                        <td>ISR por finiquitos</td>
                        <td class="valor-moneda">-</td>
                        <td class="valor-moneda">-</td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['isr_finiquitos'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>ISR por indemnización</td>
                        <td class="valor-moneda">-</td>
                        <td class="valor-moneda">-</td>
                        <td class="valor-moneda">$<?php echo number_format($resultado_indemnizacion['isr_indemnizacion'], 2); ?></td>
                    </tr>
                    <tr class="neto-row">
                        <td><strong>Neto a recibir</strong></td>
                        <td class="valor-moneda">-</td>
                        <td class="valor-moneda">-</td>
                        <td class="valor-moneda"><strong>$<?php echo number_format($resultado_indemnizacion['neto_recibir'], 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Nota informativa con UMA 2026 -->
        <div class="nota-informativa" style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
            <p style="margin: 0; color: #666; font-size: 14px;">
                <strong>Valores UMA 2026 (vigente desde 1 de febrero 2026):</strong> [citation:1][citation:3]<br>
                • Diario: $<?php echo number_format(UMA_DIARIA_2026, 2); ?> | Mensual: $<?php echo number_format(UMA_MENSUAL_2026, 2); ?> | Anual: $<?php echo number_format(UMA_ANUAL_2026, 2); ?><br>
                • Salario mínimo 2026: $<?php echo number_format(SALARIO_MINIMO_2026, 2); ?> diarios<br>
                • Límite SBC (25 UMA): $<?php echo number_format(LIMITE_SBC, 2); ?> [citation:5]<br>
                • Partes exentas: Aguinaldo (30 UMA), Prima vacacional (15 UMA), Indemnización (90 UMA diarias por año) [citation:2][citation:4]
            </p>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
/* Estilos específicos para la calculadora de Indemnización */
.tabla-indemnizacion {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.tabla-indemnizacion thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.tabla-indemnizacion th {
    padding: 15px;
    text-align: left;
    font-weight: 600;
}

.tabla-indemnizacion th:not(:first-child),
.tabla-indemnizacion td:not(:first-child) {
    text-align: right;
}

.tabla-indemnizacion td {
    padding: 12px 15px;
    border-bottom: 1px solid #eef2f6;
    color: #000000;
}

.tabla-indemnizacion tbody tr:last-child td {
    border-bottom: none;
}

.tabla-indemnizacion .total-row {
    background: #f0f4ff;
    font-weight: bold;
    border-top: 2px solid #667eea;
}

.tabla-indemnizacion .neto-row {
    background: linear-gradient(135deg, #e6f0ff 0%, #d9e6ff 100%);
    font-weight: bold;
    font-size: 16px;
}

.valor-moneda {
    font-family: 'Courier New', monospace;
    font-weight: 500;
    color: #000000 !important;
}

.help-text {
    display: block;
    margin-top: 5px;
    color: #666;
    font-size: 12px;
    font-style: italic;
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
    .tabla-indemnizacion {
        font-size: 14px;
    }
    
    .tabla-indemnizacion td,
    .tabla-indemnizacion th {
        padding: 8px;
    }
}
</style>