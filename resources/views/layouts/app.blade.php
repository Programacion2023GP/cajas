<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="csrf-token" content="{{ csrf_token() }}">

   <title>{{ config('app.name', 'Turnos') }}</title>

   <!-- Fonts -->
   <link rel="preconnect" href="https://fonts.bunny.net">
   <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   <!-- Scripts -->
   @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased">
   <div class="min-h-screen bg-gray-100">
      @include('layouts.navigation')

      <!-- Page Heading -->
      @if (isset($header))
      <header class="bg-white shadow">
         <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            {{ $header }}
         </div>
      </header>
      @endif

      <!-- Page Content -->
      <main>
         {{ $slot }}
      </main>
   </div>
   <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
   <script>
   // Configuración de Pusher
   const pusher = new Pusher('b70b1baa91c69051cb75', {
      cluster: 'us2'
   });

   // FUNCIONES GLOBALES - deben estar fuera del DOMContentLoaded
   async function ejecutarReinicio(button) {
      // Animación de clic
      button.style.transform = 'scale(0.95)';
      const originalText = button.innerHTML;

      // Crear efecto de partículas
      crearParticulas(button);

      // Confirmación
      if (confirm('¿Reiniciar el conteo de turnos?\nEsta acción no se puede deshacer.')) {
         // Agregar clase de loading
         button.innerHTML = `
            <div class="flex items-center justify-center gap-2">
                <div class="w-5 h-5 border-2 border-secondary border-t-transparent rounded-full animate-spin"></div>
                <span class="animate-pulse">REINICIANDO...</span>
            </div>
         `;
         button.disabled = true;

         try {
            // Enviar petición AJAX
            const response = await fetch('{{ route("reset") }}', {
               method: 'GET',
               headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': '{{ csrf_token() }}',
                  'Accept': 'application/json'
               }
            });

            // const data = await response.json();

            if (response.ok && response.statusText === 'OK') {
               // Mostrar notificación de éxito
               showNotification('Conteo reiniciado exitosamente', 'success');

               // Actualizar interfaz si es necesario
               //    if (data.nuevoTurno) {
               //       document.getElementById('turno-lbl').textContent = data.nuevoTurno;
               //    }

               // Redireccionar después de 2 segundos
               // setTimeout(() => {
               //     window.location.reload();
               // }, 2000);

            } else {
               throw new Error(response.statusText || 'Error al reiniciar');
            }

         } catch (error) {
            // Mostrar error
            showNotification(error.message || 'Error en la conexión', 'error');

         } finally {
            // Restaurar botón
            button.disabled = false;
            button.classList.add('hover:-translate-y-1', 'hover:shadow-xl');
            button.innerHTML = originalText;
         }
      } else {
         // Restaurar animación si cancela
         setTimeout(() => {
            button.style.transform = '';
         }, 200);
      }
   }

   function showNotification(message, type = 'info') {
      // Crear notificación
      const notification = document.createElement('div');
      notification.className = `fixed top-4 right-4 px-6 py-3 rounded-xl shadow-xl font-avenir font-medium text-white z-50 transform transition-all duration-300 ${
        type === 'error' ? 'bg-red-500' : 
        type === 'success' ? 'bg-green-500' :
        type === 'warning' ? 'bg-yellow-500 text-white' :
         'bg-guinda-principal text-white'
    }`;
      notification.textContent = message;

      document.body.appendChild(notification);

      // Remover después de 3 segundos
      setTimeout(() => {
         notification.style.opacity = '0';
         notification.style.transform = 'translateX(100px)';
         setTimeout(() => notification.remove(), 300);
      }, 3000);
   }

   function crearParticulas(element) {
      const rect = element.getBoundingClientRect();
      for (let i = 0; i < 8; i++) {
         const particle = document.createElement('div');
         particle.className = 'absolute w-2 h-2 bg-white rounded-full opacity-70';
         particle.style.left = `${rect.width / 2}px`;
         particle.style.top = `${rect.height / 2}px`;
         element.appendChild(particle);

         // Animar partícula
         const angle = (Math.PI * 2 * i) / 8;
         const distance = 30;
         particle.animate([{
               transform: 'translate(0,0) scale(1)',
               opacity: 0.7
            },
            {
               transform: `translate(${Math.cos(angle) * distance}px, ${Math.sin(angle) * distance}px) scale(0)`,
               opacity: 0
            }
         ], {
            duration: 500,
            easing: 'ease-out'
         }).onfinish = () => particle.remove();
      }
   }

   async function ajustarConteo() {
      const turno = document.getElementById('ajusteInput').value.trim().toUpperCase();

      // Validar formato de turno (ej: A01, B99, J99)
      const turnoRegex = /^[A-J]\d{2}$/;
      if (!turno || !turnoRegex.test(turno)) {
         alert('Por favor ingresa un turno válido en formato LetraNúmero (ej: A01, B05, J99)');
         document.getElementById('ajusteInput').focus();
         return;
      }

      if (confirm(`¿Ajustar el conteo al turno ${turno}?`)) {
         const url = '{{ route("ajuste", ":turno") }}'.replace(':turno', turno);

         // Mostrar loading en el botón
         const button = event.target;
         const originalText = button.innerHTML;
         button.innerHTML = `
            <div class="flex items-center justify-center gap-2">
                <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                <span>AJUSTANDO...</span>
            </div>
         `;
         button.disabled = true;

         //  setTimeout(() => {
         //     window.location.href = url;
         //  }, 800);

         try {
            // Enviar petición AJAX
            const response = await fetch(url, {
               method: 'GET',
               headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': '{{ csrf_token() }}',
                  'Accept': 'application/json'
               }
            });
            // console.log("response", response)

            // const data = await response.json();

            if (response.ok && response.statusText === 'OK') {
               // Mostrar notificación de éxito
               showNotification('Conteo ajustado exitosamente', 'success');

               // Actualizar interfaz si es necesario
               //    if (data.nuevoTurno) {
               //       document.getElementById('turno-lbl').textContent = data.nuevoTurno;
               //    }

               // Redireccionar después de 2 segundos
               // setTimeout(() => {
               //     window.location.reload();
               // }, 2000);

            } else {
               throw new Error(response.statusText || 'Error al reiniciar');
            }

         } catch (error) {
            // Mostrar error
            showNotification(error.message || 'Error en la conexión', 'error');

         } finally {
            // Restaurar botón
            button.disabled = false;
            button.classList.add('hover:-translate-y-1', 'hover:shadow-xl');
            button.innerHTML = originalText;
         }
      }
   }

   //    Función para repetir anuncio
   async function repetirAnuncio() {
      const btn = document.getElementById('repetirAnuncioBtn');
      const turno = document.getElementById('turno-lbl')?.textContent || '{{ $turno ?? "" }}';
      console.log("turno", turno)

      if (!turno) {
         showNotification('No tienes un turno asignado', 'warning');
         return;
      }

      // const originalText = btn.querySelector('.button-text').textContent;
      btn.disabled = true;
      btn.classList.add('loading');
      // btn.querySelector('.button-text').textContent = 'ENVIANDO...';

      try {
         // Enviar solicitud para repetir anuncio
         const response = await fetch('{{ route("repetir.anuncio") }}', {
            method: 'POST',
            headers: {
               'Content-Type': 'application/json',
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
               usuario: '{{ Auth::user()->name }}',
               turno: turno,
               caja: '{{ Auth::user()->name ?? "Caja 1" }}'
            })
         });

         const data = await response.json();

         if (data.success) {
            showNotification('Anuncio enviado para repetir', 'success');
         } else {
            showNotification(data.message || 'Error al repetir anuncio', 'error');
         }
      } catch (error) {
         console.error('Error:', error);
         showNotification('Error de conexión', 'error');
      } finally {
         setTimeout(() => {
            btn.disabled = false;
            btn.classList.remove('loading');
            // btn.querySelector('.button-text').textContent = originalText;
         }, 1500);
      }
   }

   //    Escuchar eventos de Pusher para confirmaciones
   const channel = pusher.subscribe('myCanal');
   channel.bind('anuncio-repetido', function(data) {
      if (data.usuario === '{{ Auth::user()->name }}') {
         showNotification('Anuncio repetido exitosamente', 'success');
      }
   });

   document.addEventListener("DOMContentLoaded", function() {

      var usuario = document.getElementById("usuario-lbl");
      var elemento = document.getElementById("turno-lbl");

      if (!elemento) {
         return;
      }

      setTimeout(() => {

         elemento.style.backgroundColor = "green";
         elemento.style.color = "white";

      }, 2000);

      elemento.style.backgroundColor = "red";
      elemento.style.color = "white";


      // -----------------

      const turnoForm = document.getElementById('turnoForm');
      const submitBtn = document.getElementById('submitBtn');

      if (turnoForm && submitBtn) {
         const buttonImage = submitBtn.querySelector('.button-image');

         turnoForm.addEventListener('submit', function(e) {
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;

            const buttonText = submitBtn.querySelector('.button-text');
            const originalText = buttonText.textContent;
            buttonText.textContent = 'PROCESANDO...';

            setTimeout(() => {
               submitBtn.classList.remove('loading');
               submitBtn.disabled = false;
               buttonText.textContent = originalText;
            }, 3000);
         });

         submitBtn.addEventListener('mouseenter', () => {
            if (buttonImage) {
               buttonImage.style.transform = 'rotate(5deg)';
            }
         });

         submitBtn.addEventListener('mouseleave', () => {
            if (buttonImage) {
               buttonImage.style.transform = 'rotate(0deg)';
            }
         });
      }

      // Permitir Enter en el input de ajuste
      const ajusteInput = document.getElementById('ajusteInput');
      if (ajusteInput) {
         ajusteInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
               ajustarConteo();
            }
         });
      }

      // Optimizar iframe
      const iframe = document.getElementById('iframeTurnos');
      if (iframe) {
         iframe.onload = function() {
            console.log('Iframe cargado');
         };
      }
   });
   </script>
</body>

</html>