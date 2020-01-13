<?php

    namespace App\Controllers;

    use MF\Controller\Action;
    use MF\Model\Container;

    class AppController extends Action{
        public function timeline(){
                $this->validaAutenticacao();

                $tweet = Container::getModel('Tweet');
                $usuario = Container::getModel('Usuario');
                $usuarios_seguidores = Container::getModel('Usuarios_seguidores');

                $tweet->__set('id_usuario', $_SESSION['id']);
                $usuario->__set('id', $_SESSION['id']);
                $usuarios_seguidores->__set('id_usuario', $_SESSION['id']);

                $this->view->tweets = $tweet->getAll();
                $this->view->info_usuario = $usuario->getInfoUsuario();
                $this->view->total_tweets = $tweet->getTotalTweets();
                $this->view->total_seguindo = $usuarios_seguidores->getTotalSeguindo();
                $this->view->total_seguidores = $usuarios_seguidores->getTotalSeguidores();

                $this->render('timeline');
        }
        public function tweet(){
                $this->validaAutenticacao();

                $tweet = Container::getModel('Tweet');

                $tweet->__set('tweet', $_POST['tweet']);
                $tweet->__set('id_usuario', $_SESSION['id']);

                $tweet->cadastrar();

                header('Location: /timeline');
        }
        public function quemSeguir(){
            $this->validaAutenticacao();

            $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

            $usuarios = array();

            if($pesquisarPor != ''){
                $usuario = Container::getModel('Usuario');
                $usuario->__set('nome', $pesquisarPor);
                $usuario->__set('id', $_SESSION['id']);
                $usuarios = $usuario->getAll();
            }

            $this->view->usuarios = $usuarios;
            
            $this->render('quemSeguir');
        }

        public function acao(){
            $this->validaAutenticacao();

            $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
            $id_usuario_seg = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

            $usuarios_seguidores = Container::getModel('Usuarios_seguidores');
            $usuarios_seguidores->__set('id_usuario', $_SESSION['id']);

            if($_GET['acao'] == 'seguir'){
                $usuarios_seguidores->seguirUsuario($id_usuario_seg);
            }else if($_GET['acao'] == 'nao_seguir'){
                $usuarios_seguidores->deixarSeguirUsuario($id_usuario_seg);
            }

            header('location: /quem_seguir');

            // echo '<pre>';
            // print_r();
            // echo '</pre>';
        }
        
        public function removerTweet(){
            $this->validaAutenticacao();

            $tweet = Container::getModel('Tweet');
            $tweet->__set('id', $_GET['id_tweet']);
            $tweet->deletarTweet();

            header('location: /timeline');
        }

        public function validaAutenticacao(){
            session_start();

            if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == ''){
                header('Location: /?login=erro');
            }
        }
    }

?>