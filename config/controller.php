<?php
    include_once('humans.php');

    class Controller
    {
        private $conn;
        private $method;
        private $id;
        private $humans;


        public function __construct($conn, $method, $id)
        {
            $this->conn = $conn;
            $this->method = $method;
            $this->id = $id;
            $this->humans = new Humans($conn);
        }

        public function humansController()
        {
            switch ($this->method) {
                case 'GET':
                    if ($this->id) {
                        $response = $this->getHuman($this->id);
                    } else {
                        $response = $this->getAllHumans();
                    }
                    break;
                case 'POST':
                    $response = $this->createHuman();
                    break;
                case 'PUT':
                    $response = $this->updateHuman($this->id);
                    break;
                case 'DELETE':
                    $response = $this->deleteHuman($this->id);
                    break;
                default:
                    break;
            }
            header($response['status_code_header']);
            if ($response['body']) {
                echo $response['body'];
            }
        }

        private function getHuman($id)
        {
            $result = $this->humans->find($id);
            if (!$result) {
                return $this->notFoundResponse();
            }
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            return $response;
        }

        private function getAllHumans()
        {
            $result = $this->humans->findAll();
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            return $response;
        }

        private function createHuman()
        {
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (!$this->validateHuman($input)) {
                return $this->invalidInputResponse();
            }
            $this->humans->insert($input);
            $response['status_code_header'] = 'HTTP/1.1 201 Created';
            $response['body'] = json_encode([
                'error' => 'Created'
            ]);
            return $response;
        }

        private function updateHuman($id)
        {
            $result = $this->humans->find($id);
            if (!$result) {
                return $this->notFoundResponse();
            }
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (!$this->validateHuman($input)) {
                return $this->invalidInputResponse();
            }
            $this->humans->update($id, $input);
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode([
                'error' => 'Updated'
            ]);
            return $response;
        }

        private function deleteHuman($id)
        {
            $result = $this->humans->find($id);
            if (!$result) {
                return $this->notFoundResponse();
            }
            $this->humans->delete($id);
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode([
                'error' => 'Deleted'
            ]);
            return $response;
        }

        private function validateHuman($input)
        {
            if (!isset($input['first_name'])) {
                return false;
            }
            if (!isset($input['last_name'])) {
                return false;
            }
            return true;
        }

        private function invalidInputResponse()
        {
            $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
            $response['body'] = json_encode([
                'error' => 'Invalid Input'
            ]);
            return $response;
        }

        private function notFoundResponse()
        {
            $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
            $response['body'] = json_encode([
                'error' => 'Not Found'
            ]);
            return $response;
        }
    }
?>

