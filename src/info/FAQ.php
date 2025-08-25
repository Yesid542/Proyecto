<?php
session_start();

include '../../src/templates/header.php';
include '../../src/templates/navegador.php';
?>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .faq-container {
            max-width: 900px;
            margin: auto;
            padding: 40px 20px;
        }
        .faq-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .faq-item {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: transform 0.2s ease-in-out;
        }
        .faq-item:hover {
            transform: scale(1.02);
        }
        .faq-question {
            font-size: 1.2rem;
            font-weight: bold;
            color: #B03A8E; /* Color más claro de #961B71 */
        }
        .faq-answer {
            display: none;
            margin-top: 15px; /* Aumenté la separación */
            color: #555;
            line-height: 1.6;
        }
    </style>

    <div class="faq-container">
        <h1 class="faq-title">Preguntas Frecuentes</h1>
        <div class="faq-item" onclick="toggleAnswer(this)">
            <div class="faq-question">¿Cómo puedo hacer un pedido en la tienda en línea?</div>
            <div class="faq-answer">Selecciona el producto, agrégalo al carrito y sigue las instrucciones para el pago.</div>
        </div>
        <div class="faq-item" onclick="toggleAnswer(this)">
            <div class="faq-question">¿Cómo sé si mi compra fue exitosa?</div>
            <div class="faq-answer">Recibirás un correo de confirmación con los detalles de tu compra.</div>
        </div>
        <div class="faq-item" onclick="toggleAnswer(this)">
            <div class="faq-question">¿Puedo realizar pedidos por WhatsApp o redes sociales?</div>
            <div class="faq-answer">Sí, contáctanos por WhatsApp o nuestras redes sociales y te asistiremos.</div>
        </div>
        <div class="faq-item" onclick="toggleAnswer(this)">
            <div class="faq-question">¿Puedo recoger mi pedido en una tienda física?</div>
            <div class="faq-answer">Sí, tenemos la opción de recogida en tienda. Consulta nuestras ubicaciones.</div>
        </div>
        <div class="faq-item" onclick="toggleAnswer(this)">
            <div class="faq-question">¿Los modelos de calzado son unisex?</div>
            <div class="faq-answer">Tenemos modelos para todos, revisa la descripción de cada producto.</div>
        </div>
        <div class="faq-item" onclick="toggleAnswer(this)">
            <div class="faq-question">¿Cuánto tiempo de garantía tienen los zapatos?</div>
            <div class="faq-answer">Todos nuestros productos cuentan con una garantía de 30 días.</div>
        </div>
        <div class="faq-item" onclick="toggleAnswer(this)">
            <div class="faq-question">¿Cómo puedo devolver o cambiar un producto?</div>
            <div class="faq-answer">Puedes devolver o cambiar tu producto en un plazo de 15 días con la factura.</div>
        </div>
        <div class="faq-item" onclick="toggleAnswer(this)">
            <div class="faq-question">¿Cuánto tiempo tengo para hacer una devolución?</div>
            <div class="faq-answer">Tienes hasta 15 días después de la compra para realizar una devolución.</div>
        </div>
        <div class="faq-item" onclick="toggleAnswer(this)">
            <div class="faq-question">¿Puedo cambiar un producto en cualquier tienda física?</div>
            <div class="faq-answer">Sí, siempre y cuando lleves la factura de compra.</div>
        </div>
        <div class="faq-item" onclick="toggleAnswer(this)">
            <div class="faq-question">¿Cómo puedo contactar con soporte si tengo un problema?</div>
            <div class="faq-answer">Puedes escribirnos a nuestro correo o llamarnos al número de atención al cliente.</div>
        </div>
        <div class="faq-item" onclick="toggleAnswer(this)">
            <div class="faq-question">¿Dónde están ubicadas sus tiendas físicas?</div>
            <div class="faq-answer">Consulta nuestras ubicaciones en la sección "Nuestras Tiendas".</div>
        </div>
        <div class="faq-item" onclick="toggleAnswer(this)">
            <div class="faq-question">¿Cuál es el horario de atención al cliente?</div>
            <div class="faq-answer">Nuestro horario es de 9:00 AM a 7:00 PM de lunes a sábado.</div>
        </div>
    </div>
    
    <script>
        function toggleAnswer(clickedItem) {
            let allAnswers = document.querySelectorAll(".faq-answer");
            let allItems = document.querySelectorAll(".faq-item");

            // Cierra todas las respuestas excepto la que se está abriendo
            allAnswers.forEach(answer => {
                if (answer !== clickedItem.querySelector(".faq-answer")) {
                    answer.style.display = "none";
                }
            });

            // Alterna la visibilidad de la respuesta seleccionada
            let answer = clickedItem.querySelector(".faq-answer");
            answer.style.display = (answer.style.display === "none" || answer.style.display === "") ? "block" : "none";
        }
    </script>

<?php include "../../src/templates/footer.php"; ?>