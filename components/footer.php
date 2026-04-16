</div>


<script>
    // Script untuk toggle sidebar di HP
    const btn = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');

    if (btn) {
        btn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    }
</script>
</body>

</html>