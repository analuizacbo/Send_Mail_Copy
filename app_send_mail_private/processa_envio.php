<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    //print_r($_POST);

    class Mensagem{
        private $para = null;
        private $assunto = null;
        private $mensagem = null;
        private $status = ['codigo_status' => null, 'descricao_status' => ''];

        public function __get($atributo){
           return $this->$atributo;
        }

        public function __set($atributo, $valor){
            $this->$atributo = $valor;
        }

        public function mensagemValida(){
            if(empty($this->para) || empty($this->assunto)|| empty($this->mensagem)){
                return false;
            }
            return true;
        }
    }

        $mensagem = new Mensagem();

        $mensagem->__set('para',$_POST['para']);
        $mensagem->__set('assunto',$_POST['assunto']);
        $mensagem->__set('mensagem',$_POST['mensagem']);

        //echo'<br>';
        //print_r($mensagem);
        
        if(!$mensagem->mensagemValida()){
            echo 'Mensagem não é válida';
            //die();//mata o processamento do script do ponto em que a instrução é lida, o que entrar aqui na condicao if o resto será descartado
            header('Location: index.php');
        }

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = false;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'seuEmail@gmail.com';                 // SMTP username
            $mail->Password = '*******';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('seuEmail@gmail.com', 'remetente');
            $mail->addAddress($mensagem->__get('para'));     // Add a recipient
            //$mail->addAddress('ellen@example.com');//adicionando mais destinatários
            //$mail->addReplyTo('info@example.com', 'Information');//adiciona um contato padrão caso o destinatário resolva responder o remetente
            //$mail->addCC('cc@example.com');//destinatários adixionados em cópia 
            //$mail->addBCC('bcc@example.com');//cópia oculta

            //Attachments//adicionando anexos ao e-mail
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content//configurações de conteúdo do e-mail
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $mensagem->__get('assunto');
            $mail->Body    = $mensagem->__get('mensagem');//conteúdo do email com tag htm
            $mail->AltBody = 'É necessário usar um client que suporte HTML para ter acesso total ao conteúdo dessa mensagem';//conteúdo do email sem tag htm

            $mail->send();

            $mensagem->status['codigo_status'] = 1;
            $mensagem->status['descricao_status'] = 'E-mail enviado com sucesso!';

            echo 'Epmail enviado com sucesso!';
        } catch (Exception $e) {
            $mensagem->status['codigo_status'] = 2;
            $mensagem->status['descricao_status'] = 'Nao foi possível enviar esse e-mail. Tente novamente mais tarde!' . $mail->ErrorInfo;

            //Alguma logica que armazene o erro para ser tratado posteriormente
        }
   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<body>
    <div class="container">
            <div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>

            <div class="row">
                <div class="col-md-12">
                    <? if($mensagem->status['codigo_status']==1){ ?>

                        <div class="container">
                            <h1 class="display-4 text-sucess">Sucesso</h1>
                            <p><?= $mensagem->status['descricao_status'] ?></p>
                            <a href="index.php" class="btn btn-sucess btn-lg mt-5px text-white">Voltar</a>
                        </div>
                    <?} ?>}


                    <? if($mensagem->status['codigo_status']==2){ ?>

                        <div class="container">
                            <h1 class="display-4 text-danger">Ops, algo deu errado!</h1>
                            <p><?= $mensagem->status['descricao_status'] ?></p>
                            <a href="index.php" class="btn btn-sucess btn-lg mt-5px text-white">Voltar</a>
                        </div>

                        <?} ?>}

                </div>
            </div>
    </div>
</body>
</html>