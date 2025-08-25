<?php
session_start();

include '../../src/templates/header.php';
include '../../src/templates/navegador.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuestras Sedes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card img {
            height: 250px;
            object-fit: cover;
        }
        .section-title {
            font-size: 2rem;
            font-weight: bold;
            color: #343a40;
            text-transform: uppercase;
        }
        .map-container {
            height: 300px;
            overflow: hidden;
            border-radius: 10px;
        }
        .map-container iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }
        .map-btn {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-5 section-title">Descubre Nuestras Sedes</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <img src="/public/img/Sede1.png" class="card-img-top" alt="Sede Principal">
                    <div class="card-body">
                        <h5 class="card-title">Sede Principal</h5>
                        <p class="card-text"><strong>Dirección:</strong> Carrera 12 con Calle 17, Tunja, Boyacá, Colombia</p>
                        <p class="card-text">Nuestra sede principal es un espacio moderno y vibrante donde encontrarás una amplia variedad de calzado. Diseñada para ofrecer comodidad y un servicio excepcional, aquí podrás descubrir las últimas tendencias en moda.</p>
                        <div class="map-container">
                            <iframe src="https://www.google.com/maps/embed?pb=!4v1742875448963!6m8!1m7!1sy6aO9vklCEc2jHq65T3jOQ!2m2!1d5.530519304320697!2d-73.3643130322254!3f28.76!4f-4.450000000000003!5f0.7820865974627469" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <a href="https://maps.app.goo.gl/f9v1XvH8KEak53rf6" target="_blank" class="btn btn-primary w-100 map-btn">Ver en Google Maps</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <img src="/public/img/Sede2.png" class="card-img-top" alt="Sede Secundaria">
                    <div class="card-body">
                        <h5 class="card-title">Sede Secundaria</h5>
                        <p class="card-text"><strong>Dirección:</strong> Carrera 10 con Calle 18, Tunja, Boyacá, Colombia</p>
                        <p class="card-text">Ubicada en pleno centro de la ciudad, nuestra sede secundaria es el lugar perfecto para encontrar el calzado ideal. Con un ambiente cálido y accesible, aquí disfrutarás de una experiencia de compra única.</p>
                        <div class="map-container">
                            <iframe src="https://www.google.com/maps/embed?pb=!4v1742875468665!6m8!1m7!1sYxIObpTR6u4MyG15HbApgw!2m2!1d5.531019222339195!2d-73.36223732126892!3f223.26!4f-1.519999999999996!5f0.7820865974627469" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <a href="https://maps.app.goo.gl/akC72aP2Bj2ocQov7" target="_blank" class="btn btn-primary w-100 map-btn">Ver en Google Maps</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



<?php include "../../src/templates/footer.php"; ?>