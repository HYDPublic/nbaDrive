<?php

namespace FantasyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pick
 *
 * @ORM\Table(name="pf_pick")
 * @ORM\Entity(repositoryClass="FantasyBundle\Repository\PickRepository")
 */
class Pick
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
    * @ORM\ManyToOne(targetEntity="PlayoffBundle\Entity\Player")
    * @ORM\JoinColumn(nullable=false)
    */
    private $player;

    /**
    * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
    * @ORM\JoinColumn(nullable=false)
    */
    private $user;

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Pick
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
     * Set player
     *
     * @param \PlayoffBundle\Entity\Player $player
     *
     * @return Pick
     */
    public function setPlayer(\PlayoffBundle\Entity\Player $player)
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
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Pick
     */
    public function setUser(\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
