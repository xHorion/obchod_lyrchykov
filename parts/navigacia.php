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
        <li><a href="QnA.php">QnA</a></li>

        <?php if (isset($_SESSION['username'])): ?>
            <!-- Якщо користувач авторизований, показуємо ім'я -->
            <li>
                <a href="#" id="username" onclick="toggleLogout()"><?= htmlspecialchars($_SESSION['username']) ?>!</a>
                <div id="logout-button" style="display:none;">
                    <a href="logout.php" class="logout-btn">Odhlasit sa</a>
                </div>
            </li>
        <?php else: ?>
            <!-- Якщо користувач не авторизований, показуємо кнопку для входу -->
            <li><a href="login.php" class="login-btn">Prihlasiť sa</a></li>
        <?php endif; ?>
    </ul>

    <a class="menu-trigger">
        <span>Menu</span>
    </a>
    <!-- ***** Menu End ***** -->
</nav>

<script>
    // Функція для перемикання кнопки logout при натисканні на ім'я користувача
    function toggleLogout() {
        var logoutButton = document.getElementById('logout-button');
        logoutButton.style.display = (logoutButton.style.display === 'none' || logoutButton.style.display === '') ? 'block' : 'none';
    }
</script>