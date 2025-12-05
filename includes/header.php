<nav>
    <div class="nav-left">
        <a href="index.php" class="logo">
            <i class="fab fa-youtube"></i>
            <span>YouTube</span>
        </a>
    </div>
    
    <div class="nav-middle">
        <form class="search-bar" action="search.php" method="GET">
            <input type="text" name="q" placeholder="Search">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>
    
    <div class="nav-right">
        <?php if(isset($_SESSION['user_id'])): ?>
            <div class="nav-icons">
                <a href="upload.php" class="nav-icon" title="Upload">
                    <i class="fas fa-video"></i>
                </a>
                <div class="user-profile" id="profileDropdown">
                    <i class="fas fa-user-circle"></i>
                    <div class="dropdown-menu">
                        <a href="account.php" class="dropdown-item">
                            <i class="fas fa-user"></i>
                            <span>My Account</span>
                        </a>
                        <a href="logout.php" class="dropdown-item">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Log out</span>
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <a href="login.php" class="login-btn">Sign In</a>
        <?php endif; ?>
    </div>
</nav>