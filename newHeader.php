<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top" style="font-family: 'Chewy';">
<a class="navbar-brand" href="Homepage.php">
<img src="Imagens\Logo.png" alt="Logo Amik@"  width="170" height="80"></img>
</a>
<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>

  <div class="collapse navbar-collapse" id="navbarCollapse">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
      <?php 
        if(isset($_SESSION['loggedin'])){
                ?> 
        <a  class="nav-link" href="profile.php?search_result=<?php echo $_SESSION['user_id']; ?>" style="text-decoration:none;"> 
        <img src="imagens\profile.png" alt="Profile Icon" width="50" height="50" ></img>
        PERFIL
        </a>

        <?php } ?>
      </li>
      
      
      <li class="nav-item ">
                    <?php 
                            if(isset($_SESSION['loggedin'])){
                        ?> 
                                    <a  class="nav-link" href="logout.php" style="text-decoration:none;padding-right:50px;">
                                      <img src="imagens\Logout.png" alt="Logout Icon" width="50" height="50" ></img>
                                        SAIR
                                    </a>
                        <?php }

                        else{ ?> <!-- data-toggle="modal" data-target="#myModal"-->
                        <a   class="nav-link" href="Pagina_login.php"  style="width:auto;" style="text-decoration:none;">
                            <img src="imagens\login.png" alt="Login Icon" width="50" height="50" ></img>
                            ENTRAR
                        </a>
                        <?php 
                            } 
                        ?>  
      </li>
    </ul>
  </div>
</nav>
