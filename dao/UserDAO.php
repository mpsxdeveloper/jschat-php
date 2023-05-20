<?php

class UserDAO {
    
    public function getUser($id) {
        
        try {
            $user = new User();
            $connection = ConnectionFactory::connect();
            $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":id", $id);
            $rs->execute();
            if($rs->rowCount() > 0) {
                $row = $rs->fetch(PDO::FETCH_ASSOC);
                $user->setId($row["id"]);
                $user->setNickname($row["nickname"]);
                return $user;
            }
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return null;
        
    }
    
    public function getUserByNick($nickname) {
        
        try {
            $user = new User();
            $connection = ConnectionFactory::connect();
            $sql = "SELECT * FROM users WHERE nickname = :nickname";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":nickname", $nickname);
            $rs->execute();
            if($rs->rowCount() > 0) {                
                return $user;
            }
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return false;
        
    }
    
    public function addnewuser(User $user) {        
        
        try {
            $connection = ConnectionFactory::connect();
            $sql = "INSERT INTO users (email, nickname, password) VALUES (:email, :nickname, :password)";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":email", $user->getEmail());
            $rs->bindValue(":nickname", $user->getNickname());
            $rs->bindValue(":password", $user->getPassword());
            $rs->execute();
            if($rs->rowCount() > 0) {
                return true;
            }
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return false;
        
    }
    
    public function login(User $u) {
        try {
            $connection = ConnectionFactory::connect();
            $sql = "SELECT * FROM users WHERE email = :email";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":email", $u->getEmail());
            $rs->execute();
            if($rs->rowCount() > 0) {
                $row = $rs->fetch(PDO::FETCH_OBJ);
                $user = new User();
                if(password_verify($u->getPassword(), $row->password)) {
                    $list = array(
                        'user' => array()
                    );
                    $user->setPassword("");
                    $user->setId($row->id);
                    $user->setEmail($row->email);
                    $user->setNickname($row->nickname);
                    $list["user"] = $user;                    
                    return $list;
                }
                else {
                    return null;
                }
            }
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return null;
    }
    
    public function atualizarSenha(Usuario $u, $novasenha) {
        try {
            $connection = ConnectionFactory::connect();
            $sql = "SELECT senha FROM usuarios WHERE id = :id";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":id", $u->getId());
            $rs->execute();
            if($rs->rowCount() > 0) {
                $row = $rs->fetch(PDO::FETCH_ASSOC);
                if(password_verify($u->getSenha(), $row["senha"])) {
                    $sql = "UPDATE usuarios SET senha = :senha WHERE id = :id";
                    $rs = $connection->prepare($sql);
                    $rs->bindValue(":senha", $novasenha);
                    $rs->bindValue(":id", $u->getId());
                    $rs->execute();                   
                    return true;  
                }
                else {
                    return "senhaincorreta";
                }
            }
            else {
                return null;
            }
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return null;
    }    
      
    public function checkEmail($email) {
        try {
            $connection = ConnectionFactory::connect();
            $sql = "SELECT email FROM users WHERE email = :email";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":email", $email);
            $rs->execute();
            if($rs->rowCount() > 0) {
                return true;   
            }
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return false;
    }
    
    public function checkEmailInvitation($email) {
        try {
            $connection = ConnectionFactory::connect();
            $sql = "SELECT id FROM invites WHERE email = :email";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":email", $email);
            $rs->execute();
            if($rs->rowCount() > 0) {
                return true;   
            }
        }
        catch(PDOException $e) {
            $e->getMessage();echo $e;
        }
        return false;
       
    }
    
    public function getUsersByRoom($room_id) {        
        
        $list = array();
        try {            
            $connection = ConnectionFactory::connect();
            $sql = "SELECT DISTINCT u.id, u.nickname FROM users u 
                   INNER JOIN users_rooms ur ON ur.user_id = u.id 
                   WHERE ur.room_id = :room_id";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":room_id", $room_id);
            $rs->execute();
            if($rs->rowCount() > 0) {
                while($row = $rs->fetch(PDO::FETCH_OBJ)) {
                    $user = new User();
                    $user->setId($row->id);
                    $user->setNickname($row->nickname);                    
                    array_push($list, $user);
                }
            }            
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return $list;
    }
    
    public function addUserRoom($room_id, $user_id) {
        
        try {
            $connection = ConnectionFactory::connect();
            $sql = "INSERT INTO users_rooms (user_id, room_id) VALUES (:room_id, :user_id)";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":room_id", $room_id);
            $rs->bindValue(":user_id", $user_id);
            $rs->execute();
            return true;
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return false;
    }
    
    public function getUsuarios($id = 0) {
        
        try {
            $connection = ConnectionFactory::connect();
            $sql = "SELECT id, nome FROM usuarios WHERE id <> :id ORDER BY id 
                    DESC LIMIT 5";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":id", $id);
            $rs->execute();
            $lista = array();
            if($rs->rowCount() > 0) {
                while($row = $rs->fetch(PDO::FETCH_OBJ)) {
                    $usuario = new Usuario();
                    $usuario->setId($row->id);
                    $usuario->setNome($row->nome);
                    array_push($lista, $usuario);
                }
            }
            return $lista;
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return null;
        
    }
    
    public function pesquisar($id, $nome) {
        
        try {
            $connection = ConnectionFactory::connect();
            $sql = "SELECT id, nome FROM usuarios WHERE id <> :id AND nome LIKE :nome LIMIT 5";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":id", $id);
            $rs->bindValue(":nome", "%".$nome."%");
            $rs->execute();
            $lista = array();
            if($rs->rowCount() > 0) {
                while($row = $rs->fetch(PDO::FETCH_OBJ)) {
                    $usuario = new Usuario();
                    $usuario->setId($row->id);
                    $usuario->setNome($row->nome);
                    array_push($lista, $usuario);
                }
                return $lista;
            }            
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return null;
        
    }
    
    public function getUsuario($id) {
        
        try {
            $connection = ConnectionFactory::connect();
            $sql = "SELECT * FROM usuarios WHERE id = :id";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":id", $id);
            $rs->execute();
            $usuario = new Usuario();
            if($rs->rowCount() > 0) {
                while($row = $rs->fetch(PDO::FETCH_OBJ)) {                    
                    $usuario->setId($row->id);
                    $usuario->setNome($row->nome);
                    $usuario->setSenha($row->senha);                    
                }
                return $usuario;                               
            }
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return null;
    }
    
    public function atualizarNome(Usuario $usuario) {
        
        try {
            $connection = ConnectionFactory::connect();
            $sql = "UPDATE usuarios SET nome = :nome WHERE id = :id";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":nome", $usuario->getNome());
            $rs->bindValue(":id", $usuario->getId());
            $rs->execute();
            return true;            
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return false;
    }
    
    public function getEmailFromInvitations($email) {        
        
        try {
            $connection = ConnectionFactory::connect();
            $sql = "SELECT id FROM invites WHERE email = :email";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":email", $email);            
            $rs->execute();
            if($rs->rowCount() > 0) {
                return true;
            }
            else {
                return false;
            }
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        return false;
        
    }
    
    public function inviteFriend($email) {        
        
        try {
            $connection = ConnectionFactory::connect();
            $sql = "INSERT INTO invites (email) VALUES (:email)";
            $rs = $connection->prepare($sql);
            $rs->bindValue(":email", $email);            
            $rs->execute();
            if($rs->rowCount() > 0) {
                return true;
            }
            else {
                return false;
            }
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
        
    }
    
}