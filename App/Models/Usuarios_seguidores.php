<?php

    namespace App\Models;

    use MF\Model\Model;

    class Usuarios_seguidores extends Model{
        private $id, $id_usuario, $id_usuario_seguindo;

        public function __get($atributo){
            return $this->$atributo;
        }
        public function __set($atributo, $valor){
            $this->$atributo = $valor;
        }

        // seguir usuario
        public function seguirUsuario($seguindo){
            $query = "INSERT INTO usuarios_seguidores(id_usuario, id_usuario_seguindo) VALUES(:id_usuario, :id_usuario_seguindo)";
            $stmt = $this->db->prepare($query);

            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->bindValue(':id_usuario_seguindo', (int)$seguindo);
            $stmt->execute();

            return true;
        }
        
        // deixar de seguir
        public function deixarSeguirUsuario($seguindo){
            $query = "DELETE FROM usuarios_seguidores WHERE id_usuario = :id_usuario AND id_usuario_seguindo = :id_usuario_seguindo";
            $stmt = $this->db->prepare($query);

            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->bindVAlue(':id_usuario_seguindo', (int)$seguindo);
            $stmt->execute();

            return $this;
        }
    }

?>