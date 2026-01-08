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
    </script>
</body>
</html>
