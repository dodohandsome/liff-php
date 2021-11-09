<?php
class DbConnection
{
    public $connect;
    private $host = "localhost";
    private $username = 'root';
    private $password = '';
    private $database = 'test_liff';
    public function __construct()
    {
        $this->database_connect();
    }
    public function database_connect()
    {
        $this->connect = mysqli_connect($this->host, $this->username, $this->password, $this->database);
        if (!$this->connect) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }
    public function findUserByLine($lineId)
    {
        $sql = "SELECT * FROM users WHERE line_id='{$lineId}'";
        $result = mysqli_query($this->connect, $sql);
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function createUser($value)
    {
        $sql = "INSERT INTO users (line_id, name,img,status,created_at,updated_at)
        VALUES (" . $value . ")";
        if (mysqli_query($this->connect, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($this->connect);
        }
    }
    public function updateUser($data)
    {
        $sql = "UPDATE users SET name='{$data->name}',img='{$data->img}',status='{$data->status}',updated_at='{$data->date}' WHERE line_id='{$data->lineId}'";
        if (mysqli_query($this->connect, $sql)) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . mysqli_error($this->connect);
        }
    }
}

$object = new DbConnection();
if (isset($_POST["action"])) {
    if ($_POST["action"] == "insert") {
        $data = (object)[
            'lineId' => $_POST["lineId"],
            'name' => $_POST["name"],
            'img' => $_POST["img"],
            'status' => $_POST["status"],
            'date' => time(),
        ];
        $value = "'{$data->lineId}','{$data->name}','{$data->img}','{$data->status}',{$data->date},{$data->date}";
        $rsUser = $object->findUserByLine($data->lineId);
        if ($rsUser) {
            echo $object->updateUser($data);
        } else {
            echo $object->createUser($value);
        }
    }
}
