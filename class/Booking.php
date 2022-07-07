<?php 
class Booking
{
    private $dbh = null;
    private $bookingsTableName = 'booking';
    public function __construct()
    {
        try {          
            $this->dbh = new PDO(
                sprintf('mysql:host=%s;dbname=%s', getenv('HOST'), getenv('DATABASE')),  
                getenv('USER'),
                getenv('PASSWORD')
            );
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
 
    public function index()
    {
        $statement = $this->dbh->query('SELECT * FROM ' . $this->bookingsTableName);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add(DateTimeImmutable $bookingDate, Bool $bool, String $name)
    {
        $Date = $bookingDate->format('Y-m-d'); 
        $statement = $this->dbh->prepare('INSERT INTO ' . $this->bookingsTableName . ' (booking_date, approved, Navn) VALUES (:bookingDate, :proof, :name)');
        $statement->bindParam(':bookingDate', $Date);
        $statement->bindParam(':proof', $bool);
        $statement->bindParam(':name', $name);
        $statement->execute();
        if (false === $statement) {
            throw new Exception('Invalid prepare statement');
        }
    }
    public function prove($date)
    {
        $date = new DateTimeImmutable($date);
        $bool = 1;
        $statement = $this->dbh->prepare(
            'UPDATE ' . $this->bookingsTableName . ' SET approved = :proof WHERE booking_date = :bookingDate'
        );
        if (false === $statement) {
            throw new Exception('Invalid prepare statement');
        }
        if (false === $statement->execute([
                ':bookingDate' => $date->format('Y-m-d'),
                ':proof' => $bool
            ])) {
            throw new Exception(implode(' ', $statement->errorInfo()));
        }
    }
    public function delete($id){
        $statement = $this->dbh->prepare(
            'DELETE FROM ' . $this->bookingsTableName . ' WHERE booking_date = :id');
        $statement->bindParam(':id', $id->format('Y-m-d'));
        $statement->execute();
       
        if (false === $statement) {
            throw new Exception('Invalid prepare statement');
        }
    }
 
}