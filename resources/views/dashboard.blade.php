<x-app-layout>
   <x-slot name="header">
      <div
         class="relative overflow-hidden bg-gradient-to-br from-guinda-principal to-guinda-secundario rounded-xl shadow-guinda">
         <div
            class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-gris-claro via-white to-gris-claro opacity-30">
         </div>
         <h2 class="relative py-6 text-6xl font-bold text-center text-white font-zapf">
            {{ Auth::user()->name }}
         </h2>
      </div>
   </x-slot>

   <div class="py-4 px-2">
      @if(Auth::user()->name == 'Admin')
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
         <!-- Panel principal del iframe -->
         <div class="lg:col-span-2 bg-white rounded-2xl shadow-xl overflow-hidden h-[70vh] lg:h-[72vh]">
            <div class="flex flex-col h-full">
               <!-- Header del iframe -->
               <div
                  class="flex items-center justify-between px-4 py-3 bg-gradient-guinda border-b border-guinda-secundario/20">
                  <div class="flex items-center gap-2">
                     <div class="w-3 h-3 rounded-full bg-red-400"></div>
                     <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                     <div class="w-3 h-3 rounded-full bg-green-400"></div>
                  </div>
                  <span class="text-lg font-zapf font-bold text-white">Sistema de Turnos</span>
                  <div class="w-6"></div>
               </div>

               <!-- Contenedor del iframe -->
               <div class="flex-1 overflow-hidden relative">
                  <div style="width: 100%; height: 100%; overflow: hidden;">
                     <iframe src="https://turnos.gomezpalacio.gob.mx/"
                        style="width: 150%; height: 150%; transform: scale(0.67); transform-origin: 0 0;" scrolling="no"
                        id="iframeTurnos">
                     </iframe>
                  </div>
               </div>
            </div>
         </div>

         <!-- Panel de controles -->
         <div class="space-y-6">
            <!-- Panel de reinicio -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gris-claro/50">
               <div
                  class="px-4 py-3 bg-gradient-to-r from-guinda-principal/10 to-guinda-secundario/5 border-b border-guinda-principal/20">
                  <h3 class="text-lg font-bold text-gris-cool font-zapf">Control de Conteo</h3>
               </div>
               <div class="p-6">
                  <div class="space-y-4">
                     <div class="flex items-center gap-3 mb-6">
                        <div
                           class="flex-shrink-0 w-10 h-10 rounded-full bg-warning/20 flex items-center justify-center">
                           <svg class="w-5 h-5 text-warning" fill="currentColor" viewBox="0 0 20 20">
                              <path fill-rule="evenodd"
                                 d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z"
                                 clip-rule="evenodd" />
                           </svg>
                        </div>
                        <p class="text-sm text-gris-cool font-avenir">Reinicia el contador de turnos actual (Turno 1)
                        </p>
                     </div>

                     <button onclick="ejecutarReinicio(this)" type="button"
                        class="w-full px-6 py-4 bg-gradient-to-r from-guinda-principal to-guinda-secundario hover:from-guinda-principal/90 hover:to-guinda-secundario/90 text-white font-bold font-zapf rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                           <path fill-rule="evenodd"
                              d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                              clip-rule="evenodd" />
                        </svg>
                        <span>REINICIAR CONTEO</span>
                        <span
                           class="absolute inset-0 bg-white/20 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
                     </button>
                  </div>
               </div>
            </div>

            <!-- Panel de ajuste -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gris-claro/50">
               <div
                  class="px-4 py-3 bg-gradient-to-r from-guinda-principal/10 to-guinda-secundario/5 border-b border-guinda-principal/20">
                  <h3 class="text-lg font-bold text-gris-cool font-zapf">Ajuste Manual</h3>
               </div>
               <div class="p-6">
                  <div class="space-y-4">
                     <div class="flex items-center gap-3 mb-4">
                        <div
                           class="flex-shrink-0 w-10 h-10 rounded-full bg-guinda-principal/20 flex items-center justify-center">
                           <svg class="w-5 h-5 text-guinda-principal" fill="currentColor" viewBox="0 0 20 20">
                              <path
                                 d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                           </svg>
                        </div>
                        <p class="text-sm text-gris-cool font-avenir">Ingresa el número de turno deseado (tiene que ser
                           mayor al actual)</p>
                     </div>

                     <div class="space-y-3">
                        <div class="relative">
                           <input type="number" id="ajusteInput" min="0" placeholder="Ej: 125"
                              class="w-full px-4 py-3 pl-12 text-lg font-bold text-center font-avenir border-2 border-gris-cool/30 rounded-xl focus:border-guinda-principal focus:ring-2 focus:ring-guinda-principal/20 transition-all duration-300"
                              onfocus="this.select()">
                           <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                              <svg class="w-5 h-5 text-gris" fill="currentColor" viewBox="0 0 20 20">
                                 <path fill-rule="evenodd"
                                    d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z"
                                    clip-rule="evenodd" />
                              </svg>
                           </div>
                        </div>

                        <button onclick="ajustarConteo()"
                           class="w-full px-6 py-4 bg-gradient-to-r from-guinda-principal to-guinda-secundario hover:from-guinda-principal/90 hover:to-guinda-secundario/90 text-white font-bold font-zapf rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex items-center justify-center gap-2">
                           <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                              <path fill-rule="evenodd"
                                 d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                 clip-rule="evenodd" />
                           </svg>
                           AJUSTAR CONTEO
                        </button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      @else
      <div class="mx-auto max-w-4xl">
         <div
            class="overflow-hidden bg-white rounded-2xl shadow-xl transition-all duration-300 hover:shadow-2xl border border-gris-claro">
            <div class="p-8">
               <!-- Display del turno actual -->
               @isset($turno)
               <div class="turno-display pulse-animation">
                  <span class="turno-label">Tu turno asignado</span>
                  <div class="turno-number font-black font-zapf" id="turno-lbl">{{ $turno }}</div>
               </div>
               @endisset

               <!-- Formulario -->
               <div class="user-form hidden">
                  <form method="post" action="{{ route('turnos') }}" id="turnoForm">
                     @csrf
                     <div class="form-group">
                        <label for="name" class="form-label">USUARIO</label>
                        <input type="text" name="user" class="form-control" id="name" value="{{ Auth::user()->name }}"
                           readonly>
                     </div>
                  </form>
               </div>

               <!-- Botón para tomar turno -->
               <div class="text-center py-0">
                  <button type="submit" form="turnoForm" class="turno-button" id="submitBtn">
                     <img src="{{ asset('/img/btn-rojo.png') }}" alt="Tomar turno" class="button-image">
                     <span class="button-text">TOMAR TURNO</span>
                  </button>
               </div>
            </div>
         </div>
      </div>
      @endif
   </div>

   @push('scripts')
   <script>
   // Event listeners que deben estar dentro del DOMContentLoaded
   document.addEventListener('DOMContentLoaded', function() {
      // const turnoForm = document.getElementById('turnoForm');
      // const submitBtn = document.getElementById('submitBtn');

      // if (turnoForm && submitBtn) {
      //    const buttonImage = submitBtn.querySelector('.button-image');

      //    turnoForm.addEventListener('submit', function(e) {
      //       submitBtn.classList.add('loading');
      //       submitBtn.disabled = true;

      //       const buttonText = submitBtn.querySelector('.button-text');
      //       const originalText = buttonText.textContent;
      //       buttonText.textContent = 'PROCESANDO...';

      //       setTimeout(() => {
      //          submitBtn.classList.remove('loading');
      //          submitBtn.disabled = false;
      //          buttonText.textContent = originalText;
      //       }, 3000);
      //    });

      //    submitBtn.addEventListener('mouseenter', () => {
      //       if (buttonImage) {
      //          buttonImage.style.transform = 'rotate(5deg)';
      //       }
      //    });

      //    submitBtn.addEventListener('mouseleave', () => {
      //       if (buttonImage) {
      //          buttonImage.style.transform = 'rotate(0deg)';
      //       }
      //    });
      // }

      // // Permitir Enter en el input de ajuste
      // const ajusteInput = document.getElementById('ajusteInput');
      // if (ajusteInput) {
      //    ajusteInput.addEventListener('keypress', function(e) {
      //       if (e.key === 'Enter') {
      //          ajustarConteo();
      //       }
      //    });
      // }

      // // Optimizar iframe
      // const iframe = document.getElementById('iframeTurnos');
      // if (iframe) {
      //    iframe.onload = function() {
      //       console.log('Iframe cargado');
      //    };
      // }
   });
   </script>
   @endpush
</x-app-layout>