<?php

namespace App\Entity\Social;

class Month {

    private $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

    private $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    public $month;
    public $year;

    /**
     * Month constructor.
     * @param int $month
     * @param int $year
     * @throws \Exception
     */
    public function __construct(?int $month = null, ?int $year = null) {
        if ($month === null || $month < 1 || $month > 12) {
            $month = intval(date('m'));
        }
        if ($year === null) {
            $year = intval(date('Y'));
        }

        $this->month = $month;
        $this->year = $year;
    }

    /**
     * Renvoie le premier jour du mois
     * @return \DateTimeImmutable
     * @throws \Exception
     */
    public function getStartingDay() : \DateTimeInterface {
        return new \DateTimeImmutable("{$this->year}-{$this->month}-01");
    }

    /**
     * Retourne le mois en toute lettre
     * @return string
     */
    public function toString() : string {
        return $this->months[$this->month -1] . ' ' . $this->year;
    }

    /**
     * Retourne le nombre de semaine à afficher dans le calendrier
     * @return int
     * @throws \Exception
     */
    public function getWeeks() : int {
        $start = $this->getStartingDay();
        $end = $start->modify('1 month -1 day');
        $startWeek = intval($start->format('W'));
        $endWeek = intval($end->format('W'));

        if ($endWeek === 1) {
            $endWeek = intval($end->modify("- 7 days")->format('W')) + 1;
        }
        $weeks = $endWeek - $startWeek + 1;

        if ($weeks < 0) {
            $weeks = intval($end->format('W'));
        }

        return $weeks;
    }

    /**
     * Est-ce que le jour est dans le mois en cours ou pas
     * @param \DateTime $date
     * @return bool
     * @throws \Exception
     */
    public function withinMonth(\DateTimeInterface $date) : bool {
        return $this->getStartingDay()->format('Y-m') === $date->format('Y-m');
    }

    /**
     * Retourne une instance de Month avec le mois suivant
     * @return Month
     * @throws \Exception
     */
    public function nextMonth() : Month {
        $month = $this->month + 1;
        $year = $this->year;
        if ($month > 12) {
            $month = 1;
            $year += 1;
        }
        return new Month($month, $year);
    }

    /**
     * Retourne une instance de Month avec le mois précédent
     * @return Month
     * @throws \Exception
     */
    public function prevMonth() : Month {
        $month = $this->month - 1;
        $year = $this->year;
        if ($month < 1) {
            $month = 12;
            $year -= 1;
        }
        return new Month($month, $year);
    }

    /**
     * @return array
     */
    public function getDays() {
        return $this->days;
    }

}