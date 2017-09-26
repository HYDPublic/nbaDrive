<?php

namespace PlayoffBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Player
 *
 * @ORM\Table(name="pf_player")
 * @ORM\Entity(repositoryClass="PlayoffBundle\Repository\PlayerRepository")
 */
class Player
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="aid", type="string", length=255)
     */
    private $aid;

    /**
     * @var array
     *
     * @ORM\Column(name="season_stats", type="array", nullable=true)
     */
    private $seasonStats;

    /**
    * @ORM\ManyToOne(targetEntity="PlayoffBundle\Entity\Team")
    * @ORM\JoinColumn(nullable=true)
    */
    private $team;


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
     * Set name
     *
     * @param string $name
     *
     * @return Player
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set aid
     *
     * @param string $aid
     *
     * @return Player
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
     * Set seasonStats
     *
     * @param array $seasonStats
     *
     * @return Player
     */
    public function setSeasonStats($seasonStats)
    {
        $this->seasonStats = $seasonStats;

        return $this;
    }

    /**
     * Get seasonStats
     *
     * @return array
     */
    public function getSeasonStats()
    {
        return get_object_vars($this->seasonStats);
    }

    /**
     * Set team
     *
     * @param \PlayoffBundle\Entity\Team $team
     *
     * @return Player
     */
    public function setTeam(\PlayoffBundle\Entity\Team $team = null)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * Get team
     *
     * @return \PlayoffBundle\Entity\Team
     */
    public function getTeam()
    {
        return $this->team;
    }

}
