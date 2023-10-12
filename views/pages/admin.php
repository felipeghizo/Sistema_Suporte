<?php
            //Import PHPMailer classes into the global namespace
            //These must be at the top of your script, not inside a function
            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\Exception;
    ?>
<?php
    if(isset($_POST["responder_novo_chamado"])){
        $token = $_POST["token"];
        $email = $_POST["email"];
        $mensagem = $_POST["mensagem"];

        $sql = \MySql::conectar()->prepare("INSERT INTO interacao_chamado VALUES (null, ?, ?, ?, 1)");
        $sql->execute(array($token, $mensagem, 1));

         //Create an instance; passing `true` enables exceptions
         $mail = new PHPMailer(true);

         try {
             //Server settings
             $mail->SMTPDebug = 0;                      //Enable verbose debug output
             $mail->isSMTP();                                            //Send using SMTP
             $mail->Host       = 'smtp.office365.com';                     //Set the SMTP server to send through
             $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
             $mail->Username   = 'ghizoni71@gmail.com';                     //SMTP username
             $mail->Password   = '#Z5a6qryifelipe007jj';                               //SMTP password
             $mail->SMTPSecure = "sll";            //Enable implicit TLS encryption
             $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

             //Recipients
             $mail->setFrom('ghizoni71@gmail.com', 'felipe01');
             $mail->addAddress($email, '');     //Add a recipient

             //Content
             $mail->isHTML(true);                                  //Set email format to HTML
             $mail->CharSet = "UTF-8";
             $mail->Subject = 'Nova interação no seu chamado!'.$token;
             $url = BASE."chamado?token=".$token;
             $informacoes = 
             "Olá! Nova interação no seu chamado!<br />
             Utilize o link abaixo para interagir:<br /> 
             <a href='$url'>Acessar chamdo</a>";
             $mail->Body    = $informacoes;

             $mail->send();
             echo 'Message has been sent';
         } catch (Exception $e) {
             echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
         }
         /*Fim do envio de email*/

        echo "<script>alert('Você respondeu o usuário!')</script>";
    }else if(isset($_POST["responder_nova_interacao"])){
        $token = $_POST["token"];
        $email = $_POST["email"];
        $mensagem = $_POST["mensagem"];
        $email = \MySql::conectar()->prepare("SELECT * FROM chamados WHERE token = ?");
        $email->execute(array($token));
        $email = $email->fetch()["email"];

        \MySql::conectar()->exec("UPDATE interacao_chamado SET status = 1 WHERE id = $_POST[id]");

        $sql = \MySql::conectar()->prepare("INSERT INTO interacao_chamado VALUES (null, ?, ?, 1, 1)");
        $sql->execute(array($token, $mensagem));

          //Create an instance; passing `true` enables exceptions
          $mail = new PHPMailer(true);

          try {
              //Server settings
              $mail->SMTPDebug = 0;                      //Enable verbose debug output
              $mail->isSMTP();                                            //Send using SMTP
              $mail->Host       = 'smtp.office365.com';                     //Set the SMTP server to send through
              $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
              $mail->Username   = 'ghizoni71@gmail.com';                     //SMTP username
              $mail->Password   = '#Z5a6qryifelipe007jj';                               //SMTP password
              $mail->SMTPSecure = "sll";            //Enable implicit TLS encryption
              $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
 
              //Recipients
              $mail->setFrom('ghizoni71@gmail.com', 'felipe01');
              $mail->addAddress($email, '');     //Add a recipient
 
              //Content
              $mail->isHTML(true);                                  //Set email format to HTML
              $mail->CharSet = "UTF-8";
              $mail->Subject = 'Nova interação no seu chamado!'.$token;
              $url = BASE."chamado?token=".$token;
              $informacoes = 
              "Olá! Nova interação no seu chamado!<br />    
              Utilize o link abaixo para interagir:<br /> 
              <a href='$url'>Acessar chamdo</a>";
              $mail->Body    = $informacoes;
 
              $mail->send();
              echo 'Message has been sent';
          } catch (Exception $e) {
              echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
          }
          /*Fim do envio de email*/
        echo "<script>alert('Você respondeu o usuário!')</script>";
    }

?>

<h2>Novos chamados: </h2>
<?php

    $pegarChamados = \MySql::conectar()->prepare("SELECT * FROM chamados ORDER BY id DESC");
    $pegarChamados->execute();
    $pegarChamados = $pegarChamados->fetchAll();
    foreach($pegarChamados as $key=>$value){
        $verificaInteracao = \MySql::conectar()->prepare("SELECT * FROM interacao_chamado WHERE id_chamado = '$value[token]'");
        $verificaInteracao->execute();
        if($verificaInteracao->rowCount() >= 1){
            continue;
        }
?>
    <form method="POST">
        <h2><?php echo $value["pergunta"]; ?></h2>
        <textarea name="mensagem" placeholder="Sua resposta..."></textarea>
        <br>
        <input type="submit" name="responder_novo_chamado" value="Responder"/>
        <input type="hidden" name="token" value="<?php echo $value['token']; ?>"/>
        <input type="hidden" name="email" value="<?php echo $value['email']; ?>"/>
    </form>
<?php  } ?>
 
<hr>

<h2>Última interações: </h2>
<?php

    $pegarChamados = \MySql::conectar()->prepare("SELECT * FROM interacao_chamado WHERE admin = -1 AND status = 0 ORDER BY id DESC");
    $pegarChamados->execute();
    $pegarChamados = $pegarChamados->fetchAll();
    foreach($pegarChamados as $key=>$value){
?>
    <h2><?php echo $value["mensagem"]; ?></h2>
    <p>Clique <a href="<?php BASE ?>chamado?token=<?php echo $value['id_chamado']; ?>"> aqui </a> para ver este chamado!</p>
    <form method="POST">
        <textarea name="mensagem" placeholder="Sua resposta..."></textarea>
        <br>
        <input type="submit" name="responder_nova_interacao" value="Responder"/>
        <input type="hidden" name="id" value="<?php echo $value['id']; ?>"/>
        <input type="hidden" name="token" value="<?php echo $value['id_chamado']; ?>"/>
    </form>
<?php  } ?>
 
<hr>