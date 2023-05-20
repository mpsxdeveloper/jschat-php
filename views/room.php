<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?=TITLE?> - Sala</title>        
        <link rel="stylesheet" type="text/css" href="<?=BASE?>/css/rooms.css" />
        <link rel="stylesheet" type="text/css" href="<?=BASE?>/css/button.css" />
        <link rel="stylesheet" type="text/css" href="<?=BASE?>/css/modal.css" />
    </head>
    
    <body style="margin: 0; padding: 0; background: rgb(240,255,240);">
        
        <input type="hidden" id="userId" value="<?=$_SESSION["id"]?>" />
        <input type="hidden" id="welcome" value="<?=$welcome?>" />
        <input type="hidden" id="room" value="<?=$roomId?>" />
        
        <div style="width: 100%; text-align: center; background-color: #00bfff; height: 50px; font-size: 32px; font-weight: bold; padding-top: 5px;">
            <span><?=TITLE?></span>
        </div>

        <div class="row">
            <div class="column left">
                <ul class="" id="chat" style="overflow-y: auto; max-height: 450px; min-height: 450px; margin: 0; padding: 0"></ul>
                <div id="chatList"></div>
                <input type="text" id="msg" style="border: none; outline: none; width: 100%;" maxlength="100" 
                    placeholder="Digite sua mensagem e pressione ENTER para enviar..." />
                <div class="progress mt-1" style="display: none;">
                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>                   
            </div>
            <div class="column right">
                <div class="users">
                    <div id="userList" style="overflow-y: auto; font-size: 13px; min-height: 450px;"></div>
                    <input type="file" id="photo" style="position: absolute; top: 0; left: -1000px;" />
                    <div class="col-6">
                        <button type="button" class="buttonblue" id="setPictureBtn" title="Enviar imagem">Imagem</button>
                        <button type="button" id="exitBtn" class="buttonred btn-sm" onclick="exit('0');" title="Sair da sala" style="float: right;">Sair</button>
                    </div>                    
                </div> 
            </div>
        </div>
        
        <div id="modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p id="texto"></p>
            </div>
        </div>

        <style>
            * {
                box-sizing: border-box;
            }
            .column {
                float: left;
                padding: 5px;
                min-height: 500px;
            }
            .left {
                width: 80%;
            }
            .right {
                width: 20%;
            }
            .row:after {
                content: "";
                display: table;
                clear: both;
            }
            input[type=text] {
                width: 100%;
                padding: 12px 20px;
                margin: 8px 0;
                display: inline-block;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
                font-weight: bold;
            }
        </style>
        
        <script src="<?=BASE?>js/config.js"></script>
        <script src="<?=BASE?>js/room.js"></script>
        <script src="<?=BASE?>js/chat.js"></script>
        <script src="<?=BASE?>js/modal.js"></script>
        
    </body>
    
</html>