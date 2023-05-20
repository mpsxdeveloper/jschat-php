<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title><?=TITLE?> - Home</title>
        <link rel="stylesheet" type="text/css" href="<?=BASE?>/css/home.css" />
        <link rel="stylesheet" type="text/css" href="<?=BASE?>/css/alert.css" />        
    </head>
    
    <?php
        if(!isset($_SESSION["csrf_token"])) {
            $_SESSION["csrf_token"] = md5(time() . rand(0, 9999));
        }
        if(!isset($_SESSION["owner"])) {
            $addr = filter_input(INPUT_SERVER, "REMOTE_ADDR");
            $agent = filter_input(INPUT_SERVER, "HTTP_USER_AGENT");
            $_SESSION["owner"] = md5($addr . $agent);
        }
    ?>
    
    <body style="background: rgba(0, 0, 128, 0.2);">       
        
        <div class="row" style="margin-top: 45px;">
            <div style="text-align: center;">
                <h1 style="margin-bottom: 0; font-size: 42px; padding-bottom: 50px;"><?=TITLE?></h1>                
            </div>
            <div class="column">
                <input type="hidden" id="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                <label for="email" class="form-label">E-mail</label>
                <input type="email" id="email" placeholder="Informe o e-mail" maxlength="45" />
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" placeholder="Informe a senha" maxlength="60" />                    
                <button type="button" onclick="login();" style="background-color: #008CBA;">Login</button>                    
                <div class="alert" id="loginalerta">
                    <span class="mensagem"></span><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                </div>
            </div>
            <div class="column" style="float: right;">
                <label for="nemail">E-mail</label>
                <input type="email" id="nemail" placeholder="Informe o e-mail" maxlength="45" />
                <label for="nnick">Apelido</label>
                <input type="text" id="nnick" placeholder="Informe um apelido" maxlength="17" />
                <label for="npassword">Senha</label>
                <input type="password" id="npassword" placeholder="Informe uma senha" maxlength="60" />
                <button type="button" onclick="add();">Cadastrar</button>
                <div class="alert" id="addalerta">
                    <span class="mensagem"></span><span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                </div>
            </div>
        </div>
        
        <script src="<?=BASE?>js/config.js"></script>
        <script src="<?=BASE?>js/login.js"></script>

    </body>
    
</html>
