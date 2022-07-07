<?php
class BookableCell
{
    private $booking;
 
    private $currentURL;

    private int $Maxspace = 30;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        $this->currentURL = htmlentities($_SERVER['REQUEST_URI']);
    }
 
    public function update(Calendar $cal)
    {
        /*
        if ($this->isDateBooked($cal->getCurrentDate()) && $this->isDatePending($cal->getCurrentDate())) {
            return $cal->cellContent =
                $this->pendingCell($cal->getCurrentDate());
        }

        if ($this->isDateBooked($cal->getCurrentDate()) && !$this->isDatePending($cal->getCurrentDate())) {
            return $cal->cellContent =
                $this->bookedCell($cal->getCurrentDate());
        }
        */
        if (!$this->isDateBooked($cal->getCurrentDate())) {
            return $cal->cellContent =
                $this->openCell($cal->getCurrentDate(), $this->Maxspace);
        }
    }

    private function openCell($date, $space)
    {
        return '<a href="index-11.php?date=' . date('Y-m-d', strtotime($date)) .'"><div class="open" value="'. date('Y-m-d', strtotime($date)) .'">' . date('j',strtotime($date))
        . '<div class="free">'  . $space .'/' . $space . ' Tilgængelige sæder</div></div></a>';
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