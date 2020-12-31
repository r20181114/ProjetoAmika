<?php

session_start();
include('header.php');
include "config.php";


$posts = [];

$post_query = "SELECT u.user_id, u.nome, p.POST_ID, p.title, DATE(p.data) as data, p.imagem, p.text
              FROM posts as p, utilizadores as u
              WHERE p.id_user = u.user_id
              ORDER BY DATE(p.data) DESC;";

$result = mysqli_query($mysqli, $post_query);

$resultCheck = mysqli_num_rows($result);

//if there is 1 or more results, save them in $posts
if ($resultCheck > 0) {
  while ($found = mysqli_fetch_assoc($result)) {
    $posts[] = $found;
  }
  //if not, show a message saying that no results were found
} else {
  echo "No results were found :(";
}

?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Fórum</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script>

  <link rel="stylesheet" type="text/css" href="CSS/Amik@.css">
  <link rel="stylesheet" type="text/css" href="CSS/forum.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css" integrity="sha512-TQQ3J4WkE/rwojNFo6OJdyu6G8Xe9z8rMrlF9y7xpFbQfW5g8aSWcygCQ4vqRiJqFsDsE1T6MoAOMJkFXlrI9A==" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
</head>

<body>

  <?php
  if (isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
  }

  if (isset($_SESSION['msg_pass'])) {
    echo $_SESSION['msg_pass'];
    unset($_SESSION['msg_pass']);
  }
  ?>

  <div style="background: linear-gradient(#ffff00,#ffd769); width: 25%; margin-top:50px; border-radius: 25px; padding: 5px;" class="center">
    <h1 style="font-family: 'Chewy'; text-align: center; color: #03036B; font-size: 48px; ">
      Fórum
    </h1>
  </div>
  <br>
  <br>
  <br>
  <div class="container">
    <button id="addpost" class="btn-grad" style="margin:auto;">+</button>
  </div>



  <div class="blog-feed">
    <?php foreach ($posts as $post) : ?>
      <div class="blog-post-comment">

        <div class="blog-post">
          <div class="blog-post__img">
            <img <?php echo 'src="data:image/jpeg;base64,' . base64_encode($post['imagem']) . '"' ?>>
          </div>
          <div class="blog-post__info">
            <div class="blog-post__date">
              <span><?php
                    setlocale(LC_TIME, 'pt', 'pt.utf-8', 'pt.utf-8', 'portuguese');
                    date_default_timezone_set('Europe/Lisbon');
                    $date_string = utf8_encode(strftime('%A, %d de %B de %Y', strtotime($post['data'])));
                    echo $date_string;

                    ?></span>
            </div>

            <h1 class="blog-post__title"><?php echo $post['title']; ?></h1>

            <p class="blog-post__text">
              <?php echo $post['text']; ?>
              <br><br>
              <b style="font-size:15px;">Publicado por:</b>
              <?php echo $post['nome']; ?>
            </p>

            <a href="javascript:;" class="comentar blog-post__cta">Comentar</a>

            <form action="comentario.php?postId=<?php echo $post['POST_ID'] ?>" method="post">
              <div class="comment_form_wrapper" style="display: none;">
                <input type="text" name="comentario" class="form-control rounded-corner" style="font-size:17px;" placeholder="Escreve um comentário...">
                <input type="hidden" name="data_com" value="<?php echo date("Y-m-d H:i:s"); ?>">
                <span class="input-group-btn p-l-10">
                  <input class="btn btn-primary" style="font-size:15px;" value="Submeter" id="submit" type="submit" onclick="javascript:window.location.reload(true)">
                </span>
              </div>
            </form>
          </div>

        </div>

        <div class="blog-post__comment">

          <?php

          $postid = $post['POST_ID'];

          $com_query = "SELECT * FROM comentarios WHERE POST_ID='$postid'";
          $com_result = mysqli_query($mysqli, $com_query) or die(mysqli_error($mysqli));
          $resultCheck = mysqli_num_rows($com_result);

          if ($resultCheck > 0) {

            $comentarios = [];
            while ($com = mysqli_fetch_assoc($com_result)) {
              $comentarios[] = $com;
              $nocomments = 0;
            }
          } else {
            $nocomments = 1;
          }

          ?>

          <h1 class="blog-post__title">Comentários:</h1>
          <hr>

          <p class="blog-post__text">

            <?php if ($nocomments == 0) foreach ($comentarios as $comentario) :
              $user_com = $comentario['ID_USER'];

              $user_query = "SELECT nome FROM utilizadores WHERE user_id='$user_com'";

              $user_result = mysqli_query($mysqli, $user_query) or die(mysqli_error($mysqli));

              while ($un = mysqli_fetch_assoc($user_result)) {
                $username_com = $un;
              } ?>


              <p> <?php echo $comentario['COMENTARIO']; ?> </p>

              <div style="font-size:13px;">
                <b>Publicado por:</b>
                <?php echo $username_com['nome']; ?>
              </div>
              <?php echo $comentario['DATA']; ?>
              <hr>

            <?php endforeach; ?>

            <?php if ($nocomments == 1) : ?>
              Ainda ninguém comentou esta publicação :(
            <?php endif; ?>

          </p>
        </div>
      </div>
    <?php endforeach; ?>

  </div>


  <!-- POPUP ADICIONAR POST -->

  <div class="modal fade" id="adicionarPost" tabindex="-1" role="dialog" style="font-size:17px;" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="font-size:17px;">
        <div class="modal-header text-center">
          <button type="button" class="close" data-dismiss="modal" style="font-size:22px;" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title w-100" style="font-size:27px; font-family: 'Chewy'; color: #03036B;">Adiciona uma publicação!</h4>
        </div>
        <div class="modal-body">
          <form id="novaPublicacao" method="POST" action="new_post.php" enctype="multipart/form-data" autocomplete="off">

            <div class="form-group col-sm-10">
              <label><b>Título:</b></label>
              <input type="text" class="form-control" name="titulo" id="titulo" style="font-size:17px;">
            </div>

            <div class="form-group col-sm-10">
              <label><b>Data:</b></label>
              <div class="input-group input-group-lg">
                <input type="text" class="form-control" style="font-size:17px;" id="datepicker1" name="date">
                <div class="input-group-append">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
              </div>
            </div>


            <div class="form-group col-sm-10">
              <label><b>Descrição:</b></label>
              <textarea class="form-control" type="text" rows="6" placeholder="Escreve qualquer coisa sobre este dia..." name="texto" style="font-size:17px;"></textarea>
            </div>

            <div class="form-group col-sm-10">
              <label><b>Foto:</b></label>
              <input type="file" name="foto">
            </div>

            <div class="modal-footer">
              <button type="submit" id="submit" name="submit" form="novaPublicacao" class="btn-grad" style="font-size:15px;">Publicar</button>
              <button type="button" class="btn btn-danger" style="font-size:15px; border-radius: 50px; text-transform:uppercase;" data-dismiss="modal">Cancelar</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>


  <script>
    $('#addpost').on('click', function() {
      $('#adicionarPost').modal('show');
    });


    $(document).ready(function() {
      $('#datepicker1').datepicker({
        format: "dd-mm-yyyy",
        language: "pt",
        autoclose: true
      });

      $('.comentar').click(function() {
        $('.comment_form_wrapper').toggle();
      });
    });
  </script>

</body>


</html>