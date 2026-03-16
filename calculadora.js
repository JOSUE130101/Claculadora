// =============================================
// CALCULADORA CONTABLE - MD CONSULTORÍA
// =============================================

// Función para formatear números
function formatearDinero(cantidad) {
    if (isNaN(cantidad)) return '$0.00';
    
    return '$' + cantidad.toLocaleString('es-MX', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// =============================================
// INICIALIZAR ESTILOS DE ANIMACIÓN
// =============================================
function inicializarEstilosAnimacion() {
    // Verificar si los estilos ya existen
    if (document.getElementById('estilos-animacion-widget')) {
        return;
    }
    
    const style = document.createElement('style');
    style.id = 'estilos-animacion-widget';
    style.textContent = `
        @keyframes slideIn {
            from { 
                transform: translateX(100%) translateY(-20px); 
                opacity: 0; 
            }
            to { 
                transform: translateX(0) translateY(0); 
                opacity: 1; 
            }
        }
        
        @keyframes slideOut {
            from { 
                transform: translateX(0) translateY(0); 
                opacity: 1; 
            }
            to { 
                transform: translateX(100%) translateY(-20px); 
                opacity: 0; 
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .custom-alerta-widget {
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        
        .custom-alerta-widget:hover {
            transform: translateY(-2px);
        }
    `;
    
    document.head.appendChild(style);
}

// =============================================
// FUNCIONES AUXILIARES
// =============================================
function animarResultadoWidget(widgetId) {
    const resultadoDiv = document.querySelector(`#${widgetId} .widget-resultado`);
    if (resultadoDiv) {
        // Resetear animación
        resultadoDiv.style.animation = 'none';
        resultadoDiv.offsetHeight; // Trigger reflow
        
        // Aplicar animación
        resultadoDiv.style.animation = 'slideInRight 0.3s ease';
        
        // Efecto de brillo
        resultadoDiv.style.boxShadow = '0 0 20px rgba(37, 99, 235, 0.3)';
        setTimeout(() => {
            resultadoDiv.style.boxShadow = '';
            resultadoDiv.style.transition = 'box-shadow 0.5s ease';
        }, 500);
    }
}

function mostrarAlertaWidget(mensaje, tipo = 'info') {
    // Eliminar alertas anteriores
    const alertasAnteriores = document.querySelectorAll('.custom-alerta-widget');
    alertasAnteriores.forEach(alerta => {
        if (alerta.parentNode) {
            alerta.parentNode.removeChild(alerta);
        }
    });
    
    // Colores según tipo
    const colores = {
        'info': '#2563eb',      // Azul
        'error': '#ef4444',     // Rojo
        'success': '#10b981',   // Verde
        'warning': '#f59e0b'    // Naranja
    };
    
    const color = colores[tipo] || colores.info;
    
    // Iconos según tipo
    const iconos = {
        'info': 'ℹ️',
        'error': '❌',
        'success': '✅',
        'warning': '⚠️'
    };
    
    const icono = iconos[tipo] || iconos.info;
    
    // Crear alerta temporal
    const alerta = document.createElement('div');
    alerta.className = 'custom-alerta-widget';
    alerta.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${color};
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        z-index: 10000;
        animation: slideIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        max-width: 400px;
        font-size: 0.9rem;
        line-height: 1.5;
        white-space: pre-line;
        border-left: 4px solid rgba(255,255,255,0.3);
        backdrop-filter: blur(10px);
        cursor: pointer;
        border: 1px solid rgba(255, 255, 255, 0.1);
    `;
    
    // Contenido con icono
    alerta.innerHTML = `
        <div style="display: flex; align-items: flex-start; gap: 10px;">
            <span style="font-size: 1.2rem; flex-shrink: 0;">${icono}</span>
            <div style="flex: 1;">${mensaje}</div>
        </div>
    `;
    
    document.body.appendChild(alerta);
    
    // Auto-eliminación después de tiempo
    const tiempoVisible = tipo === 'error' ? 5000 : 5000;
    
    setTimeout(() => {
        alerta.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            if (alerta.parentNode) {
                alerta.parentNode.removeChild(alerta);
            }
        }, 300);
    }, tiempoVisible);
    
    // Permitir cerrar manualmente
    alerta.addEventListener('click', function() {
        this.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            if (this.parentNode) {
                this.parentNode.removeChild(this);
            }
        }, 300);
    });
}

// =============================================
// CONFIGURACIÓN DE CALCULADORA RESICO
// =============================================
function configurarCalculadoraRESICO() {
    const tipoSelect = document.getElementById('widget-tipo-resico');
    const labelIngreso = document.getElementById('widget-label-resico');
    const inputIngreso = document.getElementById('widget-ingreso-resico');
    
    if (tipoSelect && labelIngreso && inputIngreso) {
        // Actualizar al cambiar el select
        tipoSelect.addEventListener('change', function() {
            actualizarLabelsRESICO();
        });
        
        // También actualizar al cargar la página
        actualizarLabelsRESICO();
        
        // Limpiar input al cambiar tipo
        tipoSelect.addEventListener('change', function() {
            inputIngreso.value = '';
            limpiarResultadosRESICO();
        });
    }
}

function actualizarLabelsRESICO() {
    const tipoSelect = document.getElementById('widget-tipo-resico');
    const labelIngreso = document.getElementById('widget-label-resico');
    
    if (!tipoSelect || !labelIngreso) return;
    
    if (tipoSelect.value === 'mensual-a-anual') {
        labelIngreso.textContent = 'Ingreso Mensual:';
    } else {
        labelIngreso.textContent = 'Ingreso Anual:';
    }
}

function limpiarResultadosRESICO() {
    const resultados = [
        'widget-ingreso-equivalente',
        'widget-isr-resico',
        'widget-tasa-resico'
    ];
    
    resultados.forEach(id => {
        const elemento = document.getElementById(id);
        if (elemento) {
            if (id === 'widget-tasa-resico') {
                elemento.textContent = '0%';
            } else {
                elemento.textContent = '$0.00';
            }
        }
    });
}

// =============================================
// FUNCIÓN PARA CALCULAR ISR RÉGIMEN GENERAL
// =============================================
function calcularISRGeneral(ingresoAnual) {
    let isr = 0;
    
    if (ingresoAnual <= 0) return 0;
    
    // Tabla ISR Personas Físicas 2024 (exacta)
    const tramosISR = [
        { limite: 8830, tasa: 0, cuotaFija: 0 },
        { limite: 10293.36, tasa: 0.0192, cuotaFija: 0 },
        { limite: 20786.37, tasa: 0.064, cuotaFija: 140.29 },
        { limite: 36586.52, tasa: 0.1088, cuotaFija: 1024.92 },
        { limite: 51437.58, tasa: 0.16, cuotaFija: 2744.17 },
        { limite: 86217.60, tasa: 0.1792, cuotaFija: 4162.29 },
        { limite: 103218.00, tasa: 0.2136, cuotaFija: 8385.47 },
        { limite: 123580.20, tasa: 0.2352, cuotaFija: 10976.00 },
        { limite: 249243.48, tasa: 0.30, cuotaFija: 13507.19 },
        { limite: 392841.96, tasa: 0.32, cuotaFija: 51231.65 },
        { limite: 750000.00, tasa: 0.34, cuotaFija: 97169.57 },
        { limite: 1000000.00, tasa: 0.35, cuotaFija: 218669.57 },
        { limite: Infinity, tasa: 0.36, cuotaFija: 306169.57 }
    ];
    
    for (let i = 0; i < tramosISR.length; i++) {
        const tramo = tramosISR[i];
        const tramoAnterior = i > 0 ? tramosISR[i-1] : { limite: 0 };
        
        if (ingresoAnual > tramoAnterior.limite) {
            const baseTramo = Math.min(ingresoAnual - tramoAnterior.limite, tramo.limite - tramoAnterior.limite);
            
            if (baseTramo > 0) {
                isr = tramo.cuotaFija + (baseTramo * tramo.tasa);
            }
        }
        
        if (ingresoAnual <= tramo.limite) break;
    }
    
    return Math.max(0, isr);
}

// =============================================
// FUNCIÓN PRINCIPAL CALCULAR RESICO
// =============================================
function calcularRESICOWidget() {
    const tipoCalculo = document.getElementById('widget-tipo-resico').value;
    const ingresoInput = parseFloat(document.getElementById('widget-ingreso-resico').value) || 0;
    const actividad = document.getElementById('widget-actividad-resico').value;
    
    // Validación básica
    if (ingresoInput <= 0) {
        mostrarAlertaWidget('Por favor ingresa un ingreso válido mayor a cero.', 'error');
        return;
    }
    
    // Convertir según tipo de cálculo
    let ingresoMensual, ingresoAnual;
    
    if (tipoCalculo === 'mensual-a-anual') {
        ingresoMensual = ingresoInput;
        ingresoAnual = ingresoMensual * 12;
    } else {
        ingresoAnual = ingresoInput;
        ingresoMensual = ingresoAnual / 12;
    }
    
    // ===========================================
    // TABLA OFICIAL RESICO 2024 (Tramos escalonados)
    // ===========================================
    const tramosRESICO = [
        { limite: 25000, tasa: 0.010, nombre: "1.0%" },     // 1.0% hasta $25,000
        { limite: 50000, tasa: 0.011, nombre: "1.1%" },     // 1.1% hasta $50,000
        { limite: 83333.33, tasa: 0.015, nombre: "1.5%" },  // 1.5% hasta $83,333.33
        { limite: 208333.33, tasa: 0.020, nombre: "2.0%" }, // 2.0% hasta $208,333.33
        { limite: 3500000, tasa: 0.025, nombre: "2.5%" }    // 2.5% hasta $3,500,000
    ];
    
    // Validar límites
    if (ingresoAnual < 0) {
        mostrarAlertaWidget('El ingreso no puede ser negativo.', 'error');
        return;
    }
    
    if (ingresoAnual > 3500000) {
        mostrarAlertaWidget('⚠️ El ingreso excede el límite máximo para RESICO ($3,500,000 anuales).', 'warning');
    }
    
    if (ingresoAnual < 25000) {
        mostrarAlertaWidget('ℹ️ El ingreso está por debajo del mínimo para RESICO. Considera el régimen de asalariado o honorarios.', 'info');
    }
    
    // Calcular ISR según tramos RESICO
    let isrAnual = 0;
    let ingresoRestante = ingresoAnual;
    let tramoAplicado = null;
    
    for (let i = 0; i < tramosRESICO.length; i++) {
        const tramo = tramosRESICO[i];
        const tramoAnterior = i > 0 ? tramosRESICO[i-1] : { limite: 0, tasa: 0 };
        
        if (ingresoAnual > tramoAnterior.limite) {
            const baseTramo = Math.min(ingresoRestante, tramo.limite - tramoAnterior.limite);
            
            if (baseTramo > 0) {
                isrAnual += baseTramo * tramo.tasa;
                tramoAplicado = tramo;
            }
            
            ingresoRestante -= baseTramo;
        }
        
        if (ingresoRestante <= 0) break;
    }
    
    const isrMensual = isrAnual / 12;
    
    // ===========================================
    // CÁLCULO COMPARATIVO (Régimen General)
    // ===========================================
    const isrGeneral = calcularISRGeneral(ingresoAnual);
    const isrGeneralMensual = isrGeneral / 12;
    
    // Calcular comparativa
    const ahorro = isrGeneral - isrAnual;
    const porcentajeAhorro = isrGeneral > 0 ? ((ahorro / isrGeneral) * 100).toFixed(1) : 0;
    
    // ===========================================
    // ACTUALIZAR RESULTADOS EN EL WIDGET
    // ===========================================
    const tasaPorcentaje = tramoAplicado ? tramoAplicado.nombre : "0%";
    
    // Actualizar resultados principales
    document.getElementById('widget-ingreso-equivalente').textContent = 
        tipoCalculo === 'mensual-a-anual' 
            ? `$${ingresoAnual.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})} anual` 
            : `$${ingresoMensual.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})} mensual`;
    
    document.getElementById('widget-isr-resico').textContent = 
        `$${isrAnual.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})} anual`;
    
    document.getElementById('widget-tasa-resico').textContent = tasaPorcentaje;
    
    // ===========================================
    // CREAR GRÁFICO COMPARATIVO
    // ===========================================
    const comparativaDiv = document.getElementById('widget-comparativa-detalle');
    if (comparativaDiv) {
        const maxValor = Math.max(isrAnual, isrGeneral, 1000);
        const alturaBase = 100;
        const alturaRESICO = (isrAnual / maxValor) * alturaBase;
        const alturaGeneral = (isrGeneral / maxValor) * alturaBase;
        
        const graficoHTML = `
            <div style="margin-top: 15px; padding: 12px; background: rgba(37, 99, 235, 0.1); border-radius: 10px; border: 1px solid rgba(255, 255, 255, 0.1);">
                <div style="display: flex; align-items: flex-end; height: 140px; gap: 30px; padding: 15px; position: relative; background: rgba(255, 255, 255, 0.05); border-radius: 8px; margin-bottom: 10px;">
                    <div style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end;">
                        <div class="barra resico" style="height: ${alturaRESICO}%; width: 35px; background: linear-gradient(to top, #10b981, #059669); border-radius: 6px 6px 0 0; position: relative;">
                            <div style="position: absolute; top: -30px; left: 50%; transform: translateX(-50%); font-size: 0.75rem; font-weight: 700; color: #ffffff; background: rgba(0, 0, 0, 0.8); padding: 4px 8px; border-radius: 12px; white-space: nowrap;">
                                $${isrAnual.toLocaleString('es-MX', {minimumFractionDigits: 0, maximumFractionDigits: 0})}
                            </div>
                        </div>
                        <div style="margin-top: 10px; font-size: 0.8rem; font-weight: 600; color: #a3a3a3; text-align: center;">RESICO</div>
                    </div>
                    <div style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end;">
                        <div class="barra general" style="height: ${alturaGeneral}%; width: 35px; background: linear-gradient(to top, #ef4444, #dc2626); border-radius: 6px 6px 0 0; position: relative;">
                            <div style="position: absolute; top: -30px; left: 50%; transform: translateX(-50%); font-size: 0.75rem; font-weight: 700; color: #ffffff; background: rgba(0, 0, 0, 0.8); padding: 4px 8px; border-radius: 12px; white-space: nowrap;">
                                $${isrGeneral.toLocaleString('es-MX', {minimumFractionDigits: 0, maximumFractionDigits: 0})}
                            </div>
                        </div>
                        <div style="margin-top: 10px; font-size: 0.8rem; font-weight: 600; color: #a3a3a3; text-align: center;">GENERAL</div>
                    </div>
                </div>
                <div style="display: flex; justify-content: center; gap: 25px; margin-top: 15px; font-size: 0.85rem;">
                    <div style="display: flex; align-items: center; gap: 8px; padding: 6px 12px; background: rgba(255, 255, 255, 0.05); border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.1);">
                        <div style="width: 14px; height: 14px; border-radius: 3px; background: #10b981;"></div>
                        <span>Régimen RESICO</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; padding: 6px 12px; background: rgba(255, 255, 255, 0.05); border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.1);">
                        <div style="width: 14px; height: 14px; border-radius: 3px; background: #ef4444;"></div>
                        <span>Régimen General</span>
                    </div>
                </div>
                <div style="margin-top: 15px; padding: 10px; background: rgba(37, 99, 235, 0.1); border-radius: 8px; font-size: 0.85rem;">
                    <p style="margin: 5px 0; display: flex; justify-content: space-between;">
                        <span>• Régimen RESICO:</span>
                        <span style="font-weight: 600; color: #10b981;">$${isrAnual.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                    </p>
                    <p style="margin: 5px 0; display: flex; justify-content: space-between;">
                        <span>• Régimen General:</span>
                        <span style="font-weight: 600; color: #ef4444;">$${isrGeneral.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                    </p>
                    <p style="margin: 8px 0; padding-top: 5px; border-top: 1px solid rgba(255, 255, 255, 0.1); display: flex; justify-content: space-between;">
                        <span><strong>Diferencia:</strong></span>
                        <span style="font-weight: 700; color: ${ahorro > 0 ? '#10b981' : '#ef4444'}">
                            ${ahorro > 0 ? '✅ Ahorras:' : '⚠️ Pagas más:'} $${Math.abs(ahorro).toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
                            ${isrGeneral > 0 ? `(${Math.abs(porcentajeAhorro)}%)` : ''}
                        </span>
                    </p>
                </div>
            </div>
        `;
        
        comparativaDiv.innerHTML = graficoHTML;
    }
    
    // Efecto visual
    animarResultadoWidget('widget-resico');
    
    // ===========================================
    // MOSTRAR RESUMEN DETALLADO EN ALERTA
    // ===========================================
    let mensajeDetallado = `📊 RESUMEN DE CÁLCULO RESICO\n`;
    mensajeDetallado += `══════════════════════════════\n`;
    mensajeDetallado += `💰 INGRESO ANUAL: $${ingresoAnual.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}\n`;
    mensajeDetallado += `📅 INGRESO MENSUAL: $${ingresoMensual.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}\n\n`;
    
    mensajeDetallado += `🎯 TRAMO RESICO APLICADO: ${tasaPorcentaje}\n`;
    mensajeDetallado += `📋 ACTIVIDAD: ${document.getElementById('widget-actividad-resico').options[document.getElementById('widget-actividad-resico').selectedIndex].text}\n\n`;
    
    mensajeDetallado += `🧮 RESULTADOS:\n`;
    mensajeDetallado += `   • ISR RESICO Anual: $${isrAnual.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}\n`;
    mensajeDetallado += `   • ISR RESICO Mensual: $${isrMensual.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}\n`;
    mensajeDetallado += `   • ISR General Anual: $${isrGeneral.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}\n`;
    mensajeDetallado += `   • ISR General Mensual: $${isrGeneralMensual.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}\n\n`;
    
    if (ahorro > 0) {
        mensajeDetallado += `💚 BENEFICIO CON RESICO:\n`;
        mensajeDetallado += `   • Ahorro anual: $${ahorro.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}\n`;
        mensajeDetallado += `   • Ahorro mensual: $${(ahorro/12).toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}\n`;
        mensajeDetallado += `   • Porcentaje de ahorro: ${porcentajeAhorro}%\n`;
    } else if (ahorro < 0) {
        mensajeDetallado += `⚠️ CONSIDERACIÓN IMPORTANTE:\n`;
        mensajeDetallado += `   • Pagarías $${Math.abs(ahorro).toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})} más anual\n`;
        mensajeDetallado += `   • ${Math.abs(porcentajeAhorro)}% más que en Régimen General\n`;
        mensajeDetallado += `   • Evalúa cambiar de régimen\n`;
    } else {
        mensajeDetallado += `⚖️ AMBOS RÉGIMENES SON SIMILARES\n`;
    }
    
    mensajeDetallado += `\n══════════════════════════════\n`;
    mensajeDetallado += `ℹ️ TABLA RESICO 2024:\n`;
    mensajeDetallado += `   1.0%  → Hasta $25,000\n`;
    mensajeDetallado += `   1.1%  → Hasta $50,000\n`;
    mensajeDetallado += `   1.5%  → Hasta $83,333.33\n`;
    mensajeDetallado += `   2.0%  → Hasta $208,333.33\n`;
    mensajeDetallado += `   2.5%  → Hasta $3,500,000`;
    
    // Mostrar alerta con resumen
    mostrarAlertaWidget(mensajeDetallado, 'info');
}

// =============================================
// OTRAS FUNCIONES DE CÁLCULO
// =============================================
function calcularISRWidget() {
    const ingreso = parseFloat(document.getElementById('widget-ingreso-isr').value) || 0;
    const regimen = document.getElementById('widget-regimen-isr').value;
    
    if (ingreso <= 0) {
        mostrarAlertaWidget('Por favor ingresa un ingreso válido mayor a cero.', 'error');
        return;
    }
    
    let isrAnual = 0;
    
    if (regimen === 'general') {
        // Usar la función de cálculo general
        isrAnual = calcularISRGeneral(ingreso);
    } else {
        // Para RESICO, usar los tramos RESICO
        const tramosRESICO = [
            { limite: 25000, tasa: 0.010 },
            { limite: 50000, tasa: 0.011 },
            { limite: 83333.33, tasa: 0.015 },
            { limite: 208333.33, tasa: 0.020 },
            { limite: 3500000, tasa: 0.025 }
        ];
        
        let ingresoRestante = ingreso;
        
        for (let i = 0; i < tramosRESICO.length; i++) {
            const tramo = tramosRESICO[i];
            const tramoAnterior = i > 0 ? tramosRESICO[i-1] : { limite: 0, tasa: 0 };
            
            if (ingreso > tramoAnterior.limite) {
                const baseTramo = Math.min(ingresoRestante, tramo.limite - tramoAnterior.limite);
                
                if (baseTramo > 0) {
                    isrAnual += baseTramo * tramo.tasa;
                }
                
                ingresoRestante -= baseTramo;
            }
            
            if (ingresoRestante <= 0) break;
        }
    }
    
    const isrMensual = isrAnual / 12;
    
    // Mostrar resultados
    document.getElementById('widget-isr-anual').textContent = formatearDinero(isrAnual);
    document.getElementById('widget-isr-mensual').textContent = formatearDinero(isrMensual);
    
    // Efecto visual
    animarResultadoWidget('widget-isr');
    
    // Información adicional
    const tasaEfectiva = ingreso > 0 ? ((isrAnual / ingreso) * 100).toFixed(2) : '0.00';
    mostrarAlertaWidget(`📊 ISR calculado para ${regimen === 'general' ? 'Régimen General' : 'RESICO'}\n💰 Tasa efectiva: ${tasaEfectiva}%`, 'info');
}

function calcularIVAWidget() {
    const ivaVentas = parseFloat(document.getElementById('widget-iva-ventas').value) || 0;
    const ivaCompras = parseFloat(document.getElementById('widget-iva-compras').value) || 0;
    const tasaIVA = parseFloat(document.getElementById('widget-tasa-iva').value);
    
    if (ivaVentas < 0 || ivaCompras < 0) {
        mostrarAlertaWidget('Por favor ingresa valores positivos.', 'error');
        return;
    }
    
    // Calcular IVA por pagar o a favor
    let ivaPagar = 0;
    let ivaFavor = 0;
    
    if (ivaVentas > ivaCompras) {
        ivaPagar = ivaVentas - ivaCompras;
    } else {
        ivaFavor = ivaCompras - ivaVentas;
    }
    
    // Mostrar resultados
    document.getElementById('widget-iva-pagar').textContent = formatearDinero(ivaPagar);
    document.getElementById('widget-iva-favor').textContent = formatearDinero(ivaFavor);
    
    // Efecto visual
    animarResultadoWidget('widget-iva');
    
    // Información contextual
    let mensaje = `📊 Cálculo de IVA (${(tasaIVA * 100)}%)\n`;
    if (ivaPagar > 0) {
        mensaje += `💰 IVA por Pagar: ${formatearDinero(ivaPagar)}`;
    } else if (ivaFavor > 0) {
        mensaje += `💚 IVA a Favor: ${formatearDinero(ivaFavor)}`;
    } else {
        mensaje += `⚖️ IVA en equilibrio`;
    }
    
    mostrarAlertaWidget(mensaje, 'info');
}

function calcularNominaWidget() {
    const sueldoBruto = parseFloat(document.getElementById('widget-sueldo-bruto').value) || 0;
    const tipoContrato = document.getElementById('widget-tipo-contrato').value;
    
    if (sueldoBruto <= 0) {
        mostrarAlertaWidget('Por favor ingresa un sueldo bruto válido.', 'error');
        return;
    }
    
    // Cálculo de ISR (tarifas 2024 simplificadas)
    let isrRetenido = 0;
    
    if (sueldoBruto <= 10293.36) {
        isrRetenido = 0;
    } else if (sueldoBruto <= 20786.37) {
        isrRetenido = (sueldoBruto - 10293.36) * 0.15;
    } else if (sueldoBruto <= 36586.52) {
        isrRetenido = 1573.95 + (sueldoBruto - 20786.37) * 0.20;
    } else if (sueldoBruto <= 51437.58) {
        isrRetenido = 4728.27 + (sueldoBruto - 36586.52) * 0.25;
    } else {
        isrRetenido = 8429.02 + (sueldoBruto - 51437.58) * 0.30;
    }
    
    // Ajustes por tipo de contrato
    let imss = sueldoBruto * 0.025;
    let otrosDeducciones = sueldoBruto * 0.01;
    
    if (tipoContrato === 'honorarios') {
        imss = 0;
        otrosDeducciones = 0;
        isrRetenido = sueldoBruto * 0.10;
    }
    
    const totalDeducido = isrRetenido + imss + otrosDeducciones;
    const sueldoNeto = sueldoBruto - totalDeducido;
    
    // Mostrar resultados
    document.getElementById('widget-sueldo-neto').textContent = formatearDinero(sueldoNeto);
    document.getElementById('widget-isr-retenido').textContent = formatearDinero(isrRetenido);
    
    // Efecto visual
    animarResultadoWidget('widget-nomina');
    
    // Información detallada
    const porcentajeDeduccion = ((totalDeducido / sueldoBruto) * 100).toFixed(1);
    let mensaje = `💰 Nómina ${tipoContrato === 'honorarios' ? 'Honorarios' : 'Sueldo Base'}\n`;
    mensaje += `📊 Bruto: ${formatearDinero(sueldoBruto)}\n`;
    mensaje += `💸 Neto: ${formatearDinero(sueldoNeto)}\n`;
    mensaje += `📉 Deducciones: ${porcentajeDeduccion}%`;
    
    mostrarAlertaWidget(mensaje, 'info');
}

function calcularUMAWidget() {
    const valor = parseFloat(document.getElementById('widget-valor-uma').value) || 0;
    const conversion = document.getElementById('widget-conversion-uma').value;
    const UMA_DIARIO = 108.57;
    const SALARIO_MINIMO = 207.44;
    const SALARIO_MINIMO_FRONTERA = 248.93;
    
    if (valor < 0) {
        mostrarAlertaWidget('Por favor ingresa un valor positivo.', 'error');
        return;
    }
    
    let resultado = 0;
    let texto = "";
    let titulo = "";
    
    switch(conversion) {
        case 'uma-a-pesos':
            resultado = valor * UMA_DIARIO;
            texto = formatearDinero(resultado);
            titulo = `${valor} UMA = ${formatearDinero(resultado)}`;
            break;
        case 'pesos-a-uma':
            resultado = valor / UMA_DIARIO;
            texto = `${resultado.toFixed(2)} UMA`;
            titulo = `${formatearDinero(valor)} = ${resultado.toFixed(2)} UMA`;
            break;
        case 'salario-minimo':
            resultado = valor / SALARIO_MINIMO;
            const resultadoFrontera = valor / SALARIO_MINIMO_FRONTERA;
            texto = `${resultado.toFixed(2)} SMG / ${resultadoFrontera.toFixed(2)} SMF`;
            titulo = `${formatearDinero(valor)} = ${resultado.toFixed(2)} SMG`;
            break;
    }
    
    // Mostrar resultados
    document.getElementById('widget-resultado-uma').textContent = texto;
    
    // Efecto visual
    animarResultadoWidget('widget-uma');
    
    // Información adicional
    let mensaje = `📊 Conversión UMA/Salarios\n`;
    mensaje += `🔢 ${titulo}\n`;
    
    if (conversion === 'uma-a-pesos') {
        const mensual = valor * (UMA_DIARIO * 30.4);
        const anual = valor * (UMA_DIARIO * 365);
        mensaje += `📅 Mensual: ${formatearDinero(mensual)}\n`;
        mensaje += `📅 Anual: ${formatearDinero(anual)}`;
    }
    
    mostrarAlertaWidget(mensaje, 'info');
}

// =============================================
// WIDGET FLOTANTE DE CALCULADORA
// =============================================
function inicializarWidgetCalculadora() {
    console.log('🔄 Inicializando calculadora...');
    
    const widget = document.getElementById('calculadora-widget');
    const toggleBtn = document.getElementById('widget-toggle');
    const closeBtn = document.querySelector('.widget-close');
    const widgetOptions = document.querySelectorAll('.widget-option');
    
    // Verificar que los elementos existan
    if (!widget || !toggleBtn) {
        console.error('❌ No se encontraron elementos del widget');
        return;
    }
    
    console.log('✅ Elementos encontrados correctamente');
    
    // Abrir/cerrar widget
    toggleBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        widget.classList.toggle('visible');
        toggleBtn.style.opacity = widget.classList.contains('visible') ? '0.5' : '1';
        console.log('🔘 Widget visible:', widget.classList.contains('visible'));
    });
    
    if (closeBtn) {
        closeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            widget.classList.remove('visible');
            toggleBtn.style.opacity = '1';
        });
    }
    
    // Cambiar entre opciones del widget
    widgetOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.stopPropagation();
            const widgetType = this.getAttribute('data-widget');
            
            console.log('📱 Cambiando a widget:', widgetType);
            
            // Actualizar botones activos
            widgetOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            
            // Mostrar contenido correspondiente
            document.querySelectorAll('.widget-calc').forEach(content => {
                content.classList.remove('active');
                content.style.display = 'none';
            });
            
            const targetContent = document.getElementById('widget-' + widgetType);
            if (targetContent) {
                targetContent.style.display = 'block';
                setTimeout(() => {
                    targetContent.classList.add('active');
                }, 10);
            }
            
            // Configurar dinámicamente la calculadora de RESICO
            if (widgetType === 'resico') {
                configurarCalculadoraRESICO();
            }
        });
    });
    
    // Configurar la calculadora de RESICO inicialmente
    configurarCalculadoraRESICO();
    
    // Eventos para botones de calcular en el widget
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('widget-btn-calcular')) {
            event.preventDefault();
            event.stopPropagation();
            
            const type = event.target.getAttribute('data-type');
            console.log('🧮 Calculando:', type);
            
            switch(type) {
                case 'resico':
                    calcularRESICOWidget();
                    break;
                case 'isr':
                    calcularISRWidget();
                    break;
                case 'iva':
                    calcularIVAWidget();
                    break;
                case 'nomina':
                    calcularNominaWidget();
                    break;
                case 'uma':
                    calcularUMAWidget();
                    break;
            }
        }
    });
    
    // Cerrar widget al hacer clic fuera
    document.addEventListener('click', function(event) {
        if (widget.classList.contains('visible') && 
            !widget.contains(event.target) && 
            !toggleBtn.contains(event.target)) {
            widget.classList.remove('visible');
            toggleBtn.style.opacity = '1';
        }
    });
    
    // Configurar inputs del widget
    const widgetInputs = document.querySelectorAll('.widget-input input[type="number"]');
    widgetInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && this.value.trim() !== '') {
                const num = parseFloat(this.value);
                if (!isNaN(num)) {
                    this.value = num.toFixed(2);
                }
            }
        });
        
        input.addEventListener('focus', function() {
            this.select();
        });
    });
    
    console.log('✅ Widget inicializado correctamente');
}

// =============================================
// INICIALIZAR TODO CUANDO EL DOM ESTÉ LISTO
// =============================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('📄 DOM cargado, inicializando calculadora...');
    
    // 1. Inicializar estilos de animación
    inicializarEstilosAnimacion();
    
    // 2. Inicializar widget de calculadora
    inicializarWidgetCalculadora();
    
    console.log('🚀 Calculadora MD Consultoría lista para usar');
});