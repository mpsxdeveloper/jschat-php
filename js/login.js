function login() {
    
    var email = document.querySelector("#email");
    var password = document.querySelector("#password");
    var csrf_token = document.querySelector("#csrf_token");
    var fd = new FormData();
    var alerta = document.querySelector("#loginalerta");
    var mensagem = document.querySelector("#loginalerta .mensagem");
    if(email.value.trim() === "") {
        mensagem.innerHTML = "Informe o e-mail";
        alerta.style.display = "block";
        email.focus();
        return;
    }
    else if(password.value === "") {
        mensagem.innerHTML = "Informe a senha";
        alerta.style.display = "block";
        password.focus();
        return;
    }
    else {
        fd.append("email", email.value);
        fd.append("password", password.value);
        fd.append("csrf_token", csrf_token.value);
        return new Promise(function(resolve, reject) {
        
            let xhr = new XMLHttpRequest();
            xhr.open('POST',  BASE + 'login/login');
            xhr.onload = function() {
                if(this.status >= 200 && this.status < 300) {
                    resolve(xhr.response);
                    if(xhr.response === 'false') {
                        alerta.innerHTML = "E-mail e/ou senha incorretos";
                        alerta.style.display = "block";
                    }
                    else {
                        window.location.href = BASE + 'room';
                    }
                }
                else {
                    reject({
                        status: this.status,
                        statusText: xhr.statusText
                    });
                }
            };
            xhr.onerror = function() {
                reject({
                  status: this.status,
                  statusText: xhr.statusText
                });
            };
            xhr.send(fd);
        
        });
    }

}

function add() {
    
    var email = document.querySelector("#nemail");
    var nick = document.querySelector("#nnick");
    var password = document.querySelector("#npassword");
    var csrf_token = document.querySelector("#csrf_token");
    var fd = new FormData();
    var alerta = document.querySelector("#addalerta");
    var mensagem = document.querySelector("#addalerta .mensagem");
    if(email.value.trim() === "") {        
        mensagem.innerHTML = "Informe um e-mail";
        alerta.style.display = "block";
        email.focus();
        return;
    }
    else if(nick.value.trim() === "") {        
        mensagem.innerHTML = "Informe um apelido";
        alerta.style.display = "block";
        nick.focus();
        return;
    }
    else if(password.value === "") {        
        mensagem.innerHTML = "Informe uma senha";
        alerta.style.display = "block";
        password.focus();
        return;
    }
    else {        
        fd.append("email", email.value);
        fd.append("nick", nick.value);
        fd.append("password", password.value);
        fd.append("csrf_token", csrf_token.value);
        return new Promise(function(resolve, reject) {
        
            let xhr = new XMLHttpRequest();
            xhr.open('POST',  BASE + 'user/add');
            xhr.onload = function() {
                if(this.status >= 200 && this.status < 300) {
                    resolve(xhr.response);
                    if(xhr.response === 'true') {
                        mensagem.innerHTML = "Cadastro realizado com sucesso. Pode fazer o login!";
                        alerta.style.display = "block";
                        alerta.style.backgroundColor = 'blue';
                        return; 
                    }
                    else if(xhr.response === 'false') {                        
                        mensagem.innerHTML = "Erro ao fazer cadastro";
                        alerta.style.display = "block";
                        return;                       
                    }
                    else if(JSON.parse(xhr.response) === "uninvited") {                       
                        mensagem.innerHTML = "E-mail não consta na lista de convites";
                        alerta.style.display = "block";
                        return;                       
                    }
                    else if(JSON.parse(xhr.response) === "already") {                       
                        mensagem.innerHTML = "E-mail já está registrado no chat";
                        alerta.style.display = "block";
                        return;                       
                    }
                }
                else {
                    reject({
                        status: this.status,
                        statusText: xhr.statusText
                    });
                }
            };
            xhr.onerror = function() {
                reject({
                  status: this.status,
                  statusText: xhr.statusText
                });
            };
            xhr.send(fd);
        
        });
    }

}