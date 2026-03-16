<?php
// calculadoras/calculadora-arrendamiento.php
$resultado_arrendamiento = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calc_type']) && $_POST['calc_type'] === 'arrendamiento') {
    $tipo_calculo = $_POST['tipo_calculo'] ?? 'bruto';
    $cantidad = floatval($_POST['cantidad'] ?? 0);
    
    // Tasas para arrendamiento
    $tasa_iva = 16; // 16%
    $tasa_retencion_isr = 10; // 10% sobre arrendamiento
    $tasa_retencion_iva = 10.6667; // 2/3 del IVA (66.67% del IVA)
    
    if ($tipo_calculo === 'bruto') {
        // Cálculo desde importe bruto (arrendamiento)
        $arrendamiento = $cantidad;
        $iva = $arrendamiento * ($tasa_iva / 100);
        $subtotal = $arrendamiento + $iva;
        $retencion_isr = $arrendamiento * ($tasa_retencion_isr / 100);
        $retencion_iva = $iva * 0.66667; // 2/3 del IVA
        $total = $subtotal - $retencion_isr - $retencion_iva;
    } else {
        // Cálculo desde importe neto (total a pagar)
        // Fórmula: Total = A + (A * 0.16) - (A * 0.10) - (A * 0.16 * 0.66667)
        // Simplificando: Total = A * (1 + 0.16 - 0.10 - 0.106667) = A * 0.953333
        $arrendamiento = $cantidad / 0.953333;
        $iva = $arrendamiento * ($tasa_iva / 100);
        $subtotal = $arrendamiento + $iva;
        $retencion_isr = $arrendamiento * ($tasa_retencion_isr / 100);
        $retencion_iva = $iva * 0.66667;
        $total = $cantidad;
    }
    
    $resultado_arrendamiento = [
        'tipo_calculo' => $tipo_calculo,
        'cantidad_ingresada' => $cantidad,
        'arrendamiento' => $arrendamiento,
        'iva' => $iva,
        'subtotal' => $subtotal,
        'retencion_isr' => $retencion_isr,
        'retencion_iva' => $retencion_iva,
        'total' => $total,
        'arrendamiento_formateado' => '$' . number_format($arrendamiento, 2),
        'iva_formateado' => '$' . number_format($iva, 2),
        'subtotal_formateado' => '$' . number_format($subtotal, 2),
        'retencion_isr_formateado' => '$' . number_format($retencion_isr, 2),
        'retencion_iva_formateado' => '$' . number_format($retencion_iva, 2),
        'total_formateado' => '$' . number_format($total, 2)
    ];
}
?>

<div class="calculadora-item" id="calculadora-arrendamiento" data-calc="arrendamiento">
    <h3 class="calculadora-nombre">Calculadora de Arrendamientos</h3>
    <p class="calculadora-subtitulo">Calcula el arrendamiento con IVA y retenciones</p>
    
    <form method="POST" action="" class="calculadora-form">
        <input type="hidden" name="calc_type" value="arrendamiento">
        
        <!-- Tipo de Cálculo (Radio Buttons) -->
        <div class="form-group">
            <label>Elige una opción para el cálculo</label>
            <div class="radio-group">
                <div class="radio-item">
                    <input type="radio" 
                           name="tipo_calculo" 
                           id="calculo_bruto" 
                           value="bruto" 
                           <?php echo (($_POST['tipo_calculo'] ?? 'bruto') === 'bruto') ? 'checked' : ''; ?>>
                    <label for="calculo_bruto">Importe Bruto</label>
                </div>
                <div class="radio-item">
                    <input type="radio" 
                           name="tipo_calculo" 
                           id="calculo_neto" 
                           value="neto"
                           <?php echo (($_POST['tipo_calculo'] ?? '') === 'neto') ? 'checked' : ''; ?>>
                    <label for="calculo_neto">Importe Neto</label>
                </div>
            </div>
            <small class="help-text">
                <?php if(($_POST['tipo_calculo'] ?? 'bruto') === 'bruto'): ?>
                    De la cantidad ingresada se partirá para el cálculo de impuestos.
                <?php else: ?>
                    Importe total a pagar con impuestos incluidos (desglose de impuestos).
                <?php endif; ?>
            </small>
        </div>

        <!-- Captura de cantidad -->
        <div class="form-group">
            <label for="arrendamiento_cantidad">Ingresa la cantidad</label>
            <div class="input-wrapper">
                <span class="currency-symbol">$</span>
                <input type="number" 
                       name="cantidad" 
                       id="arrendamiento_cantidad" 
                       class="form-input" 
                       step="0.01" 
                       min="0"
                       placeholder="0.00"
                       value="<?php echo $_POST['cantidad'] ?? '150'; ?>"
                       required>
            </div>
        </div>

        <!-- Botón Calcular -->
        <div class="form-group">
            <button type="submit" class="btn-calcular-principal">
                Calcular
            </button>
        </div>
    </form>

    <?php if($resultado_arrendamiento): ?>
    <div class="resultados-calculadora">
        <h4 class="resultados-titulo">Cálculo del impuesto</h4>
        
        <!-- Tabla de resultados -->
        <div class="tabla-container">
            <table class="tabla-resultados tabla-arrendamiento">
                <thead>
                    <tr>
                        <th></th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Arrendamiento</td>
                        <td class="valor-moneda"><?php echo $resultado_arrendamiento['arrendamiento_formateado']; ?></td>
                    </tr>
                    <tr>
                        <td>IVA</td>
                        <td class="valor-moneda"><?php echo $resultado_arrendamiento['iva_formateado']; ?></td>
                    </tr>
                    <tr>
                        <td>Subtotal</td>
                        <td class="valor-moneda"><?php echo $resultado_arrendamiento['subtotal_formateado']; ?></td>
                    </tr>
                    <tr>
                        <td>Retención ISR</td>
                        <td class="valor-moneda"><?php echo $resultado_arrendamiento['retencion_isr_formateado']; ?></td>
                    </tr>
                    <tr>
                        <td>Retención IVA</td>
                        <td class="valor-moneda"><?php echo $resultado_arrendamiento['retencion_iva_formateado']; ?></td>
                    </tr>
                    <tr class="total-row">
                        <td><strong>Total</strong></td>
                        <td class="valor-moneda"><strong><?php echo $resultado_arrendamiento['total_formateado']; ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Nota informativa con tasas -->
        <div class="nota-informativa" style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
            <p style="margin: 0; color: #666; font-size: 14px;">
                <strong>Tasas aplicadas:</strong><br>
                • IVA: 16%<br>
                • Retención ISR: 10% sobre el arrendamiento<br>
                • Retención IVA: 2/3 del IVA (10.6667%)<br>
                <br>
                <strong>Leyenda:</strong> Arrendamiento, IVA, Subtotal, Retención ISR, Retención IVA, Total
            </p>
        </div>

        <!-- Explicación del cálculo -->
        <div class="explicacion-calculo" style="margin-top: 20px;">
            <details>
                <summary style="cursor: pointer; color: #667eea; font-weight: 500;">Ver explicación del cálculo</summary>
                <div style="margin-top: 10px; padding: 15px; background: #f0f4ff; border-radius: 8px; color: #000000;">
                    <?php if($resultado_arrendamiento['tipo_calculo'] === 'bruto'): ?>
                        <p><strong style="color: #000000;">Cálculo desde Importe Bruto:</strong></p>
                        <ul style="margin: 10px 0; padding-left: 20px; color: #000000;">
                            <li>Arrendamiento: $<?php echo number_format($resultado_arrendamiento['cantidad_ingresada'], 2); ?></li>
                            <li>IVA (16%): $<?php echo number_format($resultado_arrendamiento['cantidad_ingresada'] * 0.16, 2); ?></li>
                            <li>Subtotal = Arrendamiento + IVA</li>
                            <li>Retención ISR (10% sobre arrendamiento): $<?php echo number_format($resultado_arrendamiento['cantidad_ingresada'] * 0.10, 2); ?></li>
                            <li>Retención IVA (2/3 del IVA): $<?php echo number_format($resultado_arrendamiento['cantidad_ingresada'] * 0.16 * 0.66667, 2); ?></li>
                            <li>Total = Subtotal - Retenciones</li>
                        </ul>
                    <?php else: ?>
                        <p><strong style="color: #000000;">Cálculo desde Importe Neto:</strong></p>
                        <ul style="margin: 10px 0; padding-left: 20px; color: #000000;">
                            <li>Total deseado: $<?php echo number_format($resultado_arrendamiento['cantidad_ingresada'], 2); ?></li>
                            <li>Arrendamiento = Total ÷ 0.953333 (factor de retenciones)</li>
                            <li>IVA (16% sobre arrendamiento): $<?php echo number_format($resultado_arrendamiento['iva'], 2); ?></li>
                            <li>Subtotal = Arrendamiento + IVA</li>
                            <li>Retención ISR (10% sobre arrendamiento): $<?php echo number_format($resultado_arrendamiento['retencion_isr'], 2); ?></li>
                            <li>Retención IVA (2/3 del IVA): $<?php echo number_format($resultado_arrendamiento['retencion_iva'], 2); ?></li>
                            <li>Total = Subtotal - Retenciones = $<?php echo number_format($resultado_arrendamiento['cantidad_ingresada'], 2); ?></li>
                        </ul>
                    <?php endif; ?>
                </div>
            </details>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
/* Estilos específicos para la calculadora de Arrendamiento */
.tabla-arrendamiento {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.tabla-arrendamiento thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.tabla-arrendamiento th {
    padding: 15px;
    text-align: left;
    font-weight: 600;
}

.tabla-arrendamiento th:last-child,
.tabla-arrendamiento td:last-child {
    text-align: right;
}

.tabla-arrendamiento td {
    padding: 12px 15px;
    border-bottom: 1px solid #eef2f6;
    color: #000000;
}

.tabla-arrendamiento tbody tr:last-child td {
    border-bottom: none;
}

.tabla-arrendamiento .total-row {
    background: linear-gradient(135deg, #f6f8fc 0%, #eef2f6 100%);
    font-weight: bold;
}

.tabla-arrendamiento .total-row td {
    border-top: 2px solid #667eea;
    color: #000000;
}

.valor-moneda {
    font-family: 'Courier New', monospace;
    font-weight: 500;
    color: #000000 !important;
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

.help-text {
    display: block;
    margin-top: 8px;
    color: #666;
    font-size: 13px;
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
    .radio-group {
        flex-direction: column;
        gap: 15px;
    }
    
    .tabla-arrendamiento {
        font-size: 14px;
    }
    
    .tabla-arrendamiento td,
    .tabla-arrendamiento th {
        padding: 10px;
    }
}
</style>