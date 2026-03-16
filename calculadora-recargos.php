<?php
// calculadoras/calculadora-recargos.php
$resultado_recargos = null;

// Base de datos de INPC (Índice Nacional de Precios al Consumidor)
// Valores según Banxico e INEGI para 2025-2026
$inpc_data = [
    // 2025
    '01-2025' => 138.343, // Enero 2025
    '02-2025' => 138.726, // Febrero 2025
    '03-2025' => 139.161, // Marzo 2025
    '04-2025' => 139.620, // Abril 2025
    '05-2025' => 140.012, // Mayo 2025
    '06-2025' => 140.405, // Junio 2025
    '07-2025' => 140.780, // Julio 2025
    '08-2025' => 140.867, // Agosto 2025
    '09-2025' => 141.197, // Septiembre 2025
    '10-2025' => 141.708, // Octubre 2025
    '11-2025' => 142.645, // Noviembre 2025
    '12-2025' => 143.042, // Diciembre 2025
    // 2026
    '01-2026' => 143.588, // Enero 2026
    '02-2026' => 143.588, // Febrero 2026 (no disponible, se usa el último disponible)
    '03-2026' => 143.588, // Marzo 2026 (no disponible, se usa el último disponible)
    '04-2026' => 143.588, // Abril 2026 (no disponible, se usa el último disponible)
    '05-2026' => 143.588, // Mayo 2026 (no disponible, se usa el último disponible)
    '06-2026' => 143.588, // Junio 2026 (no disponible, se usa el último disponible)
    '07-2026' => 143.588, // Julio 2026 (no disponible, se usa el último disponible)
    '08-2026' => 143.588, // Agosto 2026 (no disponible, se usa el último disponible)
    '09-2026' => 143.588, // Septiembre 2026 (no disponible, se usa el último disponible)
    '10-2026' => 143.588, // Octubre 2026 (no disponible, se usa el último disponible)
    '11-2026' => 143.588, // Noviembre 2026 (no disponible, se usa el último disponible)
    '12-2026' => 143.588, // Diciembre 2026 (no disponible, se usa el último disponible)
];

// Tasas de recargos por mora 2026 - 2.07% mensual según SHCP
$tasa_recargo_mensual = 2.07; // Porcentaje mensual 2026
$dias_por_mes = 30.4; // Promedio de días por mes para cálculos proporcionales

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calc_type']) && $_POST['calc_type'] === 'recargos') {
    $impuesto = floatval($_POST['impuesto'] ?? 0);
    $fecha_debio_pagar = $_POST['fecha_debio_pagar'] ?? '';
    $fecha_pago = $_POST['fecha_pago'] ?? '';
    
    $error = '';
    $resultado = [];
    
    if ($impuesto <= 0) {
        $error = 'El impuesto debe ser mayor a cero.';
    } elseif (empty($fecha_debio_pagar) || empty($fecha_pago)) {
        $error = 'Debes ingresar ambas fechas.';
    } else {
        try {
            // Convertir fechas a objetos DateTime
            $fecha_debio = DateTime::createFromFormat('d/m/Y', $fecha_debio_pagar);
            $fecha_pagado = DateTime::createFromFormat('d/m/Y', $fecha_pago);
            
            if (!$fecha_debio || !$fecha_pagado) {
                $error = 'Formato de fecha inválido. Usa DD/MM/AAAA';
            } elseif ($fecha_pagado <= $fecha_debio) {
                $error = 'La fecha de pago debe ser posterior a la fecha que debió pagar.';
            } else {
                // Calcular diferencia en días y meses
                $intervalo = $fecha_debio->diff($fecha_pagado);
                $dias_mora = $intervalo->days;
                $meses_completos = floor($dias_mora / 30.4);
                $dias_restantes = $dias_mora % 30;
                
                // Obtener INPC del mes anterior a la fecha de pago
                $mes_anterior_pago = clone $fecha_pagado;
                $mes_anterior_pago->modify('-1 month');
                $inpc_mes_anterior_pago = obtener_inpc($mes_anterior_pago, $inpc_data);
                
                // Obtener INPC del mes anterior a la fecha que debió pagar
                $mes_anterior_debio = clone $fecha_debio;
                $mes_anterior_debio->modify('-1 month');
                $inpc_mes_anterior_debio = obtener_inpc($mes_anterior_debio, $inpc_data);
                
                // Calcular factor de actualización
                if ($inpc_mes_anterior_debio > 0) {
                    $factor_actualizacion = $inpc_mes_anterior_pago / $inpc_mes_anterior_debio;
                } else {
                    $factor_actualizacion = 1;
                }
                
                // Calcular importe actualizado
                $importe_actualizado = $impuesto * $factor_actualizacion;
                
                // Calcular recargos
                // Tasa mensual: 2.07% para 2026
                $recargos = 0;
                
                if ($meses_completos > 0) {
                    // Recargos por meses completos
                    $recargos += $importe_actualizado * ($tasa_recargo_mensual / 100) * $meses_completos;
                }
                
                if ($dias_restantes > 0) {
                    // Recargos proporcionales por días restantes
                    $tasa_diaria = $tasa_recargo_mensual / 30.4;
                    $recargos += $importe_actualizado * ($tasa_diaria / 100) * $dias_restantes;
                }
                
                // Total a pagar
                $total_pagar = $importe_actualizado + $recargos;
                
                $resultado_recargos = [
                    'impuesto' => $impuesto,
                    'fecha_debio_pagar' => $fecha_debio_pagar,
                    'fecha_pago' => $fecha_pago,
                    'dias_mora' => $dias_mora,
                    'meses_completos' => $meses_completos,
                    'dias_restantes' => $dias_restantes,
                    'inpc_mes_anterior_pago' => $inpc_mes_anterior_pago,
                    'inpc_mes_anterior_debio' => $inpc_mes_anterior_debio,
                    'factor_actualizacion' => $factor_actualizacion,
                    'tasa_recargo' => $tasa_recargo_mensual,
                    'importe_actualizado' => $importe_actualizado,
                    'recargos' => $recargos,
                    'total_pagar' => $total_pagar,
                    // Formateados
                    'impuesto_formateado' => '$' . number_format($impuesto, 2),
                    'inpc_mes_anterior_pago_formateado' => number_format($inpc_mes_anterior_pago, 3),
                    'inpc_mes_anterior_debio_formateado' => number_format($inpc_mes_anterior_debio, 3),
                    'factor_actualizacion_formateado' => number_format($factor_actualizacion, 6),
                    'tasa_recargo_formateado' => number_format($tasa_recargo_mensual, 2) . '%',
                    'importe_actualizado_formateado' => '$' . number_format($importe_actualizado, 2),
                    'recargos_formateado' => '$' . number_format($recargos, 2),
                    'total_pagar_formateado' => '$' . number_format($total_pagar, 2)
                ];
            }
        } catch (Exception $e) {
            $error = 'Error al procesar las fechas: ' . $e->getMessage();
        }
    }
}

// Función para obtener INPC de un mes específico
function obtener_inpc($fecha, $inpc_data) {
    $mes = $fecha->format('m');
    $anio = $fecha->format('Y');
    $key = $mes . '-' . $anio;
    
    if (isset($inpc_data[$key])) {
        return $inpc_data[$key];
    } else {
        // Si no hay datos para ese mes, usar el último disponible
        $ultimo_disponible = end($inpc_data);
        reset($inpc_data);
        return $ultimo_disponible;
    }
}
?>

<div class="calculadora-item" id="calculadora-recargos" data-calc="recargos">
    <h3 class="calculadora-nombre">Calculadora de Recargos 2026</h3>
    <p class="calculadora-subtitulo">Calcula recargos por pago extemporáneo de impuestos federales</p>
    
    <form method="POST" action="" class="calculadora-form">
        <input type="hidden" name="calc_type" value="recargos">
        
        <!-- Impuesto -->
        <div class="form-group">
            <label for="impuesto">Impuesto</label>
            <div class="input-wrapper">
                <span class="currency-symbol">$</span>
                <input type="number" 
                       name="impuesto" 
                       id="impuesto" 
                       class="form-input" 
                       step="0.01" 
                       min="0"
                       placeholder="0.00"
                       value="<?php echo $_POST['impuesto'] ?? '3200'; ?>"
                       required>
            </div>
        </div>

        <!-- Fecha que debió pagar -->
        <div class="form-group">
            <label for="fecha_debio_pagar">Ingresa la fecha que debió pagar (DD-MM-AAAA)</label>
            <input type="text" 
                   name="fecha_debio_pagar" 
                   id="fecha_debio_pagar" 
                   class="form-input" 
                   placeholder="dd/mm/aaaa"
                   value="<?php echo $_POST['fecha_debio_pagar'] ?? '18/09/2025'; ?>"
                   required>
            <small class="help-text">Formato: DD/MM/AAAA (ejemplo: 18/09/2025)</small>
        </div>

        <!-- Fecha de pago -->
        <div class="form-group">
            <label for="fecha_pago">Ingresa la fecha de pago (DD-MM-AAAA)</label>
            <input type="text" 
                   name="fecha_pago" 
                   id="fecha_pago" 
                   class="form-input" 
                   placeholder="dd/mm/aaaa"
                   value="<?php echo $_POST['fecha_pago'] ?? '28/02/2026'; ?>"
                   required>
            <small class="help-text">Formato: DD/MM/AAAA (ejemplo: 28/02/2026)</small>
        </div>

        <!-- Botón Calcular -->
        <div class="form-group">
            <button type="submit" class="btn-calcular-principal">
                Calcular Recargos
            </button>
        </div>
    </form>

    <?php if(isset($error) && $error): ?>
        <div class="error-mensaje" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-top: 20px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <?php if($resultado_recargos): ?>
    <div class="resultados-calculadora">
        <h4 class="resultados-titulo">Cálculo del impuesto</h4>
        
        <!-- Primera tabla: INPC y factores -->
        <div class="tabla-container" style="margin-bottom: 20px;">
            <table class="tabla-resultados tabla-recargos">
                <thead>
                    <tr>
                        <th></th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>INPC del mes anterior al pago</td>
                        <td class="valor-moneda"><?php echo $resultado_recargos['inpc_mes_anterior_pago_formateado']; ?></td>
                    </tr>
                    <tr>
                        <td>INPC del mes anterior al que debió pagar</td>
                        <td class="valor-moneda"><?php echo $resultado_recargos['inpc_mes_anterior_debio_formateado']; ?></td>
                    </tr>
                    <tr>
                        <td>Factor de actualización</td>
                        <td class="valor-moneda"><?php echo $resultado_recargos['factor_actualizacion_formateado']; ?></td>
                    </tr>
                    <tr>
                        <td>Tasa de recargos aplicable</td>
                        <td class="valor-moneda"><?php echo $resultado_recargos['tasa_recargo_formateado']; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Segunda tabla: Importes -->
        <div class="tabla-container">
            <table class="tabla-resultados tabla-recargos">
                <thead>
                    <tr>
                        <th></th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Importe histórico</td>
                        <td class="valor-moneda"><?php echo $resultado_recargos['impuesto_formateado']; ?></td>
                    </tr>
                    <tr>
                        <td>Importe de actualización</td>
                        <td class="valor-moneda"><?php echo $resultado_recargos['importe_actualizado_formateado']; ?></td>
                    </tr>
                    <tr>
                        <td>Importe de Recargos</td>
                        <td class="valor-moneda"><?php echo $resultado_recargos['recargos_formateado']; ?></td>
                    </tr>
                    <tr class="total-row">
                        <td><strong>Total de impuestos a pagar</strong></td>
                        <td class="valor-moneda"><strong><?php echo $resultado_recargos['total_pagar_formateado']; ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Nota informativa con detalles del cálculo -->
        <div class="nota-informativa" style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
            <p style="margin: 0; color: #666; font-size: 14px;">
                <strong>Detalles del cálculo 2026:</strong><br>
                • Período de mora: <?php echo $resultado_recargos['dias_mora']; ?> días 
                  (<?php echo $resultado_recargos['meses_completos']; ?> meses y <?php echo $resultado_recargos['dias_restantes']; ?> días)<br>
                • Tasa mensual de recargos 2026: 2.07% según SHCP<br>
                • INPC utilizado: Datos oficiales del INEGI/Banxico<br>
                • Cálculo basado en el Artículo 21 del CFF y reglas 2.1.20 de la RMF 2026
            </p>
        </div>

        <!-- Explicación del cálculo -->
        <div class="explicacion-calculo" style="margin-top: 20px;">
            <details>
                <summary style="cursor: pointer; color: #667eea; font-weight: 500;">Ver fórmula de cálculo</summary>
                <div style="margin-top: 10px; padding: 15px; background: #f0f4ff; border-radius: 8px; color: #000000;">
                    <p><strong style="color: #000000;">Cálculo de actualización y recargos 2026:</strong></p>
                    <ul style="margin: 10px 0; padding-left: 20px; color: #000000;">
                        <li><strong>Factor de actualización</strong> = INPC mes anterior al pago ÷ INPC mes anterior a la fecha que debió pagar</li>
                        <li><?php echo $resultado_recargos['inpc_mes_anterior_pago_formateado']; ?> ÷ <?php echo $resultado_recargos['inpc_mes_anterior_debio_formateado']; ?> = <?php echo $resultado_recargos['factor_actualizacion_formateado']; ?></li>
                        <li><strong>Importe actualizado</strong> = Impuesto histórico × Factor de actualización</li>
                        <li>$<?php echo number_format($resultado_recargos['impuesto'], 2); ?> × <?php echo $resultado_recargos['factor_actualizacion_formateado']; ?> = $<?php echo number_format($resultado_recargos['importe_actualizado'], 2); ?></li>
                        <li><strong>Recargos</strong> = Importe actualizado × (Tasa mensual × Meses completos + Tasa diaria × Días restantes)</li>
                        <li>$<?php echo number_format($resultado_recargos['importe_actualizado'], 2); ?> × (<?php echo $resultado_recargos['tasa_recargo']; ?>% × <?php echo $resultado_recargos['meses_completos']; ?> meses + <?php echo number_format($resultado_recargos['tasa_recargo'] / 30.4, 4); ?>% × <?php echo $resultado_recargos['dias_restantes']; ?> días) = $<?php echo number_format($resultado_recargos['recargos'], 2); ?></li>
                        <li><strong>Total a pagar</strong> = Importe actualizado + Recargos</li>
                    </ul>
                </div>
            </details>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
/* Estilos específicos para la calculadora de Recargos */
.tabla-recargos {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.tabla-recargos thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.tabla-recargos th {
    padding: 15px;
    text-align: left;
    font-weight: 600;
}

.tabla-recargos th:last-child,
.tabla-recargos td:last-child {
    text-align: right;
}

.tabla-recargos td {
    padding: 12px 15px;
    border-bottom: 1px solid #eef2f6;
    color: #000000;
}

.tabla-recargos tbody tr:last-child td {
    border-bottom: none;
}

.tabla-recargos .total-row {
    background: linear-gradient(135deg, #f6f8fc 0%, #eef2f6 100%);
    font-weight: bold;
}

.tabla-recargos .total-row td {
    border-top: 2px solid #667eea;
    color: #000000;
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

@media (max-width: 768px) {
    .tabla-recargos {
        font-size: 14px;
    }
    
    .tabla-recargos td,
    .tabla-recargos th {
        padding: 10px;
    }
}
</style>