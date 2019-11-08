<?php


namespace App\Controller;


use App\Entity\Social\Month;
use App\Repository\EventRepository;
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
        $weeks = $month->getWeeks();

        $start = $month->getStartingDay();
        $start = $start->format('N') == 1 ? $start : $month->getStartingDay()->modify('last monday');
        $lastDay = $start->modify("+" . (6 + 7 * ($weeks -1)) . " days");

        //$events = $this->eventsByDay($start, $lastDay);
        $events = $this->eventsByDay(new \DateTime('2020-08-01'), new \DateTime('2020-08-31'));

        dump($events);
        return $this->render("calendar/index.html.twig", [
            'weeks' => $weeks,
            'start' => $start,
            'days' => $month->getDays(),
            'events' => $events
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

        //$events = $this->eventsByDay($start, $lastDay);
        $events = $this->eventsByDay(new \DateTime('2020-08-01'), new \DateTime('2020-08-31'));

        dump($events);
        return $this->render("calendar/index.html.twig", [
            'weeks' => $weeks,
            'start' => $start,
            'days' => $month->getDays(),
            'events' => $events
        ]);
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