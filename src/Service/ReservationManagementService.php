<?php
namespace App\Service;

use App\Entity\ReservationEntity;

class ReservationManagementService
{
    public function calcPrice(ReservationEntity $reservation, int $priceWeekDay = 50, int $priceWeekendDay = 75)
    {
        $weekDayChildrenPrice = 0;
        $weekendDayChildrenPrice = 0;
        $dateFrom = $reservation->getDateFrom();
        $dateTo = $reservation->getDateTo();
        $adults = $reservation->getAdults();
        $children = $reservation->getChildren();
        $weekendDays = $this->countWeekendDays($dateFrom, $dateTo);
        $weekDays = $this->countDays($dateFrom, $dateTo);
        if ($children > 0) {
            $weekDayChildrenPrice = (($weekDays - $weekendDays) * ($priceWeekDay / 3)) * $children;
            $weekendDayChildrenPrice = ($weekendDays * ($priceWeekendDay / 3)) * $children;
        }
        $weekendAdultsPrice = ($weekendDays * $priceWeekendDay) * $adults;
        $weekDaysAdultsPrice = (($weekDays - $weekendDays) * $priceWeekDay) * $adults;
        return $weekendAdultsPrice + $weekDaysAdultsPrice + $weekDayChildrenPrice + $weekendDayChildrenPrice;
    }

    function countWeekendDays(\DateTime $dateOne, \DateTime $dateTwo, $weekendDays = [6, 7])
    {
        $weekendCount = 0;
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($dateOne, $interval, $dateTwo->modify('+1 day'));

        foreach ($period as $date) {
            $dayOfWeek = (int) $date->format('N');
            if (in_array($dayOfWeek, $weekendDays)) {
                $weekendCount++;
            }
        }

        return $weekendCount;
    }
    private function countDays(\DateTime $dateOne, \DateTime $dateTwo)
    {
        $interval = $dateOne->diff($dateTwo, true);
        return $interval->days - 1;
    }
    function hasWeekend(\DateTime $dateOne, \DateTime $dateTwo, $weekendDays = ["6", "7"])
    {
        $diff = $dateTwo->diff($dateOne)->format('%a');
        $weekendCount = 0;

        while ($diff > 0) {
            $date = $dateTwo->sub(new \DateInterval('P1D'));
            $day = $date->format('N');
            if (in_array($day, $weekendDays)) {
                ++$weekendCount;
                break;
            }

            $diff--;
        }

        return $weekendCount;
    }


}