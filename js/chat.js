var chat = {

    msgRequest:null,
    userRequest:null,
    lastTime : '',

    updateLastTime: function(lt) {
        lastTime = lt;
    },

    sendMessage: function(currentTab, msg, type) {

        fetch(BASE + 'ajax/send_message', {
            body: 'q=add&receiver_id='+currentTab+'&msg='+msg+'&type='+type,
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*',
                'Content-type': 'application/x-www-form-urlencoded'
            }
        })
        .then((res) => res.json())
        .then((data) => {
            chat.updateLastTime(data.datemsg);
            for(var i in data.msgs) {
                chat.insertMessage(data.msgs);
            }
        })
        
    },
    
    sendPhoto:function(currentTab, photo) {

        let fd = new FormData();
        fd.append('receiver_id', currentTab);
        fd.append('photo', photo);

        fetch(BASE + 'ajax/send_photo', {
            body: fd,
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain, */*'
            }
        })
        .then((res) => res.json())
        .then((data) => {
            if(data.error == "1") {
                alert(data.errorMsg);
            }
        })

    },
    
    insertMessage : function(msg) {
        
        var chatui = document.querySelector("#chat");
        var userId = document.querySelector("#userId").value;
        var room_id = document.querySelector("#room").value;
        
        if(msg.type =="welcome" || msg.type =="bye") {
            if(msg.type=="bye") {
                if(msg.sender_id == userId) {
                    window.location.href = BASE + 'room/exit/'+room_id+'/1';
                }
                else {
                    var div = document.querySelector(`[user-id=${CSS.escape(msg.sender_id)}]`);
                    room.removeUserTab(div);
                }
            }            
            room.updateUser();
        }

        if(msg.sender_id == room.myId || msg.receiver_id == room.myId || msg.receiver_id == 0) {            
            var sender = msg.sender_id;
            var receiver = msg.receiver_id;
            var hour = msg.datemsg.split(" ");
            var small = document.createElement("span");
            small.innerHTML = '<small">' + hour[1] + '</small>';            
            var li = document.createElement("li");
            li.classList.add("list-item-all");            
            li.appendChild(small);
            if(receiver == 0) {
                li.style.backgroundColor = "rgb(173,216,230)";
                var span = document.createElement("span");
                span.innerHTML = "<strong>" + " " + msg.nicksender + "</strong>" + " para todos:<br />" + msg.msg;
                li.appendChild(span);
            }
            else {
                if(msg.type=="text") {
                    if(msg.type != "bye") {
                        li.style.backgroundColor = "rgb(135,206,250)";
                    }
                    else {
                        li.style.backgroundColor = "rgb(220,53,69)";
                    }
                    var spantext = document.createElement("span");
                    spantext.innerHTML = '<strong>' + " " + msg.nicksender + '</strong> (reservadamente) para ' + msg.nickreceiver + ':<br />' + msg.msg;
                    li.appendChild(spantext);
                }
                else if(msg.type=="image") {
                    li.style.backgroundColor = "rgb(0,191,255)";
                    var span = document.createElement("span");
                    var texto = document.createElement("span");
                    texto.innerHTML = "<strong>" + " " + msg.nicksender + "</strong> (reservadamente) para " + msg.nickreceiver + ":<br />";
                    span.appendChild(texto);                    
                    li.appendChild(span);
                    var img = document.createElement("img");
                    img.src = BASE+'media/uploads/'+msg.msg;
                    img.style.width = "auto";
                    img.style.height = "300px";
                    li.appendChild(img);
                }                
            }
            chatui.appendChild(li);            
            var numLi = document.querySelector("#chat").getElementsByTagName("li");
            if(numLi.length > 5) {
                let liFirst = document.querySelector('#chat');
                liFirst.removeChild(liFirst.getElementsByTagName('li')[0]);
            }            
            chatui.scrollTop = chatui.scrollHeight;
            document.querySelector('#msg').focus;
        }
               
    },
    
    chatActivity:function() {

        fetch(BASE + 'ajax/get_messages?last_time=' + this.lastTime)
        .then(response => response.json()) 
        .then(json => {
            chat.updateLastTime(json.last_time);
            for(var i in json.msgs) {                        
                chat.insertMessage(json.msgs[i]);
            }                    
            chat.chatActivity();
        })
        .catch(err => console.log('Erro ao recuperar mensagens', err));
        
    },
    
    userActivity : function() {

        var room_id = document.querySelector("#room").value;
        var userId = document.querySelector("#userId").value;
        
        fetch(BASE + 'ajax/get_minutes?room_id=' + room_id)
        .then(response => response.json()) 
        .then(json => {
            if(json.length > 0) {                
                for(var i = 0; i < json.length; i++) {                    
                    if(json[i] == userId) {
                        window.location.href = BASE + 'room/exit/'+room_id+'/1';
                    }
                }
            }
        })
        .catch(err => console.log('Erro de solicitação', err));

    }
    
};
