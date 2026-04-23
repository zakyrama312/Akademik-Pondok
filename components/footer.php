</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Cari semua elemen sidebar, tombol menu, dan tombol close yang ada di halaman
        const sidebars = document.querySelectorAll('aside');
        const menuToggle = document.getElementById('menu-toggle');
        const closeBtns = document.querySelectorAll('.close-sidebar-btn');

        // Fungsi BUKA Sidebar
        if (menuToggle) {
            menuToggle.addEventListener('click', () => {
                sidebars.forEach(sidebar => {
                    sidebar.classList.remove('-translate-x-full');
                });
            });
        }

        // Fungsi TUTUP Sidebar
        closeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                sidebars.forEach(sidebar => {
                    sidebar.classList.add('-translate-x-full');
                });
            });
        });
    });
</script>
</body>

</html>