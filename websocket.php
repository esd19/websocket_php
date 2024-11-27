<?php
require 'vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

echo"server en linea";

class WebSocketServer implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Guardar la conexión cuando se abre
        $this->clients->attach($conn);
        echo "Nueva conexión: {$conn->resourceId}\n";
    
        // Enviar un solo mensaje a todos los clientes conectados
        $message = "<p>¡Hola a todos! </p>";
        foreach ($this->clients as $client) {
            $client->send($message);
        }
    }
    public function onMessage(ConnectionInterface $from, $msg) {
        echo "Mensaje recibido: $msg\n";
    
        // Usamos un switch para decidir qué hacer según el mensaje
        switch ($msg) {
            case "1":
                $responseMessage = "Pregunta 1";
                break;
            case "2":
                $responseMessage = "Pregunta 2";
                break;
            case "3":
                $responseMessage = "Pregunta 3";
                break;
            case "4":
                $responseMessage = "Pregunta 4";
                break;
            case "5":
                $responseMessage = "Pregunta 5";
                break;
            default:
                $responseMessage = "Mensaje no reconocido.";
                break;
        }
    
        foreach ($this->clients as $client) {
            $client->send($responseMessage);
        }
        
    }
    

    public function onClose(ConnectionInterface $conn) {
        // Eliminar la conexión cuando se cierra
        $this->clients->detach($conn);
        echo "Conexión cerrada: {$conn->resourceId}\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }

    
}

$app = new Ratchet\App('0.0.0.0', 3000, );
$app->route('/ws', new WebSocketServer, array('*'));
$app->run();


