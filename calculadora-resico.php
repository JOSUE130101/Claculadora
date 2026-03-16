<?php
// calculadoras/calculadora-resico.php
$resultado_resico = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calc_type']) && $_POST['calc_type'] === 'resico') {
    $tipo_periodo = $_POST['tipo_periodo'] ?? 'mensual';
    $ingreso = floatval($_POST['ingreso'] ?? 0);
    
    // Determinar tasa según el monto (tabla RESICO mensual)
    $tasa = 0;
    $rango = '';
    $limite_rango = '';
    
    if ($tipo_periodo === 'mensual') {
        // Tabla mensual RESICO
        if ($ingreso <= 50000) {
            $tasa = 1.0;
            $rango = 'Hasta $50,000.00';
            $limite_rango = 50000;
        } elseif ($ingreso <= 83333.33) {
            $tasa = 1.1;
            $rango = 'Hasta $83,333.33';
            $limite_rango = 83333.33;
        } elseif ($ingreso <= 208333.33) {
            $tasa = 1.5;
            $rango = 'Hasta $208,333.33';
            $limite_rango = 208333.33;
        } elseif ($ingreso <= 3500000) {
            $tasa = 2.0;
            $rango = 'Hasta $3,500,000.00';
            $limite_rango = 3500000;
        } else {
            $tasa = 2.5;
            $rango = 'Más de $3,500,000.00';
            $limite_rango = 999999999;
        }
    } else {
        // Si es anual, convertimos a mensual para determinar la tasa
        $ingreso_mensual = $ingreso / 12;
        if ($ingreso_mensual <= 50000) {
            $tasa = 1.0;
            $rango = 'Hasta $600,000.00 anual';
            $limite_rango = 50000;
        } elseif ($ingreso_mensual <= 83333.33) {
            $tasa = 1.1;
            $rango = 'Hasta $1,000,000.00 anual';
            $limite_rango = 83333.33;
        } elseif ($ingreso_mensual <= 208333.33) {
            $tasa = 1.5;
            $rango = 'Hasta $2,500,000.00 anual';
            $limite_rango = 208333.33;
        } elseif ($ingreso_mensual <= 3500000) {
            $tasa = 2.0;
            $rango = 'Hasta $42,000,000.00 anual';
            $limite_rango = 3500000;
        } else {
            $tasa = 2.5;
            $rango = 'Más de $42,000,000.00 anual';
            $limite_rango = 999999999;
        }
    }
    
    // Calcular ISR
    $isr = ($ingreso * $tasa) / 100;
    
    $resultado_resico = [
        'ingreso' => $ingreso,
        'tipo_periodo' => $tipo_periodo,
        'tasa' => $tasa,
        'rango' => $rango,
        'limite_rango' => $limite_rango,
        'isr' => $isr,
        'ingreso_formateado' => '$' . number_format($ingreso, 2),
        'isr_formateado' => '$' . number_format($isr, 2)
    ];
}

// Rangos para la tabla RESICO
$rangos_resico = [
    ['limite' => 50000, 'tasa' => 1.0, 'texto' => 'Hasta $50,000.00'],
    ['limite' => 83333.33, 'tasa' => 1.1, 'texto' => 'Hasta $83,333.33'],
    ['limite' => 208333.33, 'tasa' => 1.5, 'texto' => 'Hasta $208,333.33'],
    ['limite' => 3500000, 'tasa' => 2.0, 'texto' => 'Hasta $3,500,000.00'],
    ['limite' => 999999999, 'tasa' => 2.5, 'texto' => 'Más de $3,500,000.00']
];
?>

<div class="calculadora-item" id="calculadora-resico" data-calc="resico">
    <h3 class="calculadora-nombre">Calculadora de RESICO</h3>
    <p class="calculadora-subtitulo">Calcula el ISR para Personas Físicas en el Régimen de RESICO</p>
    
    <form method="POST" action="" class="calculadora-form">
        <input type="hidden" name="calc_type" value="resico">
        
        <!-- Tipo de Período (Radio Buttons) -->
        <div class="form-group">
            <label>Elige una opción para el cálculo</label>
            <div class="radio-group">
                <div class="radio-item">
                    <input type="radio" 
                           name="tipo_periodo" 
                           id="periodo_mensual" 
                           value="mensual" 
                           <?php echo (($_POST['tipo_periodo'] ?? 'mensual') === 'mensual') ? 'checked' : ''; ?>>
                    <label for="periodo_mensual">Ingresos Mensuales</label>
                </div>
                <div class="radio-item">
                    <input type="radio" 
                           name="tipo_periodo" 
                           id="periodo_anual" 
                           value="anual"
                           <?php echo (($_POST['tipo_periodo'] ?? '') === 'anual') ? 'checked' : ''; ?>>
                    <label for="periodo_anual">Ingresos Anuales</label>
                </div>
            </div>
        </div>

        <!-- Captura de ingresos -->
        <div class="form-group">
            <label for="resico_ingreso">Captura los ingresos</label>
            <div class="input-wrapper">
                <span class="currency-symbol">$</span>
                <input type="number" 
                       name="ingreso" 
                       id="resico_ingreso" 
                       class="form-input" 
                       step="0.01" 
                       min="0"
                       placeholder="0.00"
                       value="<?php echo $_POST['ingreso'] ?? '25000'; ?>"
                       required>
            </div>
        </div>

        <!-- Botón Calcular -->
        <div class="form-group">
            <button type="submit" class="btn-calcular-principal">
                Calcular ISR RESICO
            </button>
        </div>
    </form>

    <?php if($resultado_resico): ?>
    <div class="resultados-calculadora">
        <h4 class="resultados-titulo">Cálculo del ISR RESICO Personas Físicas</h4>
        
        <!-- Cálculo principal -->
        <div class="calculo-principal">
            <div class="calculo-fila">
                <span class="calculo-operador">📊</span>
                <span class="calculo-label">Ingresos <?php echo ucfirst($resultado_resico['tipo_periodo']); ?></span>
                <span class="calculo-valor"><?php echo $resultado_resico['ingreso_formateado']; ?></span>
            </div>
            <div class="calculo-fila">
                <span class="calculo-operador">✗</span>
                <span class="calculo-label">Tasa Aplicable</span>
                <span class="calculo-valor"><?php echo $resultado_resico['tasa']; ?> %</span>
            </div>
            <div class="calculo-fila resultado">
                <span class="calculo-operador">=</span>
                <span class="calculo-label">ISR a Declarar</span>
                <span class="calculo-valor resultado-valor"><?php echo $resultado_resico['isr_formateado']; ?></span>
            </div>
        </div>

        <!-- Tabla RESICO -->
        <h4 class="resultados-titulo" style="margin-top: 30px;">Tabla RESICO para pago de ISR Mensual de personas físicas</h4>
        
        <div class="tabla-container">
            <table class="tabla-resultados tabla-resico">
                <thead>
                    <tr>
                        <th>Monto de los ingresos</th>
                        <th>Tasa aplicable</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rangos_resico as $index => $rango): ?>
                        <?php 
                        $ingreso_comparar = $resultado_resico['ingreso'];
                        if ($resultado_resico['tipo_periodo'] === 'anual') {
                            $ingreso_comparar = $ingreso_comparar / 12;
                        }
                        
                        $es_rango_actual = false;
                        if ($index === 0 && $ingreso_comparar <= 50000) {
                            $es_rango_actual = true;
                        } elseif ($index === 1 && $ingreso_comparar > 50000 && $ingreso_comparar <= 83333.33) {
                            $es_rango_actual = true;
                        } elseif ($index === 2 && $ingreso_comparar > 83333.33 && $ingreso_comparar <= 208333.33) {
                            $es_rango_actual = true;
                        } elseif ($index === 3 && $ingreso_comparar > 208333.33 && $ingreso_comparar <= 3500000) {
                            $es_rango_actual = true;
                        } elseif ($index === 4 && $ingreso_comparar > 3500000) {
                            $es_rango_actual = true;
                        }
                        ?>
                        <tr class="<?php echo $es_rango_actual ? 'rango-actual' : ''; ?>">
                            <td>
                                <?php if($es_rango_actual): ?>
                                    <strong>Tus ingresos entran en este rango:</strong> 
                                    $<?php echo number_format($resultado_resico['ingreso'], 2); ?>
                                <?php else: ?>
                                    <?php echo $rango['texto']; ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo number_format($rango['tasa'], 1); ?> %</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Comparación con otro régimen -->
        <h4 class="resultados-titulo" style="margin-top: 30px;">En otro régimen diferente al RESICO pagarías:</h4>
        
        <div class="percepcion-container">
            <div class="percepcion-grid">
                <div class="percepcion-item">
                    <span class="label">Ingreso gravado:</span>
                    <span class="valor">$<?php echo number_format($resultado_resico['ingreso'], 2); ?></span>
                </div>
                <div class="percepcion-item">
                    <span class="label">Subsidio a entregar al empleado:</span>
                    <span class="valor">$0.00</span>
                </div>
                <div class="percepcion-item">
                    <span class="label">Impuesto a retener:</span>
                    <span class="valor">$0.00</span>
                </div>
                <div class="percepcion-item total">
                    <span class="label">Percepción efectiva:</span>
                    <span class="valor">$<?php echo number_format($resultado_resico['ingreso'], 2); ?></span>
                </div>
            </div>
        </div>

        <!-- Nota informativa -->
        <div class="resico-link" style="margin-top: 20px;">
            <p>
                💡 El Régimen Simplificado de Confianza (RESICO) ofrece tasas preferenciales del 1% al 2.5% sobre tus ingresos.
                <br>
                <a href="#" class="calc-link" data-calc="isr">Compara con la Calculadora de ISR General</a>
            </p>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
/* Estilos específicos para la calculadora RESICO */
.calculo-principal {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    padding: 25px;
    color: white;
    margin-bottom: 25px;
}

.calculo-fila {
    display: flex;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.calculo-fila:last-child {
    border-bottom: none;
}

.calculo-operador {
    font-size: 20px;
    font-weight: bold;
    width: 40px;
    text-align: center;
}

.calculo-label {
    flex: 1;
    font-size: 16px;
}

.calculo-valor {
    font-size: 18px;
    font-weight: bold;
}

.calculo-fila.resultado {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    margin-top: 10px;
    padding: 15px 12px;
}

.resultado-valor {
    font-size: 24px;
    color: #ffd700;
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

.tabla-resico tbody tr {
    transition: background-color 0.3s;
}

.tabla-resico .rango-actual {
    background-color: #e3f2fd;
    border-left: 4px solid #667eea;
    font-weight: 500;
}

.tabla-resico .rango-actual td:first-child {
    color: #2c3e50;
}

@media (max-width: 768px) {
    .radio-group {
        flex-direction: column;
        gap: 15px;
    }
    
    .calculo-fila {
        flex-wrap: wrap;
    }
    
    .calculo-label {
        width: 100%;
        margin: 5px 0;
    }
}
</style>

<!-- Script ELIMINADO - Ya no hay actualización automática -->