   <!-- Footer Pour Toutes les pages -->
    
    </main>
    <footer class="py-6 mt-auto border-t border-gray-200 text-sm text-gray-500">
        <div class="max-w-6xl mx-auto px-5 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="assets/img/logo.png" alt="Logo" class="h-7">
                <span><strong class="text-gray-700">Réseau Canopé</strong> | <a href="policy.php" class="text-gray-500 no-underline hover:text-canope-green transition-colors">Politique de Confidentialité</a></span>
            </div>
            <div class="flex items-center gap-5">
                <a href="savoir_plus.php" class="text-gray-500 no-underline hover:text-canope-green transition-colors">Savoir Plus</a>
                <a href="support.php" class="text-gray-500 no-underline hover:text-canope-green transition-colors">Support</a>
                
                <!-- Icones des Sociaux -->
                <div class="flex items-center gap-2 ml-2">
                    <!-- LinkedIn -->
                    <a href="https://www.linkedin.com/showcase/reseau-canope-corse" target="_blank"
                       class="w-8 h-8 bg-blue-700 rounded-lg flex items-center justify-center hover:bg-blue-800 transition-all hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-4 h-4 text-white" viewBox="0 0 24 24">
                            <path d="M4.98 3.5C4.98 4.88 3.87 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1 4.98 2.12 4.98 3.5zM0 8h5v16H0V8zm7.5 0h4.8v2.2h.07c.67-1.27 2.3-2.6 4.73-2.6 5.06 0 6 3.33 6 7.67V24h-5v-7.83c0-1.87-.03-4.28-2.61-4.28-2.61 0-3.01 2.04-3.01 4.14V24h-5V8z"/>
                        </svg>
                    </a>

                    <!-- Facebook -->
                    <a href="https://www.facebook.com/canopecorse/?_rdr" target="_blank"
                       class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-700 transition-all hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 text-white" viewBox="0 0 24 24">
                            <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.99 3.66 9.12 8.44 9.88v-6.99h-2.54V12h2.54v-1.54c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.23.19 2.23.19v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99C18.34 21.12 22 16.99 22 12z"/>
                        </svg>
                    </a>

                    <!-- YouTube -->
                    <a href="https://www.youtube.com/@crdpcorse" target="_blank">
                      <div
                        style="clip-path: url(#squircleClip)"
                        class="w-8 h-8 bg-gradient-to-br from-red-600 to-red-800 rounded-lg flex flex-col items-center justify-center shadow-lg border border-red-500/50 cursor-pointer transform transition-all duration-300 ease-out hover:scale-110 hover:-translate-y-2 hover:shadow-2xl"
                      >
                        <!-- Logo triangle -->
                        <svg viewBox="0 0 24 22" fill="currentColor" class="w-5 h-5 text-white mb-1" xmlns="http://www.w3.org/2000/svg">
                          <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                      </div>
                    </a>

  </div>
</div>

</div>

            </div>
        </div>
        <!-- Bouton Scroll To Top -->
<button 
  id="scrollToTopBtn"
  class="fixed right-4 bottom-4 z-50 w-14 h-14 rounded-full bg-canope-slate text-white flex items-center justify-center shadow-lg border-2 border-canope-dark hover:bg-gradient-to-r hover:from-canope-stale hover:to-canope-teal hover:border-canope-dark active:scale-90 transition-all duration-300"
  title="Remonter en haut">
  <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
  </svg>
</button>

<script>
  document.getElementById('scrollToTopBtn').addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
</script>
 <div class="mt-8 pt-6 border-t border-border text-center text-sm text-muted-foreground">
              <p>
                © 2026<br>
                Réseau Canopé – Corse. Tous droits réservés.
              </p>
            </div>
          </div>
    </footer>
    <script src="assets/js/main.js"></script>
</body>
</html>
