var room = {
  
    userID : '',
    userTabs : [],
    currentTab : '',
    myNick : '',    
    myId : '',
    users : [],

    loadUserList : function() {
        userID = document.querySelector('#userId').value;
        userList = document.querySelector('#userList');
        room_id = document.querySelector('#room').value;
        userList.innerHTML = "";

        return new Promise(function(resolve, reject) {
        
            var fd = new FormData();
            let xhr = new XMLHttpRequest();
            xhr.open('POST',  BASE + 'room/list/'+room_id);
            xhr.onload = function() {
                if(this.status >= 200 && this.status < 300) {
                    resolve(xhr.response);
                    var json = JSON.parse(xhr.response);
                    for(let i in json.info) {
                        let div = document.createElement("div");
                        var spanNick = document.createElement("span");               
                        spanNick.innerHTML = json.info[i].nickname;
                        div.appendChild(spanNick);
                        div.style.cursor = "pointer";                         
                        div.setAttribute("user-id", json.info[i].id);           
                        if(json.info[i].id !== userID) {
                            div.classList.add("nick");
                            div.onclick = function() {
                                room.addUserTab(div.getAttribute("user-id"), json.info[i].nickname);
                            };
                            let user = {
                                'id':json.info[i].id,
                                'nickname':json.info[i].nickname
                            };
                            room.users.push(user);
                        }
                        else {
                            div.classList.add("meu-nick");                        
                            room.myNick = json.info[i].nickname;
                            room.myId = json.info[i].id;                            
                            var spanIcons = document.createElement("span");
                            spanIcons.innerHTML = " (Meu nick) ";
                            spanNick.appendChild(spanIcons);
                            let user = {
                                'id':json.info[i].id,
                                'nickname':json.info[i].nickname
                            };
                            room.users.push(user);
                        }                        
                        userList.appendChild(div);
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

    },
    
    updateUser : function() {
        document.querySelector("#userList").innerHTML = "";        
        setTimeout(function() {
            room.loadUserList();
        }, 1000);        
    },

    setUserTab : function(divUser) {
        var divs = document.getElementsByClassName("tab");        
        for(var i = 0; i < divs.length; i++) {
            divs[i].classList.remove("current-tab");
        }        
        divUser.classList.add("current-tab");
        this.currentTab = divUser.getAttribute("id");        
        var msg = document.querySelector('#msg');
        msg.focus();
        
    },
    
    removeUserTab : function(div) {
        
        var msg = document.querySelector('#msg');
        msg.value = "";
        div.classList.remove("current-tab");        
        var divUser = document.getElementsByClassName("tab")[0];
        divUser.classList.add("current-tab");
        this.currentTab = divUser.getAttribute("id");
        var id = div.getAttribute("user-id"); 
        let index = this.userTabs.indexOf(id);
        this.userTabs.splice(index, 1);
        var divtab = document.getElementById(id);
        divtab.remove();        
        setTimeout(function() {
            divUser.click();
        }, 100);
         
    },

    addUserTab : function(id, name) {
        if(this.userTabs.indexOf(id) == -1) {            
            this.userTabs.push(id);            
            var divUser = document.createElement("div");
            var spanName = document.createElement("span");
            divUser.classList.add("tab");            
            divUser.setAttribute("id", id);
            divUser.classList.add("tab");
            spanName.innerHTML = name;            
            divUser.appendChild(spanName);
            var span = document.createElement("span");
            span.setAttribute("span-id", id);
            span.title = "Fechar aba";
            span.innerHTML = "X";
            span.style.float = "right";
            span.style.zIndex = "100";
            span.style.position = "relative";
            span.style.top = "-10px";
            span.style.right = '-10px';
            divUser.appendChild(span);
            chatList.append(divUser);
            this.setUserTab(divUser);
            span.onclick = function() {
                room.removeUserTab(divUser);              
            };
            divUser.onclick = function(e) {
                room.setUserTab(this);
            };
        }        
    },

    disconnectUsers : function() {

        fetch(BASE + 'ajax/disconnect_users')
        .then(response => response.json()) 
        .then(json => {
            chat.chatActivity();
        })
        .catch(err => console.log('Erro no método disconnectUsers', err));
        
    }
    
};

window.onload = function() {
        
    chat.sendMessage(0, "Entrei na sala...", "welcome");        
    setTimeout(function() {
        chat.chatActivity();
    }, 1500);
    setInterval(function() {
        chat.userActivity();
    }, 1100*60*60*5);
    room.currentTab = 0;    
    room.loadUserList();
    room.userTabs.push(0);
    var div = document.createElement("div");    
    div.innerHTML = "Todos";
    div.id = 0;
    div.classList.add("tab", "current-tab");
    var chatList = document.querySelector("#chatList");
    chatList.appendChild(div);
    div.onclick = function() {
        room.setUserTab(div);
    };

    var msg = document.querySelector("#msg");
    msg.focus();

    var setPictureBtn = document.querySelector('#setPictureBtn');
    var photo = document.querySelector("#photo");
    setPictureBtn.onclick = function() {
        if(room.currentTab == 0) {
            var texto = document.querySelector('#texto');
            texto.innerHTML = "Imagens não podem ser enviadas para todos os usuários.\nSelecione um usuário antes de enviar a imagem";
            openModal();
        }
        else {
            photo.click();
        }
    };
    
    var photo = document.querySelector('#photo');
    photo.onchange = function(e) {
        chat.sendPhoto(room.currentTab, e.target.files[0]);
    };
    
    msg.onkeyup = function(e) {
        if(e.keyCode == 13) {
            if(msg.value.trim() != '') {                
                msg.focus;
                chat.sendMessage(room.currentTab, msg.value, "text");
                msg.value = "";
            }
        }     
    };
    
    setInterval(function() {
        room.disconnectUsers();
    }, 10000);
    
};

function exit(warning) {
    chat.sendMessage(0, "Saindo da sala...", "bye");
    var room_id = document.querySelector("#room").value;
    setTimeout(function() {
        window.location.href= BASE + 'room/exit/'+room_id+'/'+warning;
    }, 250);
}