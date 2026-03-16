<?php
// =============================================
// CONFIGURACIÓN PRINCIPAL - MD CONSULTORÍA
// =============================================

// Información de la empresa
$empresa_nombre = "MD Consultoría Empresarial";
$empresa_slogan = "Expertos en Consultoría Contable y Empresarial";
$empresa_descripcion = "En MD Consultoría Empresarial contamos con profesionales capacitados para poder solucionar tus problemas, contáctanos, podemos ayudarte.";
$anio_fundacion = "2015";

$logo_url = "img/md_consultoria.png";

// Datos de contacto
$telefono_whatsapp = "+525560744398";
$email_principal = "contacto@mdconsultoria.mx";
$email_info = "contacto@mdconsultoria.mx";
$direccion = "128 Avenida Colonia del Valle, Ciudad de México, Cd. de México";
$horario_atencion = "Lun-Vie: 9:00 AM - 6:00 PM";

// Redes sociales
$redes_sociales = [
    'facebook' => 'https://www.facebook.com/mdconsultoriamx/',
    'twitter' => 'https://x.com/mdconsultoriamx',
    'instagram' => 'https://instagram.com/mdconsultoria'
];

// Servicios ofrecidos
$servicios = [
     [
        'imagen' => 'img1.jpg', // Esto generará: img/img1.jpg
        'titulo' => 'Contabilidad General',
        'descripcion' => 'Gestión completa de registros contables, estados financieros y cumplimiento normativo para tu empresa.',
        'destacado' => true
    ],
    [
        'imagen' => 'img2.jpg',
        'titulo' => 'Asesoría Fiscal',
        'descripcion' => 'Optimización fiscal, declaraciones de impuestos y planificación tributaria estratégica.',
        'destacado' => true
    ],
    [
        'imagen' => 'img3.jpg',
        'titulo' => 'Consultoría Financiera',
        'descripcion' => 'Análisis financiero, presupuestos y estrategias para maximizar la rentabilidad de tu negocio.',
        'destacado' => true
    ],
    [
        'imagen' => 'img4.jpg',
        'titulo' => 'Auditoría Externa',
        'descripcion' => 'Revisión independiente de estados financieros y procesos contables internos.',
        'destacado' => false
    ],
    [
        'imagen' => 'foto1.png',
        'titulo' => 'Cumplimiento Legal',
        'descripcion' => 'Asesoría en obligaciones legales corporativas y gobierno empresarial.',
        'destacado' => false
    ],
    [
        'imagen' => 'img5.jpg',
        'titulo' => 'Outsourcing Contable',
        'descripcion' => 'Externaliza tu departamento contable con nuestro equipo especializado.',
        'destacado' => false
    ]
];

// Ventajas competitivas
$ventajas = [
    [
        'imagen' => 'img/foto2.png', // Cambia por tu ruta de imagen
        'titulo' => 'Respuesta Inmediata',
        'descripcion' => 'Atendemos tus consultas en menos de 24 horas con soluciones efectivas.'
    ],
    [
        'imagen' => 'img/foto4.jpg', // Cambia por tu ruta de imagen
        'titulo' => 'Enfoque Personalizado',
        'descripcion' => 'Adaptamos nuestros servicios a las necesidades específicas de tu industria.'
    ],
    [
        'imagen' => 'img/foto6.jpeg', // Cambia por tu ruta de imagen
        'titulo' => '10+ Años de Experiencia',
        'descripcion' => 'Más de una década asesorando empresas de diversos sectores en México.'
    ],
    [
        'imagen' => 'img/img5.jpg', // Cambia por tu ruta de imagen
        'titulo' => 'Contadores Certificados',
        'descripcion' => 'Equipo de CPAs y especialistas fiscales con certificaciones vigentes.'
    ]
];

// Estadísticas de la empresa
$estadisticas = [
    'clientes_activos' => 250,
    'proyectos_completados' => 1500,
    'experiencia_anios' => 10,
    'especialistas' => 15
];

// Función para obtener el año actual
function obtener_anio_actual() {
    return date("Y");
}

// Función para formatear números
function formatear_numero($numero) {
    return number_format($numero, 0, ',', ',');
}

// Procesar formulario de contacto (simulación)
$mensaje_contacto = "";
$nombre_enviado = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contacto'])) {
    $nombre = htmlspecialchars($_POST['nombre']);
    $email = htmlspecialchars($_POST['email']);
    $empresa = htmlspecialchars($_POST['empresa']);
    $mensaje = htmlspecialchars($_POST['mensaje']);
    
    // Guardar nombre para la alerta
    $nombre_enviado = $nombre;
    
    // Simulación de envío de email
    $mensaje_contacto = "¡Gracias $nombre! Hemos recibido tu mensaje y nos pondremos en contacto contigo en menos de 24 horas.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $empresa_nombre; ?> - <?php echo $empresa_slogan; ?></title>
    <meta name="description" content="Consultoría contable y empresarial especializada en soluciones financieras integrales para empresas en México.">
    <meta name="keywords" content="contabilidad, consultoría, asesoría fiscal, auditoría, finanzas, México, RESICO, ISR, IVA, nómina">
    
    <!-- Fuente Inter para diseño profesional -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Animate.css para animaciones adicionales -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- AOS para animaciones al scroll -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Incluir archivos CSS -->
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/estilos.css">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    
    <!-- Open Graph para redes sociales -->
    <meta property="og:title" content="<?php echo $empresa_nombre; ?>">
    <meta property="og:description" content="<?php echo $empresa_descripcion; ?>">
    <meta property="og:image" content="img/og-image.jpg">
    <meta property="og:url" content="https://mdconsultoria.com">
    <meta property="og:type" content="website">
    
    <style>
       

        /* =============================================
           CARRUSEL HERO - MD CONSULTORÍA
           ============================================= */
        .hero-carousel {
            position: relative;
            width: 100%;
            height: 100vh;
            min-height: 700px;
            overflow: hidden;
            margin-top: 0;
        }

        .carousel-container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .carousel-slides {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .carousel-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.8s ease-in-out, visibility 0.8s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .carousel-slide.active {
            opacity: 1;
            visibility: visible;
        }

        .carousel-slide::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.75) 100%);
        }

        .carousel-slide::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradiente-accento, linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%));
            opacity: 0.8;
        }

        .carousel-content {
            position: relative;
            z-index: 2;
            max-width: 900px;
            padding: 0 20px;
            animation: fadeInUp 1s ease-out;
        }

        .carousel-content h1 {
            font-size: 4.2rem;
            margin-bottom: 1.5rem;
            line-height: 1.1;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
            color: white;
            background: none;
            -webkit-text-fill-color: white;
        }

        .carousel-content p {
            font-size: 1.4rem;
            margin: 0 auto 3rem;
            color: rgba(255, 255, 255, 0.95);
            max-width: 700px;
            line-height: 1.6;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
        }

        .carousel-controls {
            position: absolute;
            bottom: 40px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            z-index: 10;
        }

        .carousel-dots {
            display: flex;
            gap: 12px;
        }

        .carousel-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 0;
        }

        .carousel-dot.active {
            background: #2563eb;
            transform: scale(1.2);
            box-shadow: 0 0 15px #2563eb;
        }

        .carousel-dot:hover {
            background: rgba(255, 255, 255, 0.8);
        }

        .carousel-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: white;
            font-size: 1.5rem;
            z-index: 20;
        }

        .carousel-arrow:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border-color: transparent;
            transform: translateY(-50%) scale(1.1);
        }

        .carousel-arrow.prev {
            left: 30px;
        }

        .carousel-arrow.next {
            right: 30px;
        }

        .carousel-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 4px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            width: 0%;
            transition: width 0.3s linear;
            z-index: 30;
        }

        /* =============================================
           CORRECCIÓN SELECT CALCULADORA - FONDO NEGRO
           ============================================= */
        .widget-select,
        .calculadora-widget select,
        #widget-tipo-resico,
        #widget-actividad-resico,
        #widget-regimen-isr,
        #widget-tasa-iva,
        #widget-tipo-contrato,
        #widget-conversion-uma {
            background-color: #000000 !important;
            color: white !important;
        }

        .widget-select option,
        .calculadora-widget select option,
        #widget-tipo-resico option,
        #widget-actividad-resico option,
        #widget-regimen-isr option,
        #widget-tasa-iva option,
        #widget-tipo-contrato option,
        #widget-conversion-uma option {
            background-color: #000000 !important;
            color: white !important;
            padding: 10px;
        }

        .widget-select option:checked,
        .calculadora-widget select option:checked,
        #widget-tipo-resico option:checked,
        #widget-actividad-resico option:checked,
        #widget-regimen-isr option:checked,
        #widget-tasa-iva option:checked,
        #widget-tipo-contrato option:checked,
        #widget-conversion-uma option:checked {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
            color: white !important;
        }

        /* =============================================
           ANIMACIÓN MENSAJE DE CONTACTO
           ============================================= */
        .mensaje-exito {
            animation: slideIn 0.3s ease;
            transition: all 0.3s ease;
        }

        .mensaje-oculto {
            opacity: 0;
            transform: translateY(-10px);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive para carrusel */
        @media (max-width: 1200px) {
            .carousel-content h1 {
                font-size: 3.8rem;
            }
            .carousel-content p {
                font-size: 1.3rem;
            }
        }

        @media (max-width: 992px) {
            .hero-carousel {
                min-height: 600px;
            }
            .carousel-content h1 {
                font-size: 3.2rem;
            }
            .carousel-content p {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 768px) {
            .hero-carousel {
                min-height: 550px;
            }
            .carousel-content h1 {
                font-size: 2.8rem;
            }
            .carousel-content p {
                font-size: 1.1rem;
            }
            .carousel-arrow {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }
            .carousel-arrow.prev {
                left: 15px;
            }
            .carousel-arrow.next {
                right: 15px;
            }
        }

        @media (max-width: 576px) {
            .hero-carousel {
                min-height: 500px;
            }
            .carousel-content h1 {
                font-size: 2.2rem;
            }
            .carousel-content p {
                font-size: 1rem;
            }
            .carousel-dot {
                width: 10px;
                height: 10px;
            }
        }
        /* Estilos para el menú desplegable vertical */
.menu-calculadoras {
    position: relative;
}

.calculadoras-trigger {
    display: flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
}

.dropdown-icon {
    font-size: 12px;
    transition: transform 0.3s ease;
}

/* Animación del icono */
.menu-calculadoras:hover .dropdown-icon,
.menu-calculadoras.active .dropdown-icon {
    transform: rotate(180deg);
}

/* Submenú vertical - ESTO ES LO IMPORTANTE */
.submenu-calculadoras {
    position: absolute;
    top: 100%;
    left: 0;
    background: white;
    min-width: 250px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    border-radius: 10px;
    padding: 8px 0;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: all 0.3s ease;
    z-index: 1000;
    list-style: none;
    margin: 0;
    display: block; /* Asegura que sea bloque/vertical */
}

/* Cada item del submenú en VERTICAL */
.submenu-calculadoras li {
    display: block; /* Fuerza a ser vertical */
    width: 100%;
    margin: 0;
    border-bottom: 1px solid #f0f0f0;
}

.submenu-calculadoras li:last-child {
    border-bottom: none;
}

/* Links del submenú en VERTICAL */
.submenu-calculadoras a {
    display: block; /* Ocupa todo el ancho */
    width: 100%;
    padding: 12px 20px;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.95rem;
    white-space: nowrap;
    box-sizing: border-box;
}

.submenu-calculadoras a:hover {
    background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);
    color: #007bff;
    padding-left: 25px;
}

/* Mostrar submenú en hover */
.menu-calculadoras:hover .submenu-calculadoras {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

/* Mostrar submenú en click */
.menu-calculadoras.active .submenu-calculadoras {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

/* Responsive para móviles */
@media (max-width: 768px) {
    .submenu-calculadoras {
        position: static;
        box-shadow: none;
        border: 1px solid #eee;
        margin-top: 5px;
        display: none;
        opacity: 1;
        visibility: visible;
        transform: none;
        width: 100%;
    }
    
    .menu-calculadoras.active .submenu-calculadoras {
        display: block;
    }
    
    .submenu-calculadoras a {
        white-space: normal; /* Permite texto multilínea en móviles */
    }
}
/* Sección de calculadoras */
.calculadoras-seccion {
    padding: 80px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.calculadoras-seccion .section-title h2,
.calculadoras-seccion .section-title p {
    color: white;
}

/* Tabs de calculadoras */
.calculadoras-tabs {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
    margin-bottom: 40px;
}

.tab-btn {
    padding: 10px 20px;
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid transparent;
    border-radius: 30px;
    color: white;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.tab-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
}

.tab-btn.active {
    background: white;
    color: #667eea;
    border-color: white;
}

/* Contenedor de calculadoras */
.calculadoras-contenedor {
    position: relative;
    min-height: 600px;
}

.calculadora-item {
    display: none;
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    animation: fadeIn 0.5s ease;
}

.calculadora-item.active {
    display: block;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.calculadora-nombre {
    color: #333;
    font-size: 2rem;
    margin-bottom: 30px;
    text-align: center;
    font-weight: 600;
}

/* Estilos del formulario dentro de calculadoras */
.calculadora-form {
    max-width: 500px;
    margin: 0 auto;
}

/* Resultados dentro de calculadoras */
.resultados-calculadora {
    margin-top: 40px;
    padding: 30px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
}

.resultados-calculadora h4 {
    color: #333;
    font-size: 1.3rem;
    margin-bottom: 20px;
    text-align: center;
}

.tabla-container {
    overflow-x: auto;
    margin-bottom: 20px;
}

.tabla-resultados {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 10px;
    overflow: hidden;
}

.tabla-resultados tr {
    border-bottom: 1px solid #f0f0f0;
}

.tabla-resultados tr:last-child {
    border-bottom: none;
}

.tabla-resultados td {
    padding: 12px 20px;
}

.tabla-resultados td:first-child {
    font-weight: 500;
    color: #555;
}

.tabla-resultados td:last-child {
    text-align: right;
    font-weight: 600;
    color: #333;
}

.tabla-resultados .resultado-final {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.tabla-resultados .resultado-final td {
    color: white;
}

.percepcion-final {
    text-align: center;
    padding: 15px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.percepcion-final p {
    margin: 0;
    font-size: 1.2rem;
    color: #333;
}

.percepcion-final strong {
    color: #667eea;
    font-size: 1.4rem;
}

/* Responsive */
@media (max-width: 768px) {
    .calculadoras-tabs {
        gap: 5px;
    }
    
    .tab-btn {
        padding: 8px 15px;
        font-size: 0.8rem;
    }
    
    .calculadora-item {
        padding: 20px;
    }
    
    .calculadora-nombre {
        font-size: 1.5rem;
    }
}

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
    
</head>
<body>
    <!-- Header -->
<header id="header" data-aos="fade-down" data-aos-duration="800">
    <div class="container header-container">
        <div class="logo-izquierdo">
            <img src="<?php echo $logo_url; ?>" alt="<?php echo $empresa_nombre; ?>" class="logo-imagen">
        </div>
        
        <nav>
            <ul>
                <li><a href="#inicio">Inicio</a></li>
                <li><a href="#servicios">Servicios</a></li>
                <li class="menu-calculadoras">
                    <a href="javascript:void(0);" class="calculadoras-trigger">
                        Calculadoras <span class="dropdown-icon">▼</span>
                    </a>
                    <ul class="submenu-calculadoras">
                        <li><a href="#calculadora-isr" class="calc-link" data-calc="isr"> ISR</a></li>
                        <li><a href="#calculadora-resico" class="calc-link" data-calc="resico"> RESICO</a></li>
                        <li><a href="#calculadora-interes" class="calc-link" data-calc="interes"> Interés Compuesto</a></li>
                        <li><a href="#calculadora-honorarios" class="calc-link" data-calc="honorarios"> Honorarios</a></li>
                        <li><a href="#calculadora-finquito" class="calc-link" data-calc="finiquito"> Finiquito</a></li>
                        <li><a href="#calculadora-arrendamiento" class="calc-link" data-calc="arrendamiento"> Arrendamiento</a></li>
                        <li><a href="#calculadora-imss" class="calc-link" data-calc="imss"> IMSS</a></li>
                        <li><a href="#calculadora-recargos" class="calc-link" data-calc="recargos"> Recargos</a></li>
                        <li><a href="#calculadora-indemnizacion" class="calc-link" data-calc="indemnizacion"> Indemnización</a></li>
                    </ul>
                </li>
                <li><a href="#nosotros">Nosotros</a></li>
                <li><a href="#contacto">Contacto</a></li>
            </ul>
        </nav>
        <a href="#contacto" class="btn-contacto">Solicitar Consulta</a>
    </div>
</header>
    <!-- ============================================= -->
    <!-- HERO CARRUSEL - MD CONSULTORÍA -->
    <!-- ============================================= -->
    <section class="hero-carousel" id="inicio">
        <div class="carousel-slide active" style="background-image: url('img/img.jpg');">
    <div class="carousel-content">
        <h1 class="animate__animated animate__fadeInUp">Expertos en Consultoría Contable</h1>
        <p class="animate__animated animate__fadeInUp animate__delay-1s">Más de 10 años optimizando la gestión financiera de empresas como la tuya</p>
        <div class="hero-buttons animate__animated animate__fadeInUp animate__delay-2s">
            <a href="#servicios" class="btn-hero">Nuestros Servicios</a>
            <a href="#contacto" class="btn-hero btn-hero-outline">Consulta Gratuita</a>
        </div>
    </div>
</div>
                
                <!-- Slide 2 - Asesoría Fiscal -->
                <div class="carousel-slide" style="background-image: url('img/img1.jpg');">
                    <div class="carousel-content">
                        <h1 class="animate__animated animate__fadeInUp">Asesoría Fiscal Estratégica</h1>
                        <p class="animate__animated animate__fadeInUp animate__delay-1s">Optimizamos tu carga fiscal y cumplimos con todas tus obligaciones</p>
                        <div class="hero-buttons animate__animated animate__fadeInUp animate__delay-2s">
                            <a href="#servicios" class="btn-hero">Conocer Más</a>
                            <a href="#contacto" class="btn-hero btn-hero-outline">Agendar Cita</a>
                        </div>
                    </div>
                </div>
                
                <!-- Slide 3 - Auditoría -->
                <div class="carousel-slide" style="background-image:  url('img/img2.jpg');">
                    <div class="carousel-content">
                        <h1 class="animate__animated animate__fadeInUp">Auditoría y Cumplimiento</h1>
                        <p class="animate__animated animate__fadeInUp animate__delay-1s">Garantizamos la transparencia y solidez financiera de tu empresa</p>
                        <div class="hero-buttons animate__animated animate__fadeInUp animate__delay-2s">
                            <a href="#servicios" class="btn-hero">Ver Servicios</a>
                            <a href="#contacto" class="btn-hero btn-hero-outline">Contactar</a>
                        </div>
                    </div>
                </div>
                
                <!-- Slide 4 - Outsourcing -->
                <div class="carousel-slide" style="background-image: url('img/img3.jpg');">
                    <div class="carousel-content">
                        <h1 class="animate__animated animate__fadeInUp">Outsourcing Contable</h1>
                        <p class="animate__animated animate__fadeInUp animate__delay-1s">Externaliza tu departamento contable con expertos certificados</p>
                        <div class="hero-buttons animate__animated animate__fadeInUp animate__delay-2s">
                            <a href="#servicios" class="btn-hero">Descubrir</a>
                            <a href="#contacto" class="btn-hero btn-hero-outline">Solicitar Info</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Flechas de navegación -->
            <button class="carousel-arrow prev">&#10094;</button>
            <button class="carousel-arrow next">&#10095;</button>
            
            <!-- Indicadores (dots) -->
            <div class="carousel-controls">
                <div class="carousel-dots">
                    <span class="carousel-dot active" data-slide="0"></span>
                    <span class="carousel-dot" data-slide="1"></span>
                    <span class="carousel-dot" data-slide="2"></span>
                    <span class="carousel-dot" data-slide="3"></span>
                </div>
            </div>
            
            <!-- Barra de progreso -->
            <div class="carousel-progress"></div>
        </div>
    </section>

    <!-- Estadísticas -->
    <section class="estadisticas" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
        <div class="container">
            <div class="estadisticas-grid">
                <div class="estadistica-item reveal">
                    <div class="estadistica-numero" data-count="<?php echo $estadisticas['clientes_activos']; ?>">+1</div>
                    <div class="estadistica-texto">Clientes Activos</div>
                </div>
                <div class="estadistica-item reveal">
                    <div class="estadistica-numero" data-count="<?php echo $estadisticas['proyectos_completados']; ?>">+1</div>
                    <div class="estadistica-texto">Proyectos Completados</div>
                </div>
                <div class="estadistica-item reveal">
                    <div class="estadistica-numero" data-count="<?php echo $estadisticas['experiencia_anios']; ?>">1</div>
                    <div class="estadistica-texto">Años de Experiencia</div>
                </div>
                <div class="estadistica-item reveal">
                    <div class="estadistica-numero" data-count="<?php echo $estadisticas['especialistas']; ?>">1</div>
                    <div class="estadistica-texto">Especialistas</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Servicios -->
<section class="servicios" id="servicios">
    <div class="container">
        <div class="section-title" data-aos="fade-up" data-aos-duration="1000">
            <h2>Nuestros Servicios</h2>
            <p>Ofrecemos una amplia gama de servicios contables y financieros adaptados a las necesidades específicas de tu empresa</p>
        </div>
        <div class="servicios-grid">
            <?php foreach($servicios as $index => $servicio): ?>
            <div class="servicio-card <?php echo $servicio['destacado'] ? 'destacado' : ''; ?> reveal" 
                 data-aos="fade-up" 
                 data-aos-duration="800" 
                 data-aos-delay="<?php echo $index * 100; ?>">
                <div class="servicio-bg" style="background-image: url('img/<?php echo $servicio['imagen']; ?>');"></div>
                <div class="servicio-overlay"></div>
                <div class="servicio-content">
                    <h3><?php echo $servicio['titulo']; ?></h3>
                    <p><?php echo $servicio['descripcion']; ?></p>
                    <a href="#contacto" class="servicio-link">Consultar servicio →</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<!-- Sección Calculadoras -->
<section class="calculadoras-seccion" id="calculadoras">
    <div class="container">
        <div class="section-title" data-aos="fade-up" data-aos-duration="1000">
            <h2>Calculadoras</h2>
            <p>Herramientas prácticas para cálculos fiscales, laborales y financieros</p>
        </div>

        <!-- Tabs de calculadoras -->
        <div class="calculadoras-tabs" data-aos="fade-up" data-aos-duration="800">
            <button class="tab-btn" data-calc="isr">ISR</button>
            <button class="tab-btn" data-calc="resico">RESICO</button>
            <button class="tab-btn" data-calc="interes">Interés Compuesto</button>
            <button class="tab-btn" data-calc="honorarios">Honorarios</button>
            <button class="tab-btn" data-calc="finiquito">Finiquito</button>
            <button class="tab-btn" data-calc="arrendamiento">Arrendamiento</button>
            <button class="tab-btn" data-calc="imss">IMSS</button>
            <button class="tab-btn" data-calc="recargos">Recargos</button>
            <button class="tab-btn" data-calc="indemnizacion">Indemnización</button>
        </div>

        <!-- Contenedor de calculadoras -->
        <div class="calculadoras-contenedor">
            <?php include 'caluladoras/calculadora-isr.php'; ?>
            <?php include 'caluladoras/calculadora-resico.php'; ?>
            <?php include 'caluladoras/calculadora-interes.php'; ?>
            <?php include 'caluladoras/calculadora-honorarios.php'; ?>
             <?php include 'caluladoras/calculadora-finiquitos.php'; ?>
             <?php include 'caluladoras/calculadora-arrendamiento.php'; ?>
             <?php include 'caluladoras/calculadora-recargos.php'; ?>
             <?php include 'caluladoras/calculadora-indemnizacion.php'; ?>
        </div> 
</section>
    <!-- Por qué elegirnos -->
   <section class="porque-elegirnos" id="nosotros">
    <div class="container">
        <div class="section-title" data-aos="fade-up" data-aos-duration="1000">
            <h2>Nosotros</h2>
            <p>Somos una empresa fundada por contadores, con más de 15 años de experiencia en materia contable, fiscal y de finanzas.</p>
        </div>
        <div class="elegirnos-grid">
            <?php foreach($ventajas as $index => $ventaja): ?>
            <div class="elegirnos-item" 
                 style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('<?php echo $ventaja['imagen']; ?>');"
                 data-aos="fade-up" 
                 data-aos-duration="800" 
                 data-aos-delay="<?php echo $index * 200; ?>">
                <div class="elegirnos-content">
                    <h3><?php echo $ventaja['titulo']; ?></h3>
                    <p><?php echo $ventaja['descripcion']; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
    <!-- Contacto -->
 <section class="contacto" id="contacto">
        <div class="container">
            <div class="contacto-container" data-aos="fade-up" data-aos-duration="1000">
                <div class="contacto-info">
                    <h2>Contáctanos</h2>
                    <p>Estamos listos para ayudarte a optimizar la gestión financiera de tu empresa. Solicita una consulta gratuita hoy mismo.</p>
                    
                    <div class="contacto-datos">
                        
                        <!-- WhatsApp - Reemplaza al correo -->
                        <div class="contacto-dato whatsapp-dato" data-aos="fade-right" data-aos-delay="300">
                           
                            <div class="whatsapp-contenido">
                                <strong>Atención por WhatsApp</strong>
                                
                                <span class="whatsapp-horario"><?php echo $horario_atencion; ?></span>
                                <a href="https://wa.me/<?php echo $telefono_whatsapp; ?>?text=Hola%20MD%20Consultoría,%20me%20gustaría%20solicitar%20información" 
                                   class="whatsapp-btn" 
                                   target="_blank">
                                    <i class="fab fa-whatsapp" style="margin-right: 8px;"></i> Enviar mensaje
                                </a>
                            </div>
                        </div>
                        
                        <!-- Dirección -->
                        <div class="contacto-dato" data-aos="fade-right" data-aos-delay="400">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <strong>Dirección:</strong><br>
                                <?php echo $direccion; ?>
                            </div>
                        </div>
                        
                        <!-- Horario -->
                        <div class="contacto-dato" data-aos="fade-right" data-aos-delay="500">
                            <i class="far fa-clock"></i>
                            <div>
                                <strong>Horario:</strong><br>
                                <?php echo $horario_atencion; ?>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <div class="contacto-form" data-aos="fade-left" data-aos-delay="300">
                    <h2>Solicitar Consulta</h2>
                    <form method="POST" action="enviar_consulta.php" id="formulario-contacto">
                        <div class="form-group" data-aos="fade-up" data-aos-delay="100">
                            <input type="text" class="form-control" name="nombre" placeholder="Nombre completo" required>
                        </div>
                        <div class="form-group" data-aos="fade-up" data-aos-delay="150">
                            <input type="email" class="form-control" name="email" placeholder="Correo electrónico" required>
                        </div>
                        <div class="form-group" data-aos="fade-up" data-aos-delay="200">
                            <input type="text" class="form-control" name="empresa" placeholder="Empresa" required>
                        </div>
                        <div class="form-group" data-aos="fade-up" data-aos-delay="250">
                            <textarea class="form-control" name="mensaje" placeholder="¿En qué podemos ayudarte?" required></textarea>
                        </div>
                        <button type="submit" name="contacto" class="btn-enviar" data-aos="fade-up" data-aos-delay="300">Enviar Mensaje</button>
                        
                        <?php if (!empty($mensaje_contacto)): ?>
                        <div class="mensaje-exito" data-aos="fade-up">
                            <?php echo $mensaje_contacto; ?>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col" data-aos="fade-up" data-aos-delay="100">
                    <h3><?php echo $empresa_nombre; ?></h3>
                    <p>Somos un grupo de asesores especializados en materia fiscal, contable, administrativa y de negocios.</p>
                    <div class="redes-sociales">
                        <a href="<?php echo $redes_sociales['facebook']; ?>" class="red-social" target="_blank" data-aos="zoom-in" data-aos-delay="200">f</a>
                        <a href="<?php echo $redes_sociales['twitter']; ?>" class="red-social" target="_blank" data-aos="zoom-in" data-aos-delay="300">t</a>
                        <a href="<?php echo $redes_sociales['instagram']; ?>" class="red-social" target="_blank" data-aos="zoom-in" data-aos-delay="400">ig</a>
                    </div>
                </div>
                <div class="footer-col" data-aos="fade-up" data-aos-delay="200">
                    <h3>Servicios</h3>
                    <ul>
                        <?php foreach($servicios as $servicio): ?>
                        <li><a href="#servicios"><?php echo $servicio['titulo']; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="footer-col" data-aos="fade-up" data-aos-delay="300">
                    <h3>Enlaces Rápidos</h3>
                    <ul>
                        <li><a href="#inicio">Inicio</a></li>
                        <li><a href="#servicios">Servicios</a></li>
                        <li><a href="#nosotros">Nosotros</a></li>
                        <li><a href="#contacto">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-col" data-aos="fade-up" data-aos-delay="400">
                    <h3>Contacto</h3>
                    <ul>
                        <li><a href="mailto:<?php echo $email_principal; ?>"><i>✉️</i> <?php echo $email_principal; ?></a></li>
                        <li><a href="https://www.google.com/maps/@19.3863491,-99.1734951,3a,75y,182.06h,108.44t/data=!3m7!1e1!3m5!1s95HYNLB_7g2FWGZ8OdDMKQ!2e0!6shttps:%2F%2Fstreetviewpixels-pa.googleapis.com%2Fv1%2Fthumbnail%3Fcb_client%3Dmaps_sv.tactile%26w%3D900%26h%3D600%26pitch%3D-18.437698813029442%26panoid%3D95HYNLB_7g2FWGZ8OdDMKQ%26yaw%3D182.0618854309621!7i16384!8i8192?authuser=0&entry=ttu&g_ep=EgoyMDI2MDIwOC4wIKXMDSoASAFQAw%3D%3D"><i>📍</i> <?php echo $direccion; ?></a></li>
                        <li><a href="https://wa.me/<?php echo $telefono_whatsapp; ?>"><i>💬</i> WhatsApp</a></li>
                    </ul>
                </div>
            </div>
            <div class="copyright" data-aos="fade-up" data-aos-delay="500">
                <p>&copy; <?php echo $anio_fundacion; ?> - <?php echo obtener_anio_actual(); ?> <?php echo $empresa_nombre; ?>. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- ============================================= -->
    <!-- WIDGET FLOTANTE DE CALCULADORA CONTABLE -->
    <!-- ============================================= -->
    
    <div id="calculadora-widget" class="calculadora-widget">
        <div class="widget-header">
            <h4>💰 Calculadora Contable</h4>
            <button class="widget-close">&times;</button>
        </div>
        
        <div class="widget-content">
            <!-- Menú de opciones de cálculo -->
            <div class="widget-menu">
                <button class="widget-option active" data-widget="resico">RESICO</button>
                <button class="widget-option" data-widget="isr">ISR</button>
                <button class="widget-option" data-widget="iva">IVA</button>
                <button class="widget-option" data-widget="nomina">Nómina</button>
                <button class="widget-option" data-widget="uma">UMA</button>
            </div>
            
            <div class="widget-calc-content">
                <!-- Calculadora de RESICO -->
                <div class="widget-calc active" id="widget-resico">
                    <div class="widget-input-group">
                        <label>Tipo de Cálculo:</label>
                        <select id="widget-tipo-resico" class="widget-select">
                            <option value="mensual-a-anual">Mensual a Anual</option>
                            <option value="anual-a-mensual">Anual a Mensual</option>
                        </select>
                    </div>
                    
                    <div class="widget-input-group">
                        <label id="widget-label-resico">Ingreso Mensual:</label>
                        <div class="widget-input">
                            <span>$</span>
                            <input type="number" id="widget-ingreso-resico" placeholder="0.00" min="0" step="0.01">
                        </div>
                    </div>
                    
                    <div class="widget-input-group">
                        <label>Actividad Económica:</label>
                        <select id="widget-actividad-resico" class="widget-select">
                            <option value="servicios">Servicios Profesionales</option>
                            <option value="comercio">Comercio</option>
                            <option value="arrendamiento">Arrendamiento</option>
                            <option value="transportes">Transportes</option>
                            <option value="agricola">Actividades Agrícolas</option>
                            <option value="ganadero">Actividades Ganaderas</option>
                        </select>
                    </div>
                    
                    <button class="widget-btn-calcular" data-type="resico">Calcular RESICO</button>
                    
                    <div class="widget-resultado">
                        <p>Ingreso Equivalente: <span id="widget-ingreso-equivalente">$0.00</span></p>
                        <p>ISR Aproximado: <span id="widget-isr-resico">$0.00</span></p>
                        <p>Tasa Aplicada: <span id="widget-tasa-resico">0%</span></p>
                        
                        <div id="widget-comparativa-detalle"></div>
                        
                        <div style="font-size: 0.8rem; color: #a3a3a3; margin-top: 12px; padding-top: 8px; border-top: 1px dashed rgba(255, 255, 255, 0.1);">
                            <p>💡 <strong>Tabla RESICO 2024:</strong></p>
                            <p style="margin: 3px 0;">• 1.0% → Hasta $25,000</p>
                            <p style="margin: 3px 0;">• 1.1% → Hasta $50,000</p>
                            <p style="margin: 3px 0;">• 1.5% → Hasta $83,333.33</p>
                            <p style="margin: 3px 0;">• 2.0% → Hasta $208,333.33</p>
                            <p style="margin: 3px 0;">• 2.5% → Hasta $3,500,000</p>
                        </div>
                    </div>
                </div>
                
                <!-- ISR Personas Morales -->
                <div class="widget-calc" id="widget-isr">
                    <div class="widget-input-group">
                        <label>Ingreso Anual:</label>
                        <div class="widget-input">
                            <span>$</span>
                            <input type="number" id="widget-ingreso-isr" placeholder="0.00" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="widget-input-group">
                        <label>Régimen:</label>
                        <select id="widget-regimen-isr" class="widget-select">
                            <option value="general">General</option>
                            <option value="simplificado">RESICO</option>
                        </select>
                    </div>
                    <button class="widget-btn-calcular" data-type="isr">Calcular ISR</button>
                    <div class="widget-resultado">
                        <p>ISR Anual: <span id="widget-isr-anual">$0.00</span></p>
                        <p>ISR Mensual: <span id="widget-isr-mensual">$0.00</span></p>
                    </div>
                </div>
                
                <!-- IVA -->
                <div class="widget-calc" id="widget-iva">
                    <div class="widget-input-group">
                        <label>IVA Ventas:</label>
                        <div class="widget-input">
                            <span>$</span>
                            <input type="number" id="widget-iva-ventas" placeholder="0.00" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="widget-input-group">
                        <label>IVA Compras:</label>
                        <div class="widget-input">
                            <span>$</span>
                            <input type="number" id="widget-iva-compras" placeholder="0.00" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="widget-input-group">
                        <label>Tasa IVA:</label>
                        <select id="widget-tasa-iva" class="widget-select">
                            <option value="0.16">16%</option>
                            <option value="0.08">8%</option>
                        </select>
                    </div>
                    <button class="widget-btn-calcular" data-type="iva">Calcular IVA</button>
                    <div class="widget-resultado">
                        <p>IVA por Pagar: <span id="widget-iva-pagar">$0.00</span></p>
                        <p>IVA a Favor: <span id="widget-iva-favor">$0.00</span></p>
                    </div>
                </div>
                
                <!-- Nómina -->
                <div class="widget-calc" id="widget-nomina">
                    <div class="widget-input-group">
                        <label>Sueldo Bruto:</label>
                        <div class="widget-input">
                            <span>$</span>
                            <input type="number" id="widget-sueldo-bruto" placeholder="0.00" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="widget-input-group">
                        <label>Tipo Contrato:</label>
                        <select id="widget-tipo-contrato" class="widget-select">
                            <option value="sueldo">Sueldo Base</option>
                            <option value="honorarios">Honorarios</option>
                        </select>
                    </div>
                    <button class="widget-btn-calcular" data-type="nomina">Calcular Nómina</button>
                    <div class="widget-resultado">
                        <p>Sueldo Neto: <span id="widget-sueldo-neto">$0.00</span></p>
                        <p>ISR Retenido: <span id="widget-isr-retenido">$0.00</span></p>
                    </div>
                </div>
                
                <!-- UMA -->
                <div class="widget-calc" id="widget-uma">
                    <div class="widget-input-group">
                        <label>Cantidad:</label>
                        <div class="widget-input">
                            <input type="number" id="widget-valor-uma" placeholder="0" min="0">
                        </div>
                    </div>
                    <div class="widget-input-group">
                        <label>Convertir:</label>
                        <select id="widget-conversion-uma" class="widget-select">
                            <option value="uma-a-pesos">UMA a Pesos</option>
                            <option value="pesos-a-uma">Pesos a UMA</option>
                            <option value="salario-minimo">Salarios Mínimos</option>
                        </select>
                    </div>
                    <button class="widget-btn-calcular" data-type="uma">Calcular</button>
                    <div class="widget-resultado">
                        <p id="widget-resultado-uma-text">Resultado: <span id="widget-resultado-uma">$0.00</span></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="widget-footer">
            <div class="uma-info-mini">
                <p><small>UMA 2026: $117.31 diarios</small></p>
                <p><small>Salario Mínimo: $315.04 diarios</small></p>
            </div>
        </div>
    </div>
    
    <!-- Botón para abrir el widget -->
    

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="js/calculadora.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const menuCalculadoras = document.querySelector('.menu-calculadoras');
    const trigger = document.querySelector('.calculadoras-trigger');
    
    if (menuCalculadoras && trigger) {
        // Variable para controlar si estamos en móvil
        let isMobile = window.innerWidth <= 768;
        
        // Actualizar en resize
        window.addEventListener('resize', function() {
            isMobile = window.innerWidth <= 768;
        });
        
        // Funcionalidad de click (funciona en todos los dispositivos)
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            menuCalculadoras.classList.toggle('active');
        });
        
        // Hover solo en desktop
        if (!isMobile) {
            menuCalculadoras.addEventListener('mouseenter', function() {
                this.classList.add('active');
            });
            
            menuCalculadoras.addEventListener('mouseleave', function() {
                this.classList.remove('active');
            });
        }
        
        // Cerrar al hacer click fuera
        document.addEventListener('click', function(e) {
            if (!menuCalculadoras.contains(e.target)) {
                menuCalculadoras.classList.remove('active');
            }
        });
        
        // Prevenir que clicks dentro del menú lo cierren
        const submenu = document.querySelector('.submenu-calculadoras');
        if (submenu) {
            submenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    }
});
</script>
    <script>
        
    // =============================================
    // INICIALIZACIÓN GENERAL
    // =============================================
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100,
            disable: function() {
                return window.innerWidth < 768;
            }
        });
        
        // =============================================
        // CARRUSEL HERO - FUNCIONALIDAD
        // =============================================
        function initCarousel() {
            const slides = document.querySelectorAll('.carousel-slide');
            const dots = document.querySelectorAll('.carousel-dot');
            const prevBtn = document.querySelector('.carousel-arrow.prev');
            const nextBtn = document.querySelector('.carousel-arrow.next');
            const progressBar = document.querySelector('.carousel-progress');
            
            if (!slides.length) return;
            
            let currentSlide = 0;
            let slideInterval;
            const slideDelay = 6000;
            
            function showSlide(index) {
                if (index < 0) index = slides.length - 1;
                if (index >= slides.length) index = 0;
                
                slides.forEach(slide => slide.classList.remove('active'));
                dots.forEach(dot => dot.classList.remove('active'));
                
                slides[index].classList.add('active');
                dots[index].classList.add('active');
                
                currentSlide = index;
                
                if (progressBar) {
                    progressBar.style.width = '0%';
                    setTimeout(() => {
                        progressBar.style.width = '100%';
                    }, 50);
                }
            }
            
            function nextSlide() {
                showSlide(currentSlide + 1);
            }
            
            function prevSlide() {
                showSlide(currentSlide - 1);
            }
            
            function startAutoplay() {
                stopAutoplay();
                slideInterval = setInterval(nextSlide, slideDelay);
                
                if (progressBar) {
                    progressBar.style.width = '0%';
                    setTimeout(() => {
                        progressBar.style.width = '100%';
                    }, 50);
                }
            }
            
            function stopAutoplay() {
                if (slideInterval) {
                    clearInterval(slideInterval);
                }
            }
            
            if (nextBtn) {
                nextBtn.addEventListener('click', function() {
                    nextSlide();
                    stopAutoplay();
                    startAutoplay();
                });
            }
            
            if (prevBtn) {
                prevBtn.addEventListener('click', function() {
                    prevSlide();
                    stopAutoplay();
                    startAutoplay();
                });
            }
            
            dots.forEach((dot, index) => {
                dot.addEventListener('click', function() {
                    showSlide(index);
                    stopAutoplay();
                    startAutoplay();
                });
            });
            
            const carousel = document.querySelector('.carousel-container');
            if (carousel) {
                carousel.addEventListener('mouseenter', stopAutoplay);
                carousel.addEventListener('mouseleave', startAutoplay);
            }
            
            startAutoplay();
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowLeft') {
                    prevSlide();
                    stopAutoplay();
                    startAutoplay();
                }
                if (e.key === 'ArrowRight') {
                    nextSlide();
                    stopAutoplay();
                    startAutoplay();
                }
            });
        }
        
        // Inicializar carrusel
        initCarousel();
        
        // =============================================
        // MENSAJE TEMPORAL DE CONTACTO (5 SEGUNDOS)
        // =============================================
        setTimeout(function() {
            const mensajeExito = document.querySelector('.mensaje-exito');
            
            if (mensajeExito) {
                setTimeout(function() {
                    mensajeExito.style.transition = 'all 0.3s ease';
                    mensajeExito.style.opacity = '0';
                    mensajeExito.style.transform = 'translateY(-10px)';
                    
                    setTimeout(function() {
                        const formulario = document.querySelector('form');
                        if (formulario) {
                            formulario.reset();
                        }
                        mensajeExito.style.display = 'none';
                    }, 300);
                }, 5000);
            }
        }, 100);
        
        // =============================================
        // SMOOTH SCROLL Y EFECTOS
        // =============================================
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            if (window.scrollY > 100) {
                header.classList.add('header-scroll');
            } else {
                header.classList.remove('header-scroll');
            }
        });
        
        // =============================================
        // ANIMACIÓN DE CONTADORES
        // =============================================
        function animarContadores() {
            const contadores = document.querySelectorAll('.estadistica-numero');
            const velocidad = 200;
            
            contadores.forEach(contador => {
                const actualizarContador = () => {
                    const texto = contador.innerText;
                    const valor = parseInt(texto.replace('+', '').replace(',', ''));
                    contador.setAttribute('data-objetivo', valor);
                    contador.innerText = '0';
                    
                    const observer = new IntersectionObserver((entries) => {
                        if (entries[0].isIntersecting) {
                            const objetivo = valor;
                            let conteo = 0;
                            const incremento = objetivo / velocidad;
                            
                            const animar = () => {
                                if (conteo < objetivo) {
                                    conteo += incremento;
                                    if (conteo > objetivo) conteo = objetivo;
                                    
                                    const tieneSignoMas = texto.includes('+');
                                    contador.innerText = (tieneSignoMas ? '+' : '') + Math.floor(conteo).toLocaleString('es-MX');
                                    
                                    setTimeout(animar, 1);
                                }
                            };
                            
                            animar();
                            observer.unobserve(contador);
                        }
                    });
                    
                    observer.observe(contador);
                };
                
                actualizarContador();
            });
        }
        
        setTimeout(animarContadores, 500);
        
        // =============================================
        // EFECTO REVEAL
        // =============================================
        function initReveal() {
            const reveals = document.querySelectorAll('.reveal');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });
            
            reveals.forEach(reveal => {
                observer.observe(reveal);
            });
        }
        
        initReveal();
    });
  // =============================================
// FORMULARIO DE CONTACTO - VERSIÓN COMBINADA
// =============================================
document.addEventListener('DOMContentLoaded', function() {
    
    // =============================================
    // OPCIÓN 1: FORMULARIO CON FORMSPREE
    // =============================================
    const formularioFormspree = document.querySelector('form[action="https://formspree.io/f/mzdaanwa"]');
    
    if (formularioFormspree) {
        console.log("✅ Formulario de Formspree encontrado");
        
        formularioFormspree.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            // Crear contenedor para mensajes si no existe
            let mensajeDiv = document.querySelector('.mensaje-contacto');
            if (!mensajeDiv) {
                mensajeDiv = document.createElement('div');
                mensajeDiv.className = 'mensaje-contacto';
                mensajeDiv.style.marginTop = '20px';
                mensajeDiv.style.textAlign = 'center';
                this.appendChild(mensajeDiv);
            }
            
            // Mostrar mensaje de carga
            mensajeDiv.innerHTML = '<div style="padding: 15px; background: #cce5ff; color: #004085; border-radius: 5px;">⏰ Enviando mensaje...</div>';
            mensajeDiv.style.display = 'block';
            
            try {
                const response = await fetch('https://formspree.io/f/mzdaanwa', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                const resultado = await response.json();
                
                if (response.ok) {
                    // Mostrar mensaje de éxito
                    mensajeDiv.innerHTML = '<div style="padding: 20px; background: #d4edda; color: #155724; border-radius: 8px; border: 2px solid #c3e6cb; font-weight: bold;">✓ ¡Mensaje enviado correctamente! Te contactaremos pronto.<br><span style="font-size: 14px; font-weight: normal;">La página se recargará en 3 segundos...</span></div>';
                    
                    this.reset(); // Limpiar formulario
                    
                    // Recargar después de 3 segundos
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                } else {
                    // Mostrar mensaje de error
                    mensajeDiv.innerHTML = '<div style="padding: 15px; background: #f8d7da; color: #721c24; border-radius: 5px;">✗ Hubo un error al enviar. Por favor intenta de nuevo.</div>';
                    
                    // Ocultar después de 5 segundos
                    setTimeout(() => {
                        mensajeDiv.style.display = 'none';
                    }, 5000);
                }
            } catch (error) {
                mensajeDiv.innerHTML = '<div style="padding: 15px; background: #f8d7da; color: #721c24; border-radius: 5px;">✗ Error de conexión. Por favor intenta de nuevo.</div>';
                
                setTimeout(() => {
                    mensajeDiv.style.display = 'none';
                }, 5000);
            }
        });
    }
    
    // =============================================
    // OPCIÓN 2: FORMULARIO CON PHP LOCAL (enviar_formulario.php)
    // =============================================
    const formularioLocal = document.getElementById('formulario-contacto') || 
                           document.querySelector('form[action="enviar_consulta.php"]');
    
    if (formularioLocal) {
        console.log("✅ Formulario local encontrado, configurando envío...");
        
        formularioLocal.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log("📤 Enviando formulario local...");
            
            const formData = new FormData(this);
            
            // Crear o obtener el div de resultado
            let resultadoDiv = document.getElementById('resultado-formulario');
            if (!resultadoDiv) {
                resultadoDiv = document.createElement('div');
                resultadoDiv.id = 'resultado-formulario';
                resultadoDiv.style.marginTop = '20px';
                resultadoDiv.style.textAlign = 'center';
                formularioLocal.appendChild(resultadoDiv);
            }
            
            // Mostrar mensaje de carga
            resultadoDiv.innerHTML = '<div style="padding: 15px; background: #cce5ff; color: #004085; border-radius: 5px;">⏰ Enviando mensaje...</div>';
            
            // Deshabilitar botón
            const boton = this.querySelector('button[type="submit"]');
            if (boton) boton.disabled = true;
            
            // Enviar a tu archivo PHP
            fetch('enviar_consulta.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log("📥 Respuesta recibida:", response);
                return response.json();
            })
            .then(data => {
                console.log("📊 Datos:", data);
                
                if (data.success) {
                    // Mostrar mensaje de éxito
                    resultadoDiv.innerHTML = '<div style="padding: 20px; background: #d4edda; color: #155724; border-radius: 8px; border: 2px solid #c3e6cb; font-weight: bold;">✓ ' + data.message + '<br><span style="font-size: 14px; font-weight: normal;">La página se recargará en 3 segundos...</span></div>';
                    
                    // Recargar después de 3 segundos
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                } else {
                    // Mostrar error
                    resultadoDiv.innerHTML = '<div style="padding: 15px; background: #f8d7da; color: #721c24; border-radius: 5px;">✗ ' + data.message + '</div>';
                    if (boton) boton.disabled = false;
                    
                    // Ocultar después de 5 segundos
                    setTimeout(() => {
                        resultadoDiv.style.display = 'none';
                    }, 5000);
                }
            })
            .catch(error => {
                console.error("❌ Error:", error);
                resultadoDiv.innerHTML = '<div style="padding: 15px; background: #f8d7da; color: #721c24; border-radius: 5px;">✗ Error de conexión. Intenta de nuevo.</div>';
                if (boton) boton.disabled = false;
                
                setTimeout(() => {
                    resultadoDiv.style.display = 'none';
                }, 5000);
            });
        });
    }
    
    // =============================================
    // SI NO HAY FORMULARIOS
    // =============================================
    if (!formularioFormspree && !formularioLocal) {
        console.warn("⚠️ No se encontró ningún formulario de contacto");
    }
});
// assets/js/calculadoras.js

document.addEventListener('DOMContentLoaded', function() {
    // Formatear moneda mientras se escribe
    const ingresoInputs = document.querySelectorAll('input[type="number"]');
    ingresoInputs.forEach(input => {
        input.addEventListener('blur', function() {
            let valor = parseFloat(this.value) || 0;
            this.value = valor.toFixed(2);
        });
    });

    // Validar que el ingreso no sea negativo
    ingresoInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.value < 0) this.value = 0;
        });
    });

    // Manejo de tabs de calculadoras
    const tabBtns = document.querySelectorAll('.tab-btn');
    const calculadoras = document.querySelectorAll('.calculadora-item');
    
    function showCalculadora(calcId) {
        calculadoras.forEach(calc => {
            if(calc.dataset.calc === calcId) {
                calc.classList.add('active');
            } else {
                calc.classList.remove('active');
            }
        });
        
        tabBtns.forEach(btn => {
            if(btn.dataset.calc === calcId) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
    }
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const calcId = this.dataset.calc;
            showCalculadora(calcId);
            history.pushState(null, null, `#calculadora-${calcId}`);
        });
    });
    
    // Manejo de clicks en el menú
    const calcLinks = document.querySelectorAll('.calc-link');
    calcLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const calcId = this.dataset.calc;
            showCalculadora(calcId);
            
            document.getElementById('calculadoras').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
            
            // Cerrar menú desplegable
            const menuCalculadoras = document.querySelector('.menu-calculadoras');
            if(menuCalculadoras) {
                menuCalculadoras.classList.remove('active');
            }
        });
    });
    
    // Mostrar calculadora basada en el hash de la URL
    if(window.location.hash) {
        const hash = window.location.hash.replace('#calculadora-', '');
        if(hash) {
            showCalculadora(hash);
        }
    }
    
    // Animación de resultados
    const resultados = document.querySelectorAll('.resultados-calculadora');
    resultados.forEach(resultado => {
        if(resultado.children.length > 0) {
            resultado.style.opacity = '0';
            setTimeout(() => {
                resultado.style.transition = 'opacity 0.5s ease';
                resultado.style.opacity = '1';
            }, 100);
        }
    });
});
    </script>
    
    <!-- Estilos para gráficos -->
    <style>
        .comparativa-grafico {
            margin: 15px 0;
            padding: 12px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .grafico-barras {
            display: flex;
            align-items: flex-end;
            height: 140px;
            gap: 30px;
            padding: 15px;
            position: relative;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            margin-bottom: 10px;
        }
        
        .barra-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100%;
            justify-content: flex-end;
        }
        
        .barra {
            width: 35px;
            background: linear-gradient(to top, #2563eb, #1d4ed8);
            border-radius: 6px 6px 0 0;
            transition: all 0.5s ease;
            position: relative;
            min-height: 5px;
        }
        
        .barra.resico {
            background: linear-gradient(to top, #10b981, #059669);
        }
        
        .barra.general {
            background: linear-gradient(to top, #ef4444, #dc2626);
        }
        
        .barra-label {
            margin-top: 10px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #a3a3a3;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .barra-valor {
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.75rem;
            font-weight: 700;
            color: #ffffff;
            background: rgba(0, 0, 0, 0.8);
            padding: 4px 8px;
            border-radius: 12px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.3);
            white-space: nowrap;
            border: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 10;
        }
        
        .grafico-leyenda {
            display: flex;
            justify-content: center;
            gap: 25px;
            margin-top: 15px;
            font-size: 0.85rem;
        }
        
        .leyenda-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .leyenda-color {
            width: 14px;
            height: 14px;
            border-radius: 3px;
        }
        
        .leyenda-color.resico {
            background: #10b981;
        }
        
        .leyenda-color.general {
            background: #ef4444;
        }
        
        .barra:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        
        .barra:hover .barra-valor {
            transform: translateX(-50%) scale(1.1);
            z-index: 20;
        }
        
        @keyframes crecerBarra {
            from { height: 0%; opacity: 0; }
            to { height: var(--altura-final); opacity: 1; }
        }
        
        .barra {
            animation: crecerBarra 1s ease-out;
        }
        
        @media (max-width: 768px) {
            .grafico-barras {
                height: 120px;
                gap: 20px;
                padding: 10px;
            }
            .barra {
                width: 28px;
            }
            .barra-valor {
                font-size: 0.7rem;
                padding: 3px 6px;
                top: -25px;
            }
            .grafico-leyenda {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }
        }
        
        @media (max-width: 480px) {
            .grafico-barras {
                height: 100px;
                gap: 15px;
            }
            .barra {
                width: 25px;
            }
            .barra-label {
                font-size: 0.7rem;
            }
            .barra-valor {
                font-size: 0.65rem;
                padding: 2px 5px;
                top: -22px;
            }
        }
        
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #2563eb;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #1d4ed8;
        }
        :root {
    --primary-blue: #2c3e50;
    --secondary-blue: #3498db;
    --light-blue: #e8f4fc;
    --dark-gray: #2c3e50;
    --medium-gray: #7f8c8d;
    --light-gray: #ecf0f1;
    --white: #ffffff;
    --black: #2c3e50;
    --border-color: #dde2e7;
    --success-color: #27ae60;
    --warning-color: #f39c12;
}

/* Sección de calculadoras */
.calculadoras-seccion {
    padding: 80px 0;
    background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
}

.calculadoras-seccion .section-title h2 {
    color: var(--dark-gray);
    font-size: 2.5rem;
    font-weight: 600;
    margin-bottom: 15px;
}

.calculadoras-seccion .section-title p {
    color: var(--medium-gray);
    font-size: 1.1rem;
}

/* Tabs de calculadoras */
.calculadoras-tabs {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
    margin-bottom: 40px;
}

.tab-btn {
    padding: 12px 24px;
    background: var(--white);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    color: var(--dark-gray);
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.tab-btn:hover {
    background: var(--light-blue);
    border-color: var(--secondary-blue);
    color: var(--secondary-blue);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.2);
}

.tab-btn.active {
    background: var(--secondary-blue);
    border-color: var(--secondary-blue);
    color: var(--white);
}

/* Contenedor de calculadoras */
.calculadoras-contenedor {
    position: relative;
    min-height: 700px;
}

.calculadora-item {
    display: none;
    background: var(--white);
    border-radius: 15px;
    padding: 40px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    animation: fadeIn 0.5s ease;
    border: 1px solid var(--border-color);
}

.calculadora-item.active {
    display: block;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.calculadora-nombre {
    color: var(--dark-gray);
    font-size: 2rem;
    margin-bottom: 10px;
    text-align: center;
    font-weight: 600;
    border-bottom: 2px solid var(--light-gray);
    padding-bottom: 15px;
}

.calculadora-subtitulo {
    text-align: center;
    color: var(--medium-gray);
    margin-bottom: 30px;
    font-size: 0.95rem;
}

/* Formulario */
.calculadora-form {
    max-width: 600px;
    margin: 0 auto;
    background: var(--light-gray);
    padding: 30px;
    border-radius: 15px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--dark-gray);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.select-wrapper {
    position: relative;
}

.select-wrapper::after {
    content: '▼';
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--secondary-blue);
    pointer-events: none;
    font-size: 12px;
}

.form-select {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    font-size: 1rem;
    color: var(--dark-gray);
    background: var(--white);
    cursor: pointer;
    appearance: none;
    transition: all 0.3s ease;
}

.form-select:hover,
.form-select:focus {
    border-color: var(--secondary-blue);
    outline: none;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.input-wrapper {
    position: relative;
}

.currency-symbol {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--medium-gray);
    font-weight: 600;
    font-size: 1.1rem;
}

.form-input {
    width: 100%;
    padding: 12px 15px 12px 35px;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-input:hover,
.form-input:focus {
    border-color: var(--secondary-blue);
    outline: none;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.btn-calcular-principal {
    width: 100%;
    padding: 14px;
    background: var(--secondary-blue);
    color: var(--white);
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.btn-calcular-principal:hover {
    background: var(--primary-blue);
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(52, 152, 219, 0.3);
}

/* Resultados */
.resultados-calculadora {
    margin-top: 40px;
}

.resultados-titulo {
    color: var(--dark-gray);
    font-size: 1.5rem;
    margin-bottom: 20px;
    font-weight: 600;
    text-align: left;
    border-left: 4px solid var(--secondary-blue);
    padding-left: 15px;
}

.tabla-container {
    overflow-x: auto;
    margin-bottom: 30px;
    background: var(--light-gray);
    border-radius: 10px;
    padding: 20px;
}

.tabla-resultados {
    width: 100%;
    border-collapse: collapse;
    background: var(--white);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.tabla-resultados th {
    background: var(--primary-blue);
    color: var(--white);
    padding: 15px;
    text-align: left;
    font-weight: 500;
    font-size: 0.9rem;
}

.tabla-resultados td {
    padding: 12px 15px;
    border-bottom: 1px solid var(--light-gray);
}

.tabla-resultados td:first-child {
    font-weight: 600;
    color: var(--dark-gray);
    width: 40%;
}

.tabla-resultados td:last-child {
    text-align: right;
    font-weight: 500;
    color: var(--medium-gray);
}

.tabla-resultados tr:last-child td {
    border-bottom: none;
}

.tabla-resultados .resultado-destacado {
    background: var(--light-blue);
}

.tabla-resultados .resultado-destacado td {
    color: var(--secondary-blue);
    font-weight: 600;
}

.tabla-resultados .resultado-final {
    background: var(--primary-blue);
    color: var(--white);
    font-weight: 600;
}

.tabla-resultados .resultado-final td {
    color: var(--white);
}

/* Tabla de percepción */
.percepcion-container {
    background: var(--white);
    border-radius: 10px;
    padding: 25px;
    margin-top: 30px;
    border: 1px solid var(--border-color);
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.percepcion-titulo {
    color: var(--dark-gray);
    font-size: 1.2rem;
    margin-bottom: 15px;
    font-weight: 500;
}

.percepcion-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.percepcion-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    background: var(--light-gray);
    border-radius: 6px;
    font-size: 0.95rem;
}

.percepcion-item .label {
    color: var(--medium-gray);
    font-weight: 500;
}

.percepcion-item .valor {
    color: var(--dark-gray);
    font-weight: 600;
    font-size: 1.1rem;
}

.percepcion-item.total {
    background: var(--primary-blue);
    grid-column: 1 / -1;
}

.percepcion-item.total .label,
.percepcion-item.total .valor {
    color: var(--white);
    font-size: 1.1rem;
}

.percepcion-item.total .valor {
    font-size: 1.3rem;
}

/* Comparativa de modalidades */
.comparativa-modalidades {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-top: 40px;
}

.modalidad-card {
    background: var(--white);
    border-radius: 12px;
    padding: 25px;
    border: 1px solid var(--border-color);
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.modalidad-card h4 {
    color: var(--dark-gray);
    font-size: 1.2rem;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--light-gray);
}

.modalidad-card .modalidad-year {
    color: var(--secondary-blue);
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 5px;
}

.modalidad-card .modalidad-desc {
    color: var(--medium-gray);
    font-size: 0.9rem;
    margin-bottom: 20px;
}

.modalidad-card .resultado-diferencia {
    margin-top: 15px;
    padding: 10px;
    background: var(--light-blue);
    border-radius: 6px;
    color: var(--secondary-blue);
    font-weight: 500;
    text-align: center;
}

/* Enlace RESICO */
.resico-link {
    text-align: center;
    margin-top: 40px;
    padding: 20px;
    background: var(--light-gray);
    border-radius: 8px;
}

.resico-link p {
    margin: 0;
    color: var(--medium-gray);
}

.resico-link a {
    color: var(--secondary-blue);
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.resico-link a:hover {
    color: var(--primary-blue);
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 768px) {
    .calculadoras-seccion {
        padding: 50px 0;
    }
    
    .calculadora-item {
        padding: 20px;
    }
    
    .calculadora-nombre {
        font-size: 1.5rem;
    }
    
    .comparativa-modalidades {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .percepcion-grid {
        grid-template-columns: 1fr;
    }
    
    .tabla-resultados td {
        font-size: 0.9rem;
        padding: 10px;
    }
}

@media (max-width: 480px) {
    .calculadoras-tabs {
        flex-direction: column;
        align-items: stretch;
    }
    
    .tab-btn {
        width: 100%;
        text-align: center;
    }
}

    </style>
</body>
</html>