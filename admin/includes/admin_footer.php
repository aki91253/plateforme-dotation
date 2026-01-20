            </main>
        </div>
    </div>

    <script>
        // Update page title based on current page
        const pageTitles = {
            'index': 'Tableau de bord',
            'products': 'Gestion des dotations',
            'stock': 'Gestion du stock',
            'requests': 'Gestion des demandes'
        };
        
        const currentPage = '<?= $currentPage ?>';
        if (pageTitles[currentPage]) {
            document.getElementById('page-title').textContent = pageTitles[currentPage];
        }

        // Sidebar toggle functionality
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const hamburgerBtn = document.getElementById('hamburger-btn');
        const hamburgerIcon = document.getElementById('hamburger-icon');
        const closeIcon = document.getElementById('close-icon');
        const mainContent = document.querySelector('.main-content');

        let sidebarOpen = window.innerWidth >= 1024; // Open by default on desktop

        function updateIcons() {
            if (sidebarOpen) {
                hamburgerIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
            } else {
                hamburgerIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            }
        }

        function openSidebar() {
            sidebarOpen = true;
            sidebar.classList.remove('collapsed');
            sidebar.classList.add('open');
            mainContent.classList.remove('sidebar-collapsed');
            
            // Mobile: show overlay
            if (window.innerWidth < 1024) {
                overlay.classList.remove('opacity-0', 'pointer-events-none');
                overlay.classList.add('opacity-100', 'pointer-events-auto');
                document.body.style.overflow = 'hidden';
            }
            
            updateIcons();
        }

        function closeSidebar() {
            sidebarOpen = false;
            sidebar.classList.add('collapsed');
            sidebar.classList.remove('open');
            mainContent.classList.add('sidebar-collapsed');
            
            // Mobile: hide overlay
            overlay.classList.add('opacity-0', 'pointer-events-none');
            overlay.classList.remove('opacity-100', 'pointer-events-auto');
            document.body.style.overflow = '';
            
            updateIcons();
        }

        // Toggle sidebar on hamburger click
        hamburgerBtn.addEventListener('click', () => {
            if (sidebarOpen) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });

        // Close sidebar when clicking overlay (mobile only)
        overlay.addEventListener('click', closeSidebar);

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                // Close overlay on desktop
                overlay.classList.add('opacity-0', 'pointer-events-none');
                overlay.classList.remove('opacity-100', 'pointer-events-auto');
                document.body.style.overflow = '';
            }
        });

        // Close sidebar when clicking a link on mobile
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    closeSidebar();
                }
            });
        });

        // Initialize icons on page load
        updateIcons();
    </script>
</body>
</html>
