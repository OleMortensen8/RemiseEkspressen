<?php
class BookableCell
{
    private $booking;
 
    private $currentURL;
     public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        $this->currentURL = htmlentities($_SERVER['REQUEST_URI']);
    }
 
    public function update(Calendar $cal)
    {
        if ($this->isDateBooked($cal->getCurrentDate()) && $this->isDatePending($cal->getCurrentDate())) {
            return $cal->cellContent =
                $this->pendingCell($cal->getCurrentDate());
        }

        if ($this->isDateBooked($cal->getCurrentDate()) && !$this->isDatePending($cal->getCurrentDate())) {
            return $cal->cellContent =
                $this->bookedCell($cal->getCurrentDate());
        }
 
        if (!$this->isDateBooked($cal->getCurrentDate())) {
            return $cal->cellContent =
                $this->openCell($cal->getCurrentDate());
        }
    }
 
    public function routeActions()
    {
        if (isset($_GET['book'])) {
            $name = $_GET['name'];
            $dato = $_GET['date'];
            $mailer = $_GET['receiver'];
           $this->booking->prove($dato);
          include('confirmation_mail.php');
        }
        if (isset($_GET['delete'])) {
            $this->deleteBooking($_GET['date']);
            include('rejected_mail.php');
        }
        if (isset($_POST['add'])) {
            $pendingDay = $_POST['date'];
            $name = $_POST['navnet'];
            $adress = $_POST['adresse'];
            $tel = $_POST['telefon'];
            $mailer = $_POST['mail'];
            if(!empty($mailer) && !empty($tel) && !empty($name)){
                $this->addBooking($pendingDay, 0, $name);
                include('phpmailer.php');
                include('phpmailer_2.php');
                $pendingDay= new DateTimeImmutable($pendingDay);
                $month = $pendingDay->format('m');
                $year = $pendingDay->format('Y');
        }
    }
}
    private function openCell($date)
    {
        return '<div class="open" value="'. date('Y-m-d', strtotime($date)) .'">' . date('j',strtotime($date)) . '</div>';
    }

    private function pendingCell($date)
    {
        return '<div class="pending">' . date('j',strtotime($date)) . '</div>';
    }
  
 
    private function isDatePending($date)
    {
        return in_array($date, $this->pendingDates());
    }

    private function pendingDates()
    {
        return array_map(function ($record) {
            if($record['approved'] == false){
                return $record['booking_date'];
            }
        }, $this->booking->index());
    }
 
    
    private function isDateBooked($date)
    {
        return in_array($date, $this->bookedDates());
    }
 
    private function bookedDates()
    {
        return array_map(function ($record) {
                return $record['booking_date'];
        }, $this->booking->index());
    }
    private function bookedCell($date)
    {
        return '<div class="booked">' . date('j',strtotime($date)) . '</div>';
    }
 
    private function deleteBooking($id)
    {
        $date = new DateTimeImmutable($id);
        $this->booking->delete($date);
    }
 
    private function addBooking(String $date, Bool $proof, $name)
    {
            $date = new DateTimeImmutable($date);
            $this->booking->add($date, $proof, $name);
     }

    public function bookingForm()
    {
        echo
            '<span class="close">x</span><form  id="form1" style="" method="post" action="">' .
            '<input type="hidden" name="add" />' .
            '<input type="text" id="navnet" name="navnet" placeholder="Navn" />' .
            '<input type="text" id="adresse" name="adresse" placeholder="Adresse" />' .
            '<input type="tel" id="telefon" name="telefon" placeholder="Telefon" />' .
            '<input type="email" id="mail" name="mail" placeholder="Email"/>' .
            '<input id="date" type="hidden" name="date"  value="" />' .
            
            '<input id="sub" class="submit" type="submit" value="Book" />' .
            '</form>';
    }
}