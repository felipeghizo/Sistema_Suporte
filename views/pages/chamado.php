<?php
    $token = $_GET["token"];
?>
<h2> Visualizando o chamado: <?php echo $token; ?></h2>

<hr>

<h3>Pergunta do suporte: <?php echo $info["pergunta"] ?></h3>


<?php
    $puxarInteracoes = \MySql::conectar()->prepare("SELECT * FROM interacao_chamado WHERE id_chamado = ?");
    echo "<hr>";

    $puxarInteracoes->execute(array($token));
    $puxarInteracoes = $puxarInteracoes->fetchAll();
    foreach ($puxarInteracoes as $key => $value) {
        if($value["admin"] == 1){
            echo "<p><b>Admin: </b>".$value["mensagem"]."</p>";
        } else {
            echo "<p><b>Você: </b>".$value["mensagem"]."</p>";
        }
        echo "<hr>";
    }

    ?>

    <?php

        if(isset($_POST["responder_chamado"])){
            $mensagem = $_POST["mensagem"];
            $sql = \MySql::conectar()->prepare("INSERT INTO interacao_chamado VALUES (null,? ,? ,?, 0)");
            $sql->execute(array($token,$mensagem,-1));

            echo "<script> alert('Sua resposta foi enviada, aguarde o adm responde-la')</script>";
            echo '<script> location.href="'.BASE.'chamado?token='.$token.'"</script>';
            die();
        }

        $sql = \MySql::conectar()->prepare("SELECT * FROM interacao_chamado WHERE id_chamado = ? ORDER BY id DESC");
        $sql->execute(array($token));
        if($sql->rowCount() == 0){
            echo "Aguarde a resposta do admin para continuar com o suporte a sua chamada.";
        } else {
            $info = $sql->fetchAll();
            if($info[0]["admin"] == -1){
                echo "Aguarde a resposta do admin para continuar com o suporte a sua chamada.";
            } else {
                echo "<form method='post'>
                    <textarea name='mensagem' placeholder='Sua resposta...'></textarea>
                    <br />
                    <input type='submit' name='responder_chamado' value='Enviar' /> 
                    </form>";
            }
        }
    ?>

