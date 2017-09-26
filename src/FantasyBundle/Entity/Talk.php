<?php

namespace FantasyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Talk
 *
 * @ORM\Table(name="pf_talk")
 * @ORM\Entity(repositoryClass="FantasyBundle\Repository\TalkRepository")
 */
class Talk
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
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
    * @ORM\ManyToOne(targetEntity="FantasyBundle\Entity\League")
    * @ORM\JoinColumn(nullable=false)
    */
    private $league;

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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Talk
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Talk
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set league
     *
     * @param \FantasyBundle\Entity\League $league
     *
     * @return Talk
     */
    public function setLeague(\FantasyBundle\Entity\League $league)
    {
        $this->league = $league;

        return $this;
    }

    /**
     * Get league
     *
     * @return \FantasyBundle\Entity\League
     */
    public function getLeague()
    {
        return $this->league;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Talk
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
