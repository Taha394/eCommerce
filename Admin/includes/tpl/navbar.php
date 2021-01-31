<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php"><?php echo lang('Home_Admin'); ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="app-nav">
            <ul class="navbar-nav mr-auto">
                </li> <a class="nav-link" href="cateogries.php"><?php echo lang('Categories') ?></a></li>
                </li> <a class="nav-link" href="items.php"><?php echo lang('Items') ?></a></li>
                </li> <a class="nav-link" href="members.php"><?php echo lang('Members') ?></a></li>
                </li> <a class="nav-link" href="comment.php"><?php echo lang('COMMENTS') ?></a></li>

            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Taha
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="../index.php" target="_blank"><?php echo lang('VISIT SHOP'); ?></a>
                        <a class="dropdown-item" href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>"><?php echo lang('Edit-Profile'); ?></a>
                        <a class="dropdown-item" href="#"><?php echo lang('Settings'); ?></a>
                        <a class="dropdown-item" href="logout.php"><?php echo lang('Logout'); ?></a>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</nav>