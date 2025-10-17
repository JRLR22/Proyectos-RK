<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Replica Gonvill - Demo</title>

  <!-- Fuentes -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">

  <style>
    :root{
      --blue-top: #19a4d6; /* barra superior */
      --link-blue: #1aa6d9;
      --text-main: #223;
      --muted:#7b8a99;
      --gray-line:#e6e6e6;
      --container-max:1200px;
    }

    *{box-sizing:border-box}
    html,body{height:100%;margin:0;font-family: "Roboto", sans-serif;color:var(--text-main);background:white}
    a{color:inherit;text-decoration:none}

    /* Barra social superior */
    .topbar{
      background:var(--blue-top);
      color:white;
      padding:6px 12px;
      font-size:14px;
    }
    .topbar .inner{
      max-width:var(--container-max);
      margin:0 auto;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:10px;
    }
    .socials{display:flex;gap:8px;align-items:center}
    .socials .circle{width:28px;height:28px;border-radius:50%;background:rgba(255,255,255,0.12);display:flex;align-items:center;justify-content:center;font-size:13px}

    .top-right{font-size:14px;display:flex;gap:14px;align-items:center}
    .top-right a{color:white;opacity:0.95}

    /* Header principal */
    header{
      border-bottom:1px solid var(--gray-line);
      background:white;
      position:sticky;
      top:0;
      z-index:50;
    }
    .header-inner{
      max-width:var(--container-max);
      margin:18px auto;
      display:flex;
      align-items:center;
      gap:18px;
      padding:0 12px;
    }

    .logo{
      width:230px;
      display:flex;
      align-items:center;
      gap:10px;
    }
    .logo img{max-width:100%;height:auto;display:block}

    /* buscador */
    .search-wrap{
      flex:1;
      display:flex;
      align-items:center;
      gap:8px;
    }
    .search-box{
      flex:1;
      display:flex;
      align-items:center;
      border:1px solid #d7e6ee;
      border-radius:3px;
      overflow:hidden;
      background:#fff;
      box-shadow:none;
    }
    .search-box input{
      border:0;padding:12px 14px;font-size:15px;flex:1;outline:none;
    }
    .search-btn{
      background:var(--link-blue);color:white;padding:10px 14px;border-left:1px solid rgba(0,0,0,0.05);cursor:pointer;
    }

    /* icons right */
    .header-actions{display:flex;align-items:center;gap:14px}
    .icon-btn{display:flex;flex-direction:column;align-items:center;font-size:13px;color:var(--muted)}
    .icon-sprite{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#f6f8fa;border:1px solid #eee}

    /* nav */
    nav{
      border-top:1px solid var(--gray-line);
      border-bottom:1px solid var(--gray-line);
      background:white;
    }
    .nav-inner{
      max-width:var(--container-max);
      margin:0 auto;
      display:flex;
      padding:12px;
      gap:18px;
      align-items:center;
    }
    .nav-inner a{color:#17678a;padding:8px 6px;font-weight:500}

    /* Hero */
    .hero{
      max-width:var(--container-max);
      margin:18px auto;
      position:relative;
      overflow:visible;
      padding:0 12px;
    }
    .hero .banner{
      border-radius:4px;
      overflow:hidden;
      position:relative;
      background:linear-gradient(180deg,#f6fbff 0%, #ffffff 100%);
      height:420px;
      display:flex;
      align-items:center;
      justify-content:center;
      background-size:cover;
      background-position:center;
      box-shadow:0 0 0 rgba(0,0,0,0.02);
    }

    /* Replace the background-image url below with your banner image */
    .hero .banner .left-visual{
      width:100%;
      height:420px;
      background-image: url('hero-placeholder.jpg'); /* CAMBIAR: ruta de la imagen grande */
      background-size: cover;
      background-position: center;
      filter: none;
    }

    /* carousel arrows */
    .arrow{
      position:absolute;
      top:50%;
      transform:translateY(-50%);
      width:44px;height:44px;border-radius:50%;
      background:rgba(255,255,255,0.9);
      border:1px solid #e6e6e6;
      display:flex;align-items:center;justify-content:center;
      cursor:pointer;
      box-shadow:0 4px 12px rgba(0,0,0,0.06);
    }
    .arrow.left{left:6px}
    .arrow.right{right:6px}

    /* small responsive tweaks */
    @media (max-width:980px){
      .logo{width:170px}
      .hero .banner{height:360px}
      .hero .banner .left-visual{height:360px}
    }
    @media (max-width:700px){
      .topbar .inner{flex-direction:column;gap:8px;align-items:flex-start}
      .header-inner{flex-wrap:wrap;gap:10px}
      .search-wrap{order:3;width:100%}
      .logo{width:150px}
      .nav-inner{overflow:auto}
      .hero .banner{height:300px}
      .hero .banner .left-visual{height:300px}
    }

    /* utils */
    .container{max-width:var(--container-max);margin:0 auto;padding:0 12px}
  </style>
</head>
<body>

  <!-- BARRA SUPERIOR -->
  <div class="topbar">
    <div class="inner">
      <div class="socials">
        <div class="circle" title="Facebook">f</div>
        <div class="circle" title="Twitter">t</div>
        <div class="circle" title="Instagram">ig</div>
        <div class="circle" title="YouTube">yt</div>
      </div>

      <div class="top-right">
        <div>Contacto</div>
        <div>|</div>
        <div>Mi cuenta</div>
      </div>
    </div>
  </div>

  <!-- HEADER -->
  <header>
    <div class="header-inner">
      <!-- logo (cambiar la imagen por la real) -->
      <div class="logo">
        <img src="logo-placeholder.png" alt="Logo - Reemplazar por logo real" />
      </div>

      <!-- search -->
      <div class="search-wrap">
        <div class="search-box" role="search" aria-label="Buscador">
          <input type="search" placeholder="Título, Autor, ISBN, Código Gonvill" aria-label="buscar"/>
          <button class="search-btn" aria-label="buscar">🔍</button>
        </div>
      </div>

      <!-- actions -->
      <div class="header-actions">
        <div class="icon-btn">
          <div class="icon-sprite">♡</div>
          <div style="font-size:12px;color:var(--muted)">Lista</div>
        </div>

        <div class="icon-btn">
          <div><link rel="stylesheet" href="resources\img\carritodecompras.png"></div>
          <div style="font-size:12px;color:var(--muted)">Mi compra</div>
        </div>
      </div>
    </div>
  </header>

  <!-- NAV -->
  <nav>
    <div class="nav-inner">
      <a href="#">Inicio</a>
      <a href="#">Libros ▾</a>
      <a href="#">Impresión bajo demanda</a>
      <a href="#">Sobre Nosotros</a>
      <a href="#">Nuestras librerías</a>
      <a href="#">Bolsa de trabajo</a>
      <a href="#">Ayuda</a>
      <a href="#">SchoolShop</a>
    </div>
  </nav>

  <!-- HERO / BANNER -->
  <main class="hero">
    <div class="banner" aria-hidden="false">
      <!-- Imagen grande -->
      <div class="left-visual" role="img" aria-label="Banner principal">
        <!-- si quieres, coloca contenido encima con posicion absolute -->
      </div>

      <!-- flechas carousel -->
      <button class="arrow left" aria-label="anterior">‹</button>
      <button class="arrow right" aria-label="siguiente">›</button>
    </div>
  </main>

  <!-- Pie (opcional) -->
  <footer class="container" style="padding:24px 12px;color:var(--muted);border-top:1px solid var(--gray-line);">
    Mockup creado para replicar el layout. Reemplaza los placeholders por los activos reales.
  </footer>

</body>
</html>
