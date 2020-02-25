<?php

namespace App\Service;

use App\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingService extends AbstractController
{

    /**
     * Permet de retourner un tableau qui contient les dates non disponible les 31 prochains jours
     *
     */
    public function getNotAvailable()
    {
        $notAvailable = [];

        // On enlève les jours indisponibles
        $result = $this->getDoctrine()->getRepository(Booking::class)->findByReservationAt();

        foreach ($result as $value)
        {
            if ($value['nbReservation'] >= 100)
            {
                $notAvailable[] = $value['reservationAt'];
            }
        }

        return $notAvailable;
    }

    /**
     * Permet de vérifier si la date de la réservation correspond bien au critère.
     *
     * @param $bookingAt
     */
    public function notReservableDate($bookingAt)
    {
        $tomorrow = new \Datetime("1 days");
        if ($bookingAt->format('Ymd') < $tomorrow->format('Ymd') || $bookingAt > new \Datetime("32 days"))
        {
            return true;
        }
        
        if ($bookingAt->format('D') == "Mon" || $bookingAt->format('D') == "Sun")
        {
            return true;
        }
        foreach ($this->getNotAvailable() as $value)
        {
            if ($value == $bookingAt)
            {
                return true;
            }
        }
    }

    /** 
     * Permet de retourner un tableau des plages horaires disponible
     *
     * @param $bookingAt
     * @return void
     */
    public function getSlotAvailable($bookingAt)
    {
        $slotAvailable = [];
        $slot = [];

        for($i = 10; $i<20; $i++)
        {
            $slot[] = ['time' => $i.":00", 'dispo' => 10];
        }
        
        $result = $this->getDoctrine()->getRepository(Booking::class)->findByBookingAt($bookingAt);

        foreach ($result as $value)
        {
            if ($value['nbSlot'] <= 10)
            {
                $slotAvailable[] = ['time' => $value['timeSlot']->format('H:i'), 'dispo' => 10 - $value['nbSlot']];
            }
        }

        foreach($slot as &$value1)
        {
            foreach($slotAvailable as $value2)
            {
                if ($value1['time'] == $value2['time'])
                {
                    $value1['dispo'] = $value2['dispo'];
                }
            }
        }

        return json_encode($slot);
    }

    /**
     * Permet de vérifier si le créneau horraire correspond bien au critère
     *
     * @param $bookingAt
     * @param $time
     * @param $bookings
     */
    public function notReservableTime($bookingAt, $time, $bookings)
    {
        $timeArray = json_decode($this->getSlotAvailable($bookingAt->format('d/m/Y')),true);

        $notFindTime = true;

        foreach ($timeArray as $value)
        {
            $notAvailableTime = new \Datetime($value['time']);
            
            if ($notAvailableTime == $time)
            {
                $notFindTime = false;

                if ($bookings > $value['dispo'] || $bookings > 5)
                {
                    $notFindTime = true;
                }
            }
        }
        
        return $notFindTime;
    }

    /**
     * Vérifie si l'utilisateur a déjà une réservation en cours
     *
     * @return void
     */
    public function userBooking()
    {
        return $this->getDoctrine()->getRepository(Booking::class)->findByDate($this->getUser());
    }

    /**
     * Retourne la date demandée au format désiré
     *
     * @param [type] $date
     * @return void
     */
    public function dateToFr($date)
    {
        $days = $date->format('D');
        switch ($days)
        {
            case 'Mon':
                $days = "Lundi";
                break;
            case 'Tue':
                $days = "Mardi";
                break;
            case 'Wed':
                $days = "Mercredi";
                break;
            case 'Thu':
                $days = "Jeudi";
                break;
            case 'Fri':
                $days = "Vendredi";
                break;
            case 'Sat':
                $days = "Samedi";
                break;
            case 'Sun':
                $days = "Dimanche";
                break;
        }

        $months = $date->format('m');
        switch ($months)
        {
			case "01":
                $months = "Janvier";
                break;
            case "02":
                $months = "Février";
                break;
            case "03":
                $months = "Mars";
                break;
            case "04":
                $months = "Avril";
                break;
            case "05":
                $months = "Mai";
                break;
            case "06":
                $months = "Juin";
                break;
            case "07":
                $months = "Juillet";
                break;
            case "08":
                $months = "Aout";
                break;
            case "09":
                $months = "Septembre";
                break;
            case "10":
                $months = "Octobre";
                break;
            case "11":
                $months = "Novembre";
                break;
            case "12":
                $months = "Décembre";
                break;
        }

        return $days . " " . $date->format('d') . " ". $months; 
    }
}