<?php

    namespace App\Models;

    use MF\Model\Model;

    class Usuario extends Model{
        private $id, $nome, $email, $senha;

        public function __get($atributo){
            return $this->$atributo;
        }
        public function __set($atributo, $valor){
            $this->$atributo = $valor;
        }

        // cadastrar]
        public function cadastrar(){
            $query = "INSERT INTO usuarios(nome, email, senha)VALUES(:nome, :email, :senha)";
            $stmt = $this->db->prepare($query);

            $stmt->bindValue(':nome', $this->__get('nome'));
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':senha', $this->__get('senha')); //md5

            $stmt->execute();

            return $this;
        }

        // validar
        public function validarCadastro(){
            $valido = True;
            
            if(strlen($this->__get('nome')) < 3){
                $valido = False;
            }
            if(strlen($this->__get('email')) < 3){
                $valido = False;
            }
            if(strlen($this->__get('senha')) < 3){
                $valido = False;
            }

            return $valido;
        }

        // recuperar
        public function getUsuarioPorEmail(){
            $query = "SELECT nome, email FROM usuarios WHERE email=:email";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        // logar
        public function autenticar(){
            $query = "SELECT id, nome, email FROM usuarios WHERE email=:email AND senha=:senha";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':senha', $this->__get('senha'));
            $stmt->execute();

            $usuario =  $stmt->fetch(\PDO::FETCH_ASSOC);

            if($usuario['id'] != '' && $usuario['nome'] != ''){
                $this->__set('id', $usuario['id']);
                $this->__set('nome', $usuario['nome']);
            }

            return $this;
        }
    }

?>