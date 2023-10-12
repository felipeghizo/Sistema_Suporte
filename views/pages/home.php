<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
            //Import PHPMailer classes into the global namespace
            //These must be at the top of your script, not inside a function
            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\Exception;
    ?>

    <h2> Abrir chamado!</h2>

    <?php
        if(isset($_POST["acao"])){
            $email = $_POST["email"];
            $pergunta = $_POST["pergunta"]; 
            $token = md5(uniqid());
            $sql = \MySql::conectar()->prepare("INSERT INTO chamados VALUES (null,?,?,?)");
            $sql->execute(array($pergunta, $email, $token));
            //Enviar email para o usuário:
            //Load Composer's autoloader

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
                $mail->Subject = 'Seu chamado foi aberto!';
                $url = BASE."chamado?token=".$token;
                $informacoes = 
                "Olá! Seu chamado foi criado com sucesso!<br />
                Utilize o link abaixo para interagir:<br /> 
                <a href='$url'>Acessar chamdo</a>";
                $mail->Body    = $informacoes;

                $mail->send();
                echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
            /*Fim do envio de email*/

            echo "<script> alert('deu bom familia!!!') </script>";
        }
    ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Seu email"/>
        <br/>
        <textarea name="pergunta" placeholder="Qual sua pergunta?"></textarea>
        <br/>
        <input type="submit" name="acao" value="enviar"/>
    </form>
</body>
</html>