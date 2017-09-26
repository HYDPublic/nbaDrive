<?php

namespace PlayoffBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Statsheet
 *
 * @ORM\Table(name="pf_statsheet")
 * @ORM\Entity(repositoryClass="PlayoffBundle\Repository\StatsheetRepository")
 */
class Statsheet
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var array
     *
     * @ORM\Column(name="stats", type="array")
     */
    private $stats;

    /**
     * @var int
     *
     * @ORM\Column(name="points", type="integer")
     */
    private $points;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="day", type="date")
     */
    private $day;

    /**
    * @ORM\ManyToOne(targetEntity="PlayoffBundle\Entity\Player")
    * @ORM\JoinColumn(nullable=true)
    */
    private $player;

    /**
    * @ORM\ManyToOne(targetEntity="PlayoffBundle\Entity\Game")
    * @ORM\JoinColumn(nullable=true)
    */
    private $game;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set stats
     *
     * @param array $stats
     *
     * @return Statsheet
     */
    public function setStats($stats)
    {
        $this->stats = $stats;

        return $this;
    }

    /**
     * Get stats
     *
     * @return array
     */
    public function getStats()
    {
        return $this->stats;
    }

    /**
     * Set points
     *
     * @param integer $points
     *
     * @return Statsheet
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set player
     *
     * @param \PlayoffBundle\Entity\Player $player
     *
     * @return Statsheet
     */
    public function setPlayer(\PlayoffBundle\Entity\Player $player = null)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return \PlayoffBundle\Entity\Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Set game
     *
     * @param \PlayoffBundle\Entity\Game $game
     *
     * @return Statsheet
     */
    public function setGame(\PlayoffBundle\Entity\Game $game = null)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return \PlayoffBundle\Entity\Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Set day
     *
     * @param \DateTime $day
     *
     * @return Statsheet
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day
     *
     * @return \DateTime
     */
    public function getDay()
    {
        return $this->day;
    }
}
