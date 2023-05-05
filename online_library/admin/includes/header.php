<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">TABLEAU DE BORD</a>
            </li>

            <li>
                <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> CATEGORIES </i></a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="add-category.php">Ajouter une catégorie</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-categories.php">Gérer les catégories</a></li>
                </ul>
            </li>

            <li>
                <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> AUTEURS </i></a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="add-author.php">Ajouter un auteur</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-authors.php">Gérer les auteurs</a></li>
                </ul>
            </li>
        
            <li>
                <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> LIVRES </i></a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="add-book.php">Ajouter un livre</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-books.php">Gérer les livres</a></li>
                </ul>
            </li>
          
            <li>
                <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> SORTIES </i></a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="add-issue-book.php">Ajouter une sortie</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-issued-books.php">Gérer les sorties</a></li>
                </ul>
            </li> 
            <li class="nav-item">
                <a class="nav-link" href="reg-readers.php">LECTEURS</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="change-password.php">MODIFIER MOT DE PASSE</a>
            </li>
        </ul>   
    </div>
    
    <div class="right-div">
        <a href="logout.php" class="btn btn-danger pull-right">DECONNEXION</a>
    </div>   
</nav>