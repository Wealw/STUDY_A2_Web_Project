<?php

namespace App\Controller;

use App\Entity\Social\Month;
use App\Repository\EventRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{

    /**
     * @var EventRepository
     */
    private $repository;

    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/events/calendar", name="events.calendar")
     * @return Response
     * @throws \Exception
     */
    public function thisMonth(): Response
    {
        $month = new Month();
        $thisMonth = new DateTime();
        $weeks = $month->getWeeks();

        $firstDay = $month->getStartingDay();
        $start = $firstDay->format('N') == 1 ? $firstDay : $month->getStartingDay()->modify('last monday');
        $lastDay = $start->modify("+" . (6 + 7 * ($weeks -1)) . " days");

        $events = $this->eventsByDay($start, $lastDay);

        $navigation = $this->getPrevNext($thisMonth->format('m'), $thisMonth->format('Y'));

        return $this->render("calendar/index.html.twig", [
            'month' => $month,
            'weeks' => $weeks,
            'start' => $start,
            'firstDay' => $firstDay,
            'days' => $month->getDays(),
            'events' => $events,
            'prevMonth' => $navigation[0],
            'prevYear' => $navigation[1],
            'nextMonth' => $navigation[2],
            'nextYear' => $navigation[3]
        ]);
    }

    /**
     * @Route("/events/calendar/{monthNumber}-{yearNumber}", name="events.calendar.other")
     * @param $monthNumber
     * @param $yearNumber
     * @return Response
     * @throws \Exception
     */
    public function otherMonth($monthNumber, $yearNumber)
    {
        $month = new Month($monthNumber, $yearNumber);
        $weeks = $month->getWeeks();

        $start = $month->getStartingDay();
        $start = $start->format('N') == 1 ? $start : $month->getStartingDay()->modify('last monday');
        $lastDay = $start->modify("+" . (6 + 7 * ($weeks -1)) . " days");

        $events = $this->eventsByDay($start, $lastDay);
        //$events = $this->eventsByDay(new \DateTime('2020-08-01'), new \DateTime('2020-08-31'));

        $navigation = $this->getPrevNext($monthNumber, $yearNumber);

        return $this->render("calendar/index.html.twig", [
            'weeks' => $weeks,
            'start' => $start,
            'days' => $month->getDays(),
            'events' => $events,
            'prevMonth' => $navigation[0],
            'prevYear' => $navigation[1],
            'nextMonth' => $navigation[2],
            'nextYear' => $navigation[3]
        ]);
    }

    /**
     * Retourne les mois et les années précédentes et suivantes
     * @param $month
     * @param $year
     * @return array
     */
    private function getPrevNext($month, $year)
    {
        if ($month == 1) {
            $prevMonth = 12;
            $prevYear = $year - 1;
        } else {
            $prevMonth = $month - 1;
            $prevYear = $year;
        }

        if ($month == 12) {
            $nextMonth = 1;
            $nextYear = $year + 1;
        } else {
            $nextMonth = $month + 1;
            $nextYear = $year;
        }

        if (strlen($prevMonth) == 1) {
            $prevMonth = 0 . $prevMonth;
        }

        if (strlen($nextMonth) == 1) {
            $nextMonth = 0 . $nextMonth;
        }

        return [$prevMonth, $prevYear, $nextMonth, $nextYear];
    }

    /**
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     * @return array
     */
    private function eventsByDay(\DateTimeInterface $start, \DateTimeInterface $end)
    {
        $events = $this->repository->findBetween($start, $end);
        $days = [];

        foreach ($events as $event) {
            $date = explode( ' ', $event->getEventDate()->format('Y-m-d H:i:s'))[0];
            if (!isset($days[$date])) {
                $days[$date] = [$event];
            } else {
                $days[$date][] = $event;
            }
        }
        return $days;
    }

}