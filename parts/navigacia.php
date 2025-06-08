<nav class="main-nav">
    <!-- ***** Logo Start ***** -->
    <a href="index.php" class="logo">
        <img src="assets/images/dota2-icon-2048x2026-7cnarvw2.png" alt="" style="width: 158px;">
    </a>
    <!-- ***** Logo End ***** -->

    <!-- ***** Menu Start ***** -->
    <ul class="nav">
        <li><a href="index.php">Domov</a></li>
        <li><a href="shop.php" class="active">Naš obchod</a></li>
        <li><a href="product-details.php">Naše produkty</a></li>
        <li><a href="contact.php">Kontakt</a></li>

        <li>
            <a href="QnA.php">
                <?= (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ? 'QnA (admin)' : 'QnA' ?>
            </a>
        </li>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <li><a href="admin_users.php">Admin Panel</a></li>
        <?php endif; ?>

        <?php if (isset($_SESSION['username'])): ?>
            <li>
                <a href="#" id="username" onclick="toggleLogout()">
                    <?= $_SESSION['role'] === 'admin'
                        ? 'admin: ' . htmlspecialchars($_SESSION['username'])
                        : htmlspecialchars($_SESSION['username']) ?>
                </a>
                <div id="logout-button" style="display:none;">
                    <a href="logout.php" class="logout-btn">Odhlasit sa</a>
                </div>
            </li>
        <?php else: ?>
            <li><a href="login.php" class="login-btn">Prihlasiť sa</a></li>
        <?php endif; ?>
    </ul>

    <a class="menu-trigger">
        <span>Menu</span>
    </a>
    <!-- ***** Menu End ***** -->
</nav>

<script>
    function toggleLogout() {
        var logoutButton = document.getElementById('logout-button');
        logoutButton.style.display = (logoutButton.style.display === 'none' || logoutButton.style.display === '') ? 'block' : 'none';
    }
</script>