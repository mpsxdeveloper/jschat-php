<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?=TITLE?> - Salas</title>
        <link rel="stylesheet" type="text/css" href="<?=BASE?>/css/home.css" />
        <link rel="stylesheet" type="text/css" href="<?=BASE?>/css/button.css" />
        <link rel="stylesheet" type="text/css" href="<?=BASE?>/css/notification.css" />
        <link rel="stylesheet" type="text/css" href="<?=BASE?>/css/modal.css" />        
    </head>
    
    <body style="background: rgba(0, 0, 128, 0.2); margin: 0; padding: 0;">
        
        <div>
            <div style="width: 100%; text-align: center; background-color: #00bfff; height: 50px;">
                <span style="font-size: 32px; font-weight: bold; padding-top: 5px;"><?=TITLE?></span>
                <div style="float: right; margin-top: 15px;">
                    <span><strong>Olá, <?=$_SESSION["nickname"]?></strong></span>&nbsp;
                    <a href="<?=BASE?>login/logout" class="buttonred">Sair&nbsp;</a>
                    <a href="<?=BASE?>user/settings" class="buttonblue">Configurações&nbsp;</a>
                </div>
            </div>                        
        </div>

        <div class="row" style="margin-top: 5%;">
            <?php foreach ($info as $room): ?>
                <?php if($room["total"] < 30): ?>
                    <a href="<?= BASE ?>room/show/<?=$room["id"]?>" class="notification">
                        <span><?= $room["description"] ?></span>
                        <span class="badge"><?= $room["total"] ?></span>
                    </a> 
                <?php else: ?>
                    <a href="<?= BASE ?>room/show/<?=$room["id"]?>" class="notification notification-full">
                        <span><?= $room["description"] ?></span>
                        <span class="badge"><?= $room["total"] ?></span>
                    </a> 
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <div id="modal" class="modal">            
            <div class="modal-content">
                <span class="close">&times;</span>
                <p id="texto"></p>
            </div>
        </div>

    <script src="<?=BASE?>js/config.js"></script>
    <script src="<?=BASE?>js/modal.js"></script>
    
    <?php if($warning == "1"): ?>
        <script>
           window.onload = function() {
                var texto = document.querySelector('#texto');
                texto.innerHTML = "Você ficou muito tempo sem enviar mensagens e por isso foi desconectado da sala";
                openModal();
            };
        </script>
    <?php endif; ?>
        
    </body>
    
</html>