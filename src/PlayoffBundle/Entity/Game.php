<?php

namespace PlayoffBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table(name="pf_game")
 * @ORM\Entity(repositoryClass="PlayoffBundle\Repository\GameRepository")
 */
class Game
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
     * @var string
     *
     * @ORM\Column(name="aid", type="string", length=30, nullable=true)
     */
    private $aid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="day", type="date")
     */
    private $day;

    /**
     * @var string
     *
     * @ORM\Column(name="score", type="string", length=7, nullable=true)
     */
    private $score;

    /**
    * @ORM\Column(name="round", type="integer", length=2, nullable=true)
    */
    private $round;

    /**
    * @ORM\ManyToOne(targetEntity="PlayoffBundle\Entity\Team")
    * @ORM\JoinColumn(nullable=false)
    */
    private $teamExt;

    /**
    * @ORM\ManyToOne(targetEntity="PlayoffBundle\Entity\Team")
    * @ORM\JoinColumn(nullable=false)
    */
    private $teamDom;
    
    /**
    * @ORM\ManyToOne(targetEntity="PlayoffBundle\Entity\Team")
    * @ORM\JoinColumn(nullable=true)
    */
    private $winner;

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
     * Set aid
     *
     * @param string $aid
     *
     * @return Game
     */
    public function setAid($aid)
    {
        $this->aid = $aid;

        return $this;
    }

    /**
     * Get aid
     *
     * @return string
     */
    public function getAid()
    {
        return $this->aid;
    }

    /**
     * Set dateGMT
     *
     * @param \DateTime $dateGMT
     *
     * @return Game
     */
    public function setDateGMT($dateGMT)
    {
        $this->dateGMT = $dateGMT;

        return $this;
    }

    /**
     * Get dateGMT
     *
     * @return \DateTime
     */
    public function getDateGMT()
    {
        return $this->dateGMT;
    }

    /**
     * Set score
     *
     * @param string $score
     *
     * @return Game
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return string
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set teamExt
     *
     * @param \PlayoffBundle\Entity\Team $teamExt
     *
     * @return Game
     */
    public function setTeamExt(\PlayoffBundle\Entity\Team $teamExt)
    {
        $this->teamExt = $teamExt;

        return $this;
    }

    /**
     * Get teamExt
     *
     * @return \PlayoffBundle\Entity\Team
     */
    public function getTeamExt()
    {
        return $this->teamExt;
    }

    /**
     * Set teamDom
     *
     * @param \PlayoffBundle\Entity\Team $teamDom
     *
     * @return Game
     */
    public function setTeamDom(\PlayoffBundle\Entity\Team $teamDom)
    {
        $this->teamDom = $teamDom;

        return $this;
    }

    /**
     * Get teamDom
     *
     * @return \PlayoffBundle\Entity\Team
     */
    public function getTeamDom()
    {
        return $this->teamDom;
    }

    /**
     * Set winner
     *
     * @param \PlayoffBundle\Entity\Team $winner
     *
     * @return Game
     */
    public function setWinner(\PlayoffBundle\Entity\Team $winner = null)
    {
        $this->winner = $winner;

        return $this;
    }

    /**
     * Get winner
     *
     * @return \PlayoffBundle\Entity\Team
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Game
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set day
     *
     * @param \DateTime $day
     *
     * @return Game
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

    /**
     * Set round
     *
     * @param integer $round
     *
     * @return Game
     */
    public function setRound($round)
    {
        $this->round = $round;

        return $this;
    }

    /**
     * Get round
     *
     * @return integer
     */
    public function getRound()
    {
        return $this->round;
    }
}
